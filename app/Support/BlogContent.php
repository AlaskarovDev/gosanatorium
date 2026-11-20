<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Task 20: prepares a blog post body for the post template —
 * adds anchor ids to H2 headings (table of contents) and finds the place
 * for the CTA block after the first third of the text.
 */
class BlogContent
{
    /** @return array{toc: array<int, array{id: string, text: string}>, first: string, rest: string} */
    public static function prepare(?string $html): array
    {
        $html = (string) $html;
        $toc = [];
        $used = [];

        $html = preg_replace_callback('/<h2\b([^>]*)>(.*?)<\/h2>/isu', function ($m) use (&$toc, &$used) {
            $attrs = $m[1];
            $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($m[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
            if ($text === '') {
                return $m[0];
            }
            if (preg_match('/\bid\s*=\s*["\']([^"\']+)["\']/i', $attrs, $idm)) {
                $id = $idm[1];
            } else {
                $base = Str::slug(Str::limit($text, 60, ''), '-') ?: 'section';
                $id = $base;
                $i = 2;
                while (isset($used[$id])) {
                    $id = $base . '-' . $i++;
                }
                $attrs .= ' id="' . e($id) . '"';
            }
            $used[$id] = true;
            $toc[] = ['id' => $id, 'text' => $text];

            return '<h2' . $attrs . '>' . $m[2] . '</h2>';
        }, $html);

        [$first, $rest] = self::splitAtThird($html);

        return ['toc' => $toc, 'first' => $first, 'rest' => $rest];
    }

    /** Split before the first H2 after 1/3 of the text, or after the first paragraph after 1/3. */
    private static function splitAtThird(string $html): array
    {
        $len = strlen($html);
        if ($len < 1500) {
            return [$html, ''];
        }
        $third = (int) ($len / 3);

        $pos = null;
        if (preg_match('/<h2\b/i', $html, $m, PREG_OFFSET_CAPTURE, $third)) {
            $pos = $m[0][1];
        }
        if ($pos === null || $pos > $len * 0.7) {
            if (preg_match('/<\/p>/i', $html, $m, PREG_OFFSET_CAPTURE, $third)) {
                $pos = $m[0][1] + 4;
            }
        }
        if ($pos === null || $pos >= $len - 200 || self::insideBlock($html, $pos)) {
            return [$html, ''];
        }

        return [substr($html, 0, $pos), substr($html, $pos)];
    }

    private static function insideBlock(string $html, int $pos): bool
    {
        $before = strtolower(substr($html, 0, $pos));
        foreach (['table', 'ul', 'ol', 'blockquote', 'div', 'figure'] as $tag) {
            if (preg_match_all('/<' . $tag . '\b/', $before) > substr_count($before, '</' . $tag . '>')) {
                return true;
            }
        }

        return false;
    }
}
