<?php

namespace Tests\Unit;

use Modules\TourBooking\App\Services\DescriptionSections;
use PHPUnit\Framework\TestCase;

/**
 * Sanatorium page: long description → intro + collapsible sections.
 */
class DescriptionSectionsTest extends TestCase
{
    private function para(int $n = 1): string
    {
        return str_repeat('<p>' . str_repeat('Текст о санатории и лечении. ', 12) . '</p>', $n);
    }

    public function test_short_text_is_not_split(): void
    {
        $this->assertNull(DescriptionSections::split('<p>Коротко.</p>'));
        $this->assertNull(DescriptionSections::split(null));
    }

    public function test_splits_by_h3_and_keeps_all_text(): void
    {
        $html = '<h3>Санаторий — коротко</h3>' . $this->para(2)
            . '<hr><h3>Почему именно мы</h3>' . $this->para()
            . '<h3>Питание</h3>' . $this->para()
            . '<h3>Номера</h3>' . $this->para();
        $r = DescriptionSections::split($html);

        $this->assertNotNull($r);
        $this->assertStringContainsString('Санаторий — коротко', $r['intro']);
        $this->assertSame(['Почему именно мы', 'Питание', 'Номера'], array_column($r['sections'], 'title'));
        $this->assertSame('h3', $r['sections'][0]['heading']);
        $this->assertStringNotContainsString('<h3', $r['sections'][1]['body']);

        $all = $r['intro'] . implode('', array_map(fn ($s) => $s['title'] . $s['body'], $r['sections']));
        $norm = fn ($s) => preg_replace('/\s+/u', '', strip_tags($s));
        $this->assertSame($norm($html), $norm($all));
    }

    public function test_bold_caps_paragraph_after_hr_is_a_title(): void
    {
        $html = $this->para(2)
            . '<hr><p><strong>ПРИ КАКИХ ЗАБОЛЕВАНИЯХ</strong></p>' . $this->para()
            . '<p><strong>Основные профили:</strong></p>' . $this->para()
            . '<hr><p><strong>ПИТАНИЕ</strong></p>' . $this->para();
        $r = DescriptionSections::split($html);

        $this->assertNotNull($r);
        $this->assertSame(['ПРИ КАКИХ ЗАБОЛЕВАНИЯХ', 'ПИТАНИЕ'], array_column($r['sections'], 'title'));
        $this->assertNull($r['sections'][0]['heading']);
        // a mixed-case bold line inside a section stays in its body
        $this->assertStringContainsString('Основные профили:', $r['sections'][0]['body']);
    }

    public function test_price_table_alone_is_not_an_intro(): void
    {
        $html = '<h3>Цены</h3><table><tr><td>' . str_repeat('Люкс 100$ ', 80) . '</td></tr></table>'
            . '<h3>О санатории</h3>' . $this->para(2)
            . '<h3>Лечение</h3>' . $this->para()
            . '<h3>Питание</h3>' . $this->para();
        $r = DescriptionSections::split($html);

        $this->assertNotNull($r);
        $this->assertStringContainsString('<table>', $r['intro']);
        $this->assertStringContainsString('О санатории', $r['intro']);
        $this->assertSame(['Лечение', 'Питание'], array_column($r['sections'], 'title'));
    }

    public function test_text_without_structure_returns_null(): void
    {
        $this->assertNull(DescriptionSections::split($this->para(6)));
    }
}
