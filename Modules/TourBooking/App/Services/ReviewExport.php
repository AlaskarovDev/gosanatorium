<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Services;

use Illuminate\Support\Collection;
use Modules\TourBooking\App\Models\Review;
use ZipArchive;

/**
 * Exports all reviews to an .xlsx file and marks reviews whose text
 * (or a sentence of it) repeats in another review.
 */
final class ReviewExport
{
    /** Sentences shorter than this are too generic to count as a repeat. */
    private const MIN_SENTENCE = 18;

    /**
     * @return array<int, array{ids: int[], sentences: string[]}> review id => duplicates info
     */
    public static function duplicates(Collection $reviews): array
    {
        $index = [];
        foreach ($reviews as $review) {
            foreach (self::sentences((string) $review->review) as $sentence) {
                $index[$sentence][$review->id] = true;
            }
        }

        $result = [];
        foreach ($index as $sentence => $ids) {
            if (count($ids) < 2) {
                continue;
            }
            $ids = array_keys($ids);
            foreach ($ids as $id) {
                $result[$id]['ids'] = array_values(array_unique(array_merge($result[$id]['ids'] ?? [], array_diff($ids, [$id]))));
                $result[$id]['sentences'][] = $sentence;
            }
        }
        foreach ($result as &$row) {
            sort($row['ids']);
        }

        return $result;
    }

    /** @return string[] normalized sentences */
    private static function sentences(string $text): array
    {
        $text = mb_strtolower(html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8'));
        $parts = preg_split('/[.!?…\n]+/u', $text) ?: [];
        $out = [];
        foreach ($parts as $part) {
            $part = trim(preg_replace('/[^\p{L}\p{N}]+/u', ' ', $part) ?? '');
            if (mb_strlen($part) >= self::MIN_SENTENCE) {
                $out[$part] = true;
            }
        }
        // the whole text also counts (catches identical short reviews)
        $whole = trim(preg_replace('/[^\p{L}\p{N}]+/u', ' ', $text) ?? '');
        if ($whole !== '') {
            $out[$whole] = true;
        }

        return array_keys($out);
    }

    /**
     * Builds the .xlsx file and returns its temp path.
     */
    public static function build(Collection $reviews): string
    {
        $dups = self::duplicates($reviews);

        $header = ['ID', 'Санаторий', 'Имя', 'Страна', 'Город', 'Язык', 'Дата', 'Месяц отдыха', 'Оценка', 'Опубликован', 'Подтверждён', 'Текст (оригинал)', 'Перевод (рус.)', 'Повтор текста', 'Повторяется в отзывах (ID)'];
        $rows = [];
        foreach ($reviews as $r) {
            $dup = $dups[$r->id] ?? null;
            $rows[] = [
                'dup' => (bool) $dup,
                'cells' => [
                    (int) $r->id,
                    (string) ($r->service?->translation?->title ?? $r->service?->title ?? ''),
                    $r->displayName(),
                    (string) ($r->displayCountry() ?? ''),
                    (string) ($r->city ?? ''),
                    (string) ($r->language ?? ''),
                    $r->created_at ? $r->created_at->format('d.m.Y') : '',
                    (string) ($r->stay_month ?? ''),
                    (float) $r->rating,
                    $r->status ? 'да' : 'нет',
                    $r->is_verified ? 'да' : 'нет',
                    (string) $r->review,
                    (string) ($r->review_ru ?? ''),
                    $dup ? 'ДА' : '',
                    $dup ? implode(', ', $dup['ids']) : '',
                ],
            ];
        }

        $sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            . '<cols><col min="1" max="1" width="7" customWidth="1"/><col min="2" max="2" width="32" customWidth="1"/><col min="3" max="3" width="22" customWidth="1"/>'
            . '<col min="4" max="11" width="12" customWidth="1"/><col min="12" max="13" width="70" customWidth="1"/><col min="14" max="14" width="12" customWidth="1"/><col min="15" max="15" width="22" customWidth="1"/></cols>'
            . '<sheetData>';
        $sheet .= self::row(1, $header, 1);
        foreach ($rows as $i => $row) {
            $sheet .= self::row($i + 2, $row['cells'], $row['dup'] ? 3 : 2);
        }
        $last = count($rows) + 1;
        $sheet .= '</sheetData><autoFilter ref="A1:O' . $last . '"/></worksheet>';

        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="4"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFE2E8F0"/><bgColor indexed="64"/></patternFill></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFFFF3B0"/><bgColor indexed="64"/></patternFill></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="4"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/>'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf>'
            . '<xf numFmtId="0" fontId="0" fillId="3" borderId="0" xfId="0" applyFill="1" applyAlignment="1"><alignment vertical="top" wrapText="1"/></xf></cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';

        $path = tempnam(sys_get_temp_dir(), 'reviews') . '.xlsx';
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Отзывы" sheetId="1" r:id="rId1"/></sheets><definedNames><definedName name="_xlnm._FilterDatabase" localSheetId="0" hidden="1">\'Отзывы\'!$A$1:$O$' . $last . '</definedName></definedNames></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>');
        $zip->addFromString('xl/styles.xml', $styles);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheet);
        $zip->close();

        return $path;
    }

    private static function row(int $r, array $cells, int $style): string
    {
        $xml = '<row r="' . $r . '">';
        foreach (array_values($cells) as $c => $value) {
            $ref = self::col($c) . $r;
            if (is_int($value) || is_float($value)) {
                $xml .= '<c r="' . $ref . '" s="' . $style . '"><v>' . $value . '</v></c>';
            } else {
                $value = preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', (string) $value) ?? '';
                $xml .= '<c r="' . $ref . '" s="' . $style . '" t="inlineStr"><is><t xml:space="preserve">'
                    . htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</t></is></c>';
            }
        }

        return $xml . '</row>';
    }

    private static function col(int $i): string
    {
        $s = '';
        for ($i++; $i > 0; $i = intdiv($i - 1, 26)) {
            $s = chr(65 + ($i - 1) % 26) . $s;
        }

        return $s;
    }
}
