<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConsultantController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.consultant.edit');
    }

    public function edit()
    {
        $consultant = Consultant::first() ?? new Consultant([
            'button_text' => 'Получить консультацию →',
            'button_url' => '/ru/custom-page/consultant',
            'is_active' => true,
        ]);

        return view('admin.consultant.edit', compact('consultant'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'button_text' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $consultant = Consultant::first() ?? new Consultant();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = 'consultant-' . time() . '-' . Str::random(6) . '.' . $file->extension();
            $file->move(public_path('images/consultants'), $name);
            $data['photo'] = $name;
        } else {
            unset($data['photo']);
        }

        $data['button_text'] = $data['button_text'] ?: 'Получить консультацию →';
        $data['button_url'] = $data['button_url'] ?: '/ru/custom-page/consultant';
        $data['is_active'] = $request->boolean('is_active');

        $consultant->fill($data)->save();

        return redirect()->route('admin.consultant.edit')->with(['message' => __('translate.Updated successfully'), 'alert-type' => 'success']);
    }
}
