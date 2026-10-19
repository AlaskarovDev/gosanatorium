<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akcii;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AkciiController extends Controller
{
    public function index()
    {
        $akcii = Akcii::orderBy('sort_order')->paginate(20);

        return view('admin.akcii.index', compact('akcii'));
    }

    public function create()
    {
        return view('admin.akcii.create', ['akcii' => new Akcii(['is_active' => true, 'sort_order' => 0, 'lang' => 'ru'])]);
    }

    public function store(Request $request)
    {
        $request->merge(['slug' => $request->filled('slug') ? $request->slug : Str::slug((string) $request->title)]);

        $data = $request->validate($this->rules());
        $data = $this->prepare($request, $data);

        Akcii::create($data);

        return redirect()->route('admin.akcii.index')->with(['message' => __('translate.Created successfully'), 'alert-type' => 'success']);
    }

    public function edit(Akcii $akcii)
    {
        return view('admin.akcii.edit', compact('akcii'));
    }

    public function update(Request $request, Akcii $akcii)
    {
        $request->merge(['slug' => $request->filled('slug') ? $request->slug : Str::slug((string) $request->title)]);

        $data = $request->validate($this->rules($akcii->id));
        $data = $this->prepare($request, $data);

        $akcii->update($data);

        return redirect()->route('admin.akcii.index')->with(['message' => __('translate.Updated successfully'), 'alert-type' => 'success']);
    }

    public function destroy(Akcii $akcii)
    {
        $akcii->delete();

        return redirect()->route('admin.akcii.index')->with(['message' => __('translate.Deleted successfully'), 'alert-type' => 'success']);
    }

    private function rules(?int $id = null): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:akcii,slug' . ($id ? ',' . $id : ''),
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'valid_until' => 'nullable|date',
            'sanatorium_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'lang' => 'nullable|string|max:10',
        ];
    }

    private function prepare(Request $request, array $data): array
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = 'akcii-' . time() . '-' . Str::random(6) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/website-images'), $name);
            $data['image'] = 'uploads/website-images/' . $name;
        } else {
            unset($data['image']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['lang'] = $data['lang'] ?? 'ru';

        return $data;
    }
}
