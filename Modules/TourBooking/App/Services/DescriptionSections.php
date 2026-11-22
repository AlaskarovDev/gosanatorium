<?php

namespace Modules\TourBooking\App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Long sanatorium descriptions → short intro + collapsible sections.
 *
 * A section starts at a top-level heading (the main heading level of the text) or after an <hr>.
 * Its title is that heading, or a first paragraph that is only bold text («ПОЧЕМУ ИМЕННО …»).
 * Returns null when the text has no usable structure (then the page shows it with «Читать полностью»).
 */
class DescriptionSections
{
    /** Intro keeps adding blocks until it has at least this much text. */
    public const INTRO_MIN_CHARS = 350;

    /** Bold-only paragraph longer than this is text, not a title. */
    public const TITLE_MAX_CHARS = 140;

    /**
     * @return array{intro: string, sections: list<array{title: string, heading: ?string, body: string}>}|null
     */
    public static function split(?string $html): ?array
    {
        $html = trim((string) $html);
        if ($html === '' || mb_strlen(strip_tags($html)) < 1200) {
            return null;
        }

        $prev = libxml_use_internal_errors(true);
        $doc = new DOMDocument('1.0', 'UTF-8');
        $ok = $doc->loadHTML('<?xml encoding="utf-8"?><div id="gs-desc-root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        $root = $ok ? $doc->getElementById('gs-desc-root') : null;
        if (! $root) {
            return null;
        }

        // top-level nodes without empty text and empty paragraphs
        $nodes = [];
        foreach (iterator_to_array($root->childNodes) as $node) {
            if ($node->nodeType === XML_TEXT_NODE && trim($node->textContent) === '') {
                continue;
            }
            if ($node->nodeType === XML_COMMENT_NODE) {
                continue;
            }
            if ($node instanceof DOMElement && strtolower($node->nodeName) === 'p' && self::text($node) === '' && ! $node->getElementsByTagName('img')->length) {
                continue;
            }
            $nodes[] = $node;
        }

        // main heading level: the highest level that occurs at least twice at top level
        $counts = [];
        foreach ($nodes as $node) {
            $tag = strtolower($node->nodeName);
            if (in_array($tag, ['h2', 'h3', 'h4'], true)) {
                $counts[$tag] = ($counts[$tag] ?? 0) + 1;
            }
        }
        ksort($counts);
        $level = null;
        foreach ($counts as $tag => $n) {
            if ($n >= 2) {
                $level = $tag;
                break;
            }
        }

        // chunks split at <hr> and at main-level headings
        $chunks = [[]];
        foreach ($nodes as $node) {
            $tag = strtolower($node->nodeName);
            if ($tag === 'hr') {
                $chunks[] = [];
                continue;
            }
            $isBoundary = ($level !== null && $tag === $level) || self::isCapsTitle($node);
            if ($isBoundary && count(end($chunks)) > 0) {
                $chunks[] = [];
            }
            $chunks[count($chunks) - 1][] = $node;
        }
        $chunks = array_values(array_filter($chunks, fn ($c) => count($c) > 0));

        // titled sections; a chunk without a title joins the previous one
        $intro = [];
        $sections = [];
        foreach ($chunks as $chunk) {
            $title = self::title($chunk[0]);
            if ($title === null) {
                if ($sections) {
                    $sections[count($sections) - 1]['nodes'] = array_merge($sections[count($sections) - 1]['nodes'], $chunk);
                } else {
                    $intro = array_merge($intro, $chunk);
                }
                continue;
            }
            $sections[] = ['title' => $title['text'], 'heading' => $title['heading'], 'head' => $chunk[0], 'nodes' => array_slice($chunk, 1)];
        }

        // short intro (e.g. only a price table): the first sections stay open as intro
        while ($sections && mb_strlen(self::textOf($intro)) < self::INTRO_MIN_CHARS) {
            $first = array_shift($sections);
            $intro = array_merge($intro, [$first['head']], $first['nodes']);
        }

        if (count($sections) < 2) {
            return null;
        }

        $out = [];
        foreach ($sections as $s) {
            $out[] = [
                'title' => $s['title'],
                'heading' => $s['heading'],
                'body' => self::html($doc, $s['nodes']),
            ];
        }

        return ['intro' => self::html($doc, $intro), 'sections' => $out];
    }

    /** @return array{text: string, heading: ?string}|null */
    private static function title(DOMNode $node): ?array
    {
        if (! $node instanceof DOMElement) {
            return null;
        }
        $tag = strtolower($node->nodeName);
        $text = self::text($node);
        if ($text === '') {
            return null;
        }
        if (in_array($tag, ['h2', 'h3', 'h4', 'h5'], true)) {
            return ['text' => $text, 'heading' => $tag];
        }
        if ($tag === 'p' && mb_strlen($text) <= self::TITLE_MAX_CHARS) {
            $bold = '';
            foreach ($node->getElementsByTagName('strong') as $b) {
                $bold .= $b->textContent;
            }
            foreach ($node->getElementsByTagName('b') as $b) {
                $bold .= $b->textContent;
            }
            if (self::clean($bold) === $text && ! preg_match('/[.:;,]$/u', $text)) {
                return ['text' => $text, 'heading' => null];
            }
        }

        return null;
    }

    /** «ПРИ КАКИХ ЗАБОЛЕВАНИЯХ…» written as a bold upper-case paragraph starts a section like a heading. */
    private static function isCapsTitle(DOMNode $node): bool
    {
        $title = self::title($node);
        if ($title === null || $title['heading'] !== null) {
            return false;
        }
        $text = $title['text'];

        return preg_match('/\p{Lu}{3}/u', $text) && mb_strtoupper($text) === $text;
    }

    private static function text(DOMNode $node): string
    {
        return self::clean($node->textContent);
    }

    private static function clean(string $s): string
    {
        return trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $s));
    }

    /** @param list<DOMNode> $nodes */
    private static function textOf(array $nodes): string
    {
        // reading text only: headings and tables (price grids) do not count
        $nodes = array_filter($nodes, fn ($n) => ! in_array(strtolower($n->nodeName), ['h1', 'h2', 'h3', 'h4', 'h5', 'table'], true)
            && ! ($n instanceof DOMElement && $n->getElementsByTagName('table')->length));

        return implode(' ', array_map(fn ($n) => self::text($n), $nodes));
    }

    /** @param list<DOMNode> $nodes */
    private static function html(DOMDocument $doc, array $nodes): string
    {
        $html = '';
        foreach ($nodes as $node) {
            $html .= $doc->saveHTML($node) . "\n";
        }

        return $html;
    }
}
