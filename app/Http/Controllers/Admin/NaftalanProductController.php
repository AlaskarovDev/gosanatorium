<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NaftalanProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NaftalanProductController extends Controller
{
    public function index()
    {
        $products = NaftalanProduct::orderBy('sort_order')->orderBy('created_at')->paginate(20);

        return view('admin.naftalan-products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.naftalan-products.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request->file('image'));
        }

        NaftalanProduct::create($data);

        return redirect()->route('admin.naftalan-products.index')
            ->with(['message' => __('translate.Created successfully'), 'alert-type' => 'success']);
    }

    public function edit(NaftalanProduct $naftalanProduct)
    {
        return view('admin.naftalan-products.edit', ['product' => $naftalanProduct]);
    }

    public function update(Request $request, NaftalanProduct $naftalanProduct)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($naftalanProduct->image);
            $data['image'] = $this->storeImage($request->file('image'));
        } else {
            unset($data['image']);
        }

        $naftalanProduct->update($data);

        return redirect()->route('admin.naftalan-products.index')
            ->with(['message' => __('translate.Updated successfully'), 'alert-type' => 'success']);
    }

    public function destroy(NaftalanProduct $naftalanProduct)
    {
        $this->deleteImage($naftalanProduct->image);
        $naftalanProduct->delete();

        return redirect()->route('admin.naftalan-products.index')
            ->with(['message' => __('translate.Deleted successfully'), 'alert-type' => 'success']);
    }

    public function toggleActive(NaftalanProduct $naftalanProduct)
    {
        $naftalanProduct->update(['is_active' => !$naftalanProduct->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $naftalanProduct->is_active,
        ]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:99999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'whatsapp_message' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }

    private function storeImage($file): string
    {
        $filename = uniqid() . '.' . $file->extension();

        return $file->storeAs('naftalan-products', $filename, 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
