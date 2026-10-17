#!/usr/bin/env bash
# TaskDesk check — installed by TaskDesk. Do not edit by hand.
#
# Usage: scripts/security-check.sh <base-ref>     (e.g. origin/main)
# Enforces the one rule: the change must not expose the project's source code,
# configuration or secrets. Exits non-zero when the diff adds code that could do so.
# A failure never blocks the task — it only stops automatic publishing.
# The task workflow runs the copy from the BASE branch, so a task cannot weaken it.
set -uo pipefail

BASE="${1:-origin/main}"
fail=0

say() { printf '%s\n' "$*"; }
problem() { say "FAIL  $*"; fail=1; }

if ! git rev-parse --verify -q "$BASE" > /dev/null; then
  say "FAIL  base ref $BASE not found"; exit 1
fi

mapfile -t FILES < <(git diff --name-only "$BASE"...HEAD)
say "Changed files: ${#FILES[@]}"
added="$(git diff --numstat "$BASE"...HEAD | awk '$1 ~ /^[0-9]+$/ { s += $1 } END { print s + 0 }')"
say "Added lines: $added"

# Secrets / environment files must never be committed.
for f in "${FILES[@]}"; do
  case "$f" in
    .env|.env.*|*/.env|*/.env.*) [[ "$f" == *.example ]] || problem "environment file committed: $f" ;;
    *.pem|*.key|*id_rsa*|*.p12|*.pfx|*.sql|*.sql.gz|*.sqlite) problem "secret or database file committed: $f" ;;
  esac
done

# Code that can reveal source, config or secrets to visitors.
PATTERNS=(
  'source display|(highlight_file|show_source|php_strip_whitespace)[[:space:]]*\('
  'phpinfo|phpinfo[[:space:]]*\('
  'reads own source|(file_get_contents|readfile|fopen|file)[[:space:]]*\([^)]*__(FILE|DIR)__'
  'serves project files|(readfile|file_get_contents|response\(\)->(file|download)|Storage::download)[[:space:]]*\([^)]*(base_path|app_path|config_path|resource_path|database_path|\.env|\$_(GET|POST|REQUEST)|\$request->)'
  'exposes environment|print_r[[:space:]]*\([[:space:]]*\$_(ENV|SERVER)|var_dump[[:space:]]*\([[:space:]]*\$_(ENV|SERVER)|json_encode[[:space:]]*\([[:space:]]*(\$_ENV|\$_SERVER|config\(\)|getenv\(\))'
  'directory listing|(scandir|opendir|glob|File::(allFiles|files|directories))[[:space:]]*\([^)]*(base_path|app_path|resource_path|config_path|\$_(GET|POST|REQUEST)|\$request->)'
  'remote code|(eval|assert)[[:space:]]*\([[:space:]]*\$_(GET|POST|REQUEST|COOKIE)|(shell_exec|exec|system|passthru|popen|proc_open)[[:space:]]*\([^)]*\$_(GET|POST|REQUEST|COOKIE)|eval[[:space:]]*\([[:space:]]*base64_decode'
  'blade raw source|@php[[:space:]]+(highlight_file|show_source|readfile)'
)

for f in "${FILES[@]}"; do
  case "$f" in
    *.php|*.phtml|*.inc|*.js|*.mjs|*.ts|*.vue|*.html|*.htm|*.twig) ;;
    *) continue ;;
  esac
  git cat-file -e "HEAD:$f" 2>/dev/null || continue
  lines="$(git diff -U0 --no-color "$BASE"...HEAD -- "$f" | grep -E '^\+' | grep -vE '^\+\+\+ ' || true)"
  [ -z "$lines" ] && continue
  for entry in "${PATTERNS[@]}"; do
    label="${entry%%|*}"
    regex="${entry#*|}"
    hits="$(printf '%s\n' "$lines" | grep -iEn -- "$regex" || true)"
    if [ -n "$hits" ]; then
      problem "$label in $f"
      printf '%s\n' "$hits" | head -n 5 | sed 's/^/        /'
    fi
  done
done

# Web-server rules that would serve dotfiles or source.
for f in "${FILES[@]}"; do
  case "$f" in
    .htaccess|*/.htaccess|nginx*.conf|*/web.config)
      if git diff -U0 "$BASE"...HEAD -- "$f" | grep -E '^\+' | grep -qiE 'Options[[:space:]]+\+?Indexes|Allow from all.*\.(env|php)|Require all granted.*\.(env|git)'; then
        problem "server rule may expose files: $f"
      fi ;;
  esac
done

if [ "$fail" -eq 0 ]; then say "PASS  nothing exposes source code, configuration or secrets"; fi
exit "$fail"
