<?php

namespace Modules\Page\App\Http\Controllers;

use Image, File, Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Page\App\Models\CustomPage;
use Modules\Language\App\Models\Language;
use Modules\Page\App\Models\CustomPageTranslation;
use Modules\Page\App\Http\Requests\CustomPageRequest;
use Modules\Page\App\Models\CustomPageVideo;

class CustomPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $custom_pages = CustomPage::latest()->get();

        return view('page::custom_page.index', [
            'custom_pages' => $custom_pages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page::custom_page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomPageRequest $request)
    {
        $custom_page = new CustomPage();
        $custom_page->slug = $request->slug;
        $custom_page->status = $request->status ? 1 : 0;
        $custom_page->show_consultation_form = $request->show_consultation_form ? 1 : 0;
        $custom_page->show_video_banner = $request->show_video_banner ? 1 : 0;
        $custom_page->video_url = $request->video_url;
        $custom_page->doctor_name = $request->doctor_name;
        $custom_page->feature1_icon = $request->feature1_icon ?? 'fas fa-clock';
        $custom_page->feature2_icon = $request->feature2_icon ?? 'fas fa-user-tie';
        $custom_page->feature3_icon = $request->feature3_icon ?? 'fas fa-car';
        $custom_page->feature4_icon = $request->feature4_icon ?? 'fas fa-thumbs-up';
        $custom_page->save();

        // Banner image upload
        if($request->hasFile('banner_image')){
            $image_name = 'banner-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->banner_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $custom_page->banner_image = $image_name;
            $custom_page->save();
        }

        // Doctor image upload
        if($request->hasFile('doctor_image')){
            $image_name = 'doctor-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->doctor_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $custom_page->doctor_image = $image_name;
            $custom_page->save();
        }

        // Video thumbnail upload
        if($request->hasFile('video_thumbnail')){
            $image_name = 'thumb-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->video_thumbnail)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $custom_page->video_thumbnail = $image_name;
            $custom_page->save();
        }

        $languages = Language::all();

        foreach($languages as $language){
            $custom_trans = new CustomPageTranslation();
            $custom_trans->lang_code = $language->lang_code;
            $custom_trans->custom_page_id = $custom_page->id;
            $custom_trans->page_name = $request->page_name;
            $custom_trans->description = $request->description;
            $custom_trans->banner_title = $request->banner_title;
            $custom_trans->banner_description = $request->banner_description;
            $custom_trans->form_title = $request->form_title;
            $custom_trans->form_description = $request->form_description;
            $custom_trans->feature1_title = $request->feature1_title;
            $custom_trans->feature1_description = $request->feature1_description;
            $custom_trans->feature2_title = $request->feature2_title;
            $custom_trans->feature2_description = $request->feature2_description;
            $custom_trans->feature3_title = $request->feature3_title;
            $custom_trans->feature3_description = $request->feature3_description;
            $custom_trans->feature4_title = $request->feature4_title;
            $custom_trans->feature4_description = $request->feature4_description;
            $custom_trans->save();
        }

        // Default dildən digər dillərə avtomatik tərcümə
        $this->autoTranslateCustomPage($custom_page, $request, admin_lang());

        $notify_message = trans('translate.Created successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');
        return redirect()->route('admin.custom-page.edit', ['custom_page' => $custom_page->id, 'lang_code' => admin_lang()])->with($notify_message);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
$custom_page = CustomPage::with('videos')->findOrFail($id);

        $translate = CustomPageTranslation::firstOrNew(['custom_page_id' => $custom_page->id, 'lang_code' => $request->lang_code]);

        return view('page::custom_page.edit', [
            'custom_page' => $custom_page,
            'translate' => $translate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomPageRequest $request, $id)
    {
        $custom_page = CustomPage::findOrFail($id);

        if($request->lang_code == admin_lang()){
            $custom_page->slug = $request->slug;
            $custom_page->status = $request->status ? 1 : 0;
            $custom_page->show_consultation_form = $request->show_consultation_form ? 1 : 0;
            $custom_page->show_video_banner = $request->show_video_banner ? 1 : 0;
            $custom_page->video_url = $request->video_url;
            $custom_page->doctor_name = $request->doctor_name;
            $custom_page->feature1_icon = $request->feature1_icon ?? $custom_page->feature1_icon;
            $custom_page->feature2_icon = $request->feature2_icon ?? $custom_page->feature2_icon;
            $custom_page->feature3_icon = $request->feature3_icon ?? $custom_page->feature3_icon;
            $custom_page->feature4_icon = $request->feature4_icon ?? $custom_page->feature4_icon;
            $custom_page->save();

            // Banner image upload
            if($request->hasFile('banner_image')){
                $old_image = $custom_page->banner_image;
                $image_name = 'banner-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
                $image_name = 'uploads/custom-images/'.$image_name;
                Image::make($request->banner_image)
                    ->encode('webp', 80)
                    ->save(public_path().'/'.$image_name);
                $custom_page->banner_image = $image_name;
                $custom_page->save();

                if($old_image && File::exists(public_path().'/'.$old_image)){
                    unlink(public_path().'/'.$old_image);
                }
            }

            // Doctor image upload
            if($request->hasFile('doctor_image')){
                $old_image = $custom_page->doctor_image;
                $image_name = 'doctor-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
                $image_name = 'uploads/custom-images/'.$image_name;
                Image::make($request->doctor_image)
                    ->encode('webp', 80)
                    ->save(public_path().'/'.$image_name);
                $custom_page->doctor_image = $image_name;
                $custom_page->save();

                if($old_image && File::exists(public_path().'/'.$old_image)){
                    unlink(public_path().'/'.$old_image);
                }
            }

            // Video thumbnail upload
            if($request->hasFile('video_thumbnail')){
                $old_image = $custom_page->video_thumbnail;
                $image_name = 'thumb-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
                $image_name = 'uploads/custom-images/'.$image_name;
                Image::make($request->video_thumbnail)
                    ->encode('webp', 80)
                    ->save(public_path().'/'.$image_name);
                $custom_page->video_thumbnail = $image_name;
                $custom_page->save();

                if($old_image && File::exists(public_path().'/'.$old_image)){
                    unlink(public_path().'/'.$old_image);
                }
            }
        }

        $translate = CustomPageTranslation::firstOrNew(['custom_page_id' => $custom_page->id, 'lang_code' => $request->lang_code]);
        $translate->page_name = $request->page_name;
        $translate->description = $request->description;
        $translate->banner_title = $request->banner_title;
        $translate->banner_description = $request->banner_description;
        $translate->form_title = $request->form_title;
        $translate->form_description = $request->form_description;
        $translate->feature1_title = $request->feature1_title;
        $translate->feature1_description = $request->feature1_description;
        $translate->feature2_title = $request->feature2_title;
        $translate->feature2_description = $request->feature2_description;
        $translate->feature3_title = $request->feature3_title;
        $translate->feature3_description = $request->feature3_description;
        $translate->feature4_title = $request->feature4_title;
        $translate->feature4_description = $request->feature4_description;
        $translate->save();

        // Yalnız default dildə redaktə olunanda digər dilləri avtomatik tərcümə et
        if ($request->lang_code == admin_lang()) {
            $this->autoTranslateCustomPage($custom_page, $request, $request->lang_code);
        }

        $notify_message = trans('translate.Update successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');
        return redirect()->back()->with($notify_message);
    }




/**
 * Custom page mətnlərini default dildən digər aktiv dillərə avtomatik tərcümə edir.
 * Xəta saxlamağı pozmur (ClaudeTranslator boş massiv qaytarır).
 */
private function autoTranslateCustomPage(CustomPage $custom_page, Request $request, string $sourceLang): void
{
    if (! \App\Services\ClaudeTranslator::enabled()) {
        return;
    }

    $fields = array_filter([
        'page_name'           => $request->page_name,
        'description'         => $request->description,
        'banner_title'        => $request->banner_title,
        'banner_description'  => $request->banner_description,
        'form_title'          => $request->form_title,
        'form_description'    => $request->form_description,
        'feature1_title'      => $request->feature1_title,
        'feature1_description'=> $request->feature1_description,
        'feature2_title'      => $request->feature2_title,
        'feature2_description'=> $request->feature2_description,
        'feature3_title'      => $request->feature3_title,
        'feature3_description'=> $request->feature3_description,
        'feature4_title'      => $request->feature4_title,
        'feature4_description'=> $request->feature4_description,
    ], fn ($v) => is_string($v) && trim($v) !== '');

    if (empty($fields)) {
        return;
    }

    $targets = Language::where('status', 1)
        ->pluck('lang_code')
        ->reject(fn ($l) => $l === $sourceLang)
        ->values()
        ->all();

    $translations = \App\Services\ClaudeTranslator::translate($fields, $sourceLang, $targets);

    foreach ($translations as $lang => $data) {
        if (! empty($data)) {
            CustomPageTranslation::updateOrCreate(
                ['custom_page_id' => $custom_page->id, 'lang_code' => $lang],
                $data
            );
        }
    }
}

public function storeVideo(Request $request)
    {
        try {
            $video = new CustomPageVideo();
            $video->custom_page_id = $request->custom_page_id;
            $video->title = $request->title;
            $video->video_url = $request->video_url;
            $video->status = 1;
            $video->sort_order = CustomPageVideo::where('custom_page_id', $request->custom_page_id)->count();

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = 'video-thumb-' . time() . '-' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/custom-images'), $filename);
                $video->thumbnail = 'uploads/custom-images/' . $filename;
            }

            $video->save();

            return response()->json(['success' => true, 'message' => 'Video added']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyVideo($id)
    {
        try {
            $video = CustomPageVideo::findOrFail($id);
            if ($video->thumbnail && file_exists(public_path($video->thumbnail))) {
                @unlink(public_path($video->thumbnail));
            }
            $video->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateVideoStatus(Request $request, $id)
    {
        try {
            $video = CustomPageVideo::findOrFail($id);
            $video->status = $request->input('status', 0);
            $video->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }






    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $custom_page = CustomPage::findOrFail($id);
        
        // Delete images
        if($custom_page->banner_image && File::exists(public_path().'/'.$custom_page->banner_image)){
            unlink(public_path().'/'.$custom_page->banner_image);
        }
        if($custom_page->doctor_image && File::exists(public_path().'/'.$custom_page->doctor_image)){
            unlink(public_path().'/'.$custom_page->doctor_image);
        }
        if($custom_page->video_thumbnail && File::exists(public_path().'/'.$custom_page->video_thumbnail)){
            unlink(public_path().'/'.$custom_page->video_thumbnail);
        }
        
        $custom_page->delete();

        CustomPageTranslation::where('custom_page_id', $id)->delete();

        $notify_message = trans('translate.Deleted successfully');
        $notify_message = array('message' => $notify_message, 'alert-type' => 'success');
        return redirect()->back()->with($notify_message);
    }


    public function setup_language($lang_code){
        $page_translates = CustomPageTranslation::where('lang_code' , admin_lang())->get();

        foreach($page_translates as $page_translate){
            $new_trans = new CustomPageTranslation();
            $new_trans->lang_code = $lang_code;
            $new_trans->custom_page_id = $page_translate->custom_page_id;
            $new_trans->page_name = $page_translate->page_name;
            $new_trans->description = $page_translate->description;
            $new_trans->banner_title = $page_translate->banner_title;
            $new_trans->banner_description = $page_translate->banner_description;
            $new_trans->form_title = $page_translate->form_title;
            $new_trans->form_description = $page_translate->form_description;
            $new_trans->feature1_title = $page_translate->feature1_title;
            $new_trans->feature1_description = $page_translate->feature1_description;
            $new_trans->feature2_title = $page_translate->feature2_title;
            $new_trans->feature2_description = $page_translate->feature2_description;
            $new_trans->feature3_title = $page_translate->feature3_title;
            $new_trans->feature3_description = $page_translate->feature3_description;
            $new_trans->feature4_title = $page_translate->feature4_title;
            $new_trans->feature4_description = $page_translate->feature4_description;
            $new_trans->save();
        }
    }
}