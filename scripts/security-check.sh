#!/usr/bin/env bash
# TaskDesk security check — installed by TaskDesk. Do not edit by hand.
#
# Usage: scripts/security-check.sh <base-ref>     (e.g. origin/main)
# Inspects the changes between <base-ref> and HEAD. Exits non-zero when the diff
# adds dangerous PHP/JS patterns, touches protected paths or is unusually large.
# The task workflow runs the copy from the BASE branch, so a task cannot weaken it.
set -uo pipefail

BASE="${1:-origin/main}"
MAX_ADDED_LINES="${MAX_ADDED_LINES:-400}"
fail=0

say() { printf '%s\n' "$*"; }
problem() { say "FAIL  $*"; fail=1; }

if ! git rev-parse --verify -q "$BASE" > /dev/null; then
  say "FAIL  base ref $BASE not found"; exit 1
fi

mapfile -t FILES < <(git diff --name-only "$BASE"...HEAD)
say "Changed files: ${#FILES[@]}"

# 1. Protected paths
for f in "${FILES[@]}"; do
  case "$f" in
    .github/*|scripts/*|CLAUDE.md|.task-assets/*) problem "protected path changed: $f" ;;
    .env|.env.*|*/.env|*/.env.*) problem "environment file changed: $f" ;;
    .htaccess|*/.htaccess|wp-config.php|*/wp-config.php) problem "server/credential file changed: $f" ;;
    config/*database*|config/*credential*|config/*secret*|config/*services*|config/*mail*|config/*auth*) problem "credential config changed: $f" ;;
    *.pem|*.key|*id_rsa*|*.p12|*.pfx) problem "key material added: $f" ;;
  esac
done

# 2. Size of the change (binary files are ignored)
added="$(git diff --numstat "$BASE"...HEAD | awk '$1 ~ /^[0-9]+$/ { s += $1 } END { print s + 0 }')"
say "Added lines: $added (limit $MAX_ADDED_LINES)"
if [ "$added" -gt "$MAX_ADDED_LINES" ]; then problem "diff adds $added lines (limit $MAX_ADDED_LINES)"; fi

# 3. Dangerous patterns in ADDED lines of code/markup files
PATTERNS=(
  'eval|(^|[^A-Za-z0-9_.$])eval[[:space:]]*\('
  'assert|(^|[^A-Za-z0-9_.$])assert[[:space:]]*\('
  'shell execution|(^|[^A-Za-z0-9_.$>:])(shell_exec|exec|system|passthru|popen|proc_open)[[:space:]]*\('
  'backtick shell|^[^`]*`[^`]*\$[A-Za-z_][^`]*`[[:space:]]*;'
  'base64_decode|base64_decode[[:space:]]*\('
  'phpinfo|phpinfo[[:space:]]*\('
  'source disclosure|(highlight_file|show_source|readfile)[[:space:]]*\('
  '__FILE__ read-back|__FILE__'
  'directory listing|(scandir|opendir|(^|[^A-Za-z0-9_.$])glob)[[:space:]]*\('
  'file write|(file_put_contents|fopen|fwrite)[[:space:]]*\('
  'upload handling|move_uploaded_file|\$_FILES'
  'file deletion/permissions|(^|[^A-Za-z0-9_.$>:])(unlink|rename|chmod)[[:space:]]*\('
  'dynamic include|(include|require)(_once)?[[:space:]]*\(?[[:space:]]*\$'
  'environment access|getenv[[:space:]]*\(|\$_ENV'
  'outbound connection|curl_exec|fsockopen'
  'raw destructive SQL|(DROP[[:space:]]+(TABLE|DATABASE)|DELETE[[:space:]]+FROM|TRUNCATE[[:space:]])'
  'cookie access|document\.cookie'
  'iframe|<iframe'
)

for f in "${FILES[@]}"; do
  case "$f" in
    *.php|*.phtml|*.inc|*.js|*.mjs|*.cjs|*.ts|*.jsx|*.tsx|*.vue|*.svelte|*.html|*.htm|*.twig|*.tpl) ;;
    *) continue ;;
  esac
  git cat-file -e "HEAD:$f" 2>/dev/null || continue   # deleted file
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

if [ "$fail" -eq 0 ]; then say "PASS  no dangerous patterns, protected paths untouched"; fi
exit "$fail"
