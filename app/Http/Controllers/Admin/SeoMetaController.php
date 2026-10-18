<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class SeoMetaController extends Controller
{
    private const ROBOTS = ['index,follow', 'noindex,nofollow', 'index,nofollow'];

    public function __construct()
    {
        // The server does not run migrations automatically: create the table on first use
        // so the SEO section never fails with "Table seo_meta doesn't exist".
        if (!Schema::hasTable('seo_meta')) {
            Schema::create('seo_meta', function ($table) {
                $table->id();
                $table->string('page_key')->unique();
                $table->string('seo_title')->nullable();
                $table->string('seo_description', 500)->nullable();
                $table->string('seo_keywords')->nullable();
                $table->string('og_title')->nullable();
                $table->string('og_description', 500)->nullable();
                $table->string('og_image')->nullable();
                $table->string('canonical_url')->nullable();
                $table->enum('robots', ['index,follow', 'noindex,nofollow', 'index,nofollow'])->default('index,follow');
                $table->timestamps();
            });
        }
    }

    public function index()
    {
        $seoItems = SeoMeta::orderBy('page_key')->get();

        return view('admin.seo.index', compact('seoItems'));
    }

    public function create()
    {
        return view('admin.seo.form', ['seo' => new SeoMeta(['robots' => 'index,follow']), 'robots' => self::ROBOTS]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            ['page_key' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9._\-]+$/', 'unique:seo_meta,page_key']]
            + $this->rules()
        );

        $data = $this->handleImage($request, $data);
        SeoMeta::create($data);

        return redirect()->route('admin.seo.index')->with(['message' => __('translate.Created successfully'), 'alert-type' => 'success']);
    }

    public function edit($id)
    {
        $seo = SeoMeta::findOrFail($id);

        return view('admin.seo.form', ['seo' => $seo, 'robots' => self::ROBOTS]);
    }

    public function update(Request $request, $id)
    {
        $seo = SeoMeta::findOrFail($id);
        $data = $this->handleImage($request, $request->validate($this->rules()));
        // page_key is never changed after creation
        $seo->update($data);

        return redirect()->route('admin.seo.index')->with(['message' => __('translate.Updated successfully'), 'alert-type' => 'success']);
    }

    public function destroy($id)
    {
        SeoMeta::findOrFail($id)->delete();

        return redirect()->route('admin.seo.index')->with(['message' => __('translate.Deleted successfully'), 'alert-type' => 'success']);
    }

    private function rules(): array
    {
        return [
            'seo_title'       => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords'    => 'nullable|string|max:255',
            'og_title'        => 'nullable|string|max:255',
            'og_description'  => 'nullable|string|max:500',
            'og_image'        => 'nullable|string|max:255',
            'og_image_file'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'canonical_url'   => 'nullable|url|max:255',
            'robots'          => ['required', Rule::in(self::ROBOTS)],
        ];
    }

    private function handleImage(Request $request, array $data): array
    {
        if ($request->hasFile('og_image_file')) {
            $file = $request->file('og_image_file');
            $name = 'og-' . time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/website-images'), $name);
            $data['og_image'] = 'uploads/website-images/' . $name;
        }
        unset($data['og_image_file']);

        return $data;
    }
}
