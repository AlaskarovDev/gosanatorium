<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const URL = '/diseases';

    /**
     * Adds "По заболеванию" under the existing "Лечение" item of the menu.
     * Does nothing if such a menu item does not exist (add it in the admin menu builder then).
     */
    public function up(): void
    {
        if (!Schema::hasTable('menu_items')) {
            return;
        }

        $parent = DB::table('menu_items')
            ->where('parent_id', 0)
            ->whereIn('title', ['Лечение', 'Müalicə', 'Treatment'])
            ->orderBy('id')
            ->first();

        if (!$parent || DB::table('menu_items')->where('menu_id', $parent->menu_id)->where('url', self::URL)->exists()) {
            return;
        }

        DB::table('menu_items')->insert([
            'menu_id' => $parent->menu_id,
            'title' => 'По заболеванию',
            'url' => self::URL,
            'target' => '_self',
            'parent_id' => $parent->id,
            'order' => (int) DB::table('menu_items')->where('parent_id', $parent->id)->max('order') + 1,
            'type' => 'custom',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        if (Schema::hasTable('menu_items')) {
            DB::table('menu_items')->where('url', self::URL)->where('type', 'custom')->delete();
        }
    }
};
