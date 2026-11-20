<?php

namespace Modules\Blog\App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\App\Models\Blog;
use Modules\Blog\App\Models\BlogAuthor;

/** Task 20: Admin → Блог → Авторы и врачи. */
class BlogAuthorController extends Controller
{
    public function index()
    {
        $authors = BlogAuthor::orderByDesc('is_default')->orderBy('name')->get();
        $counts = Blog::selectRaw('author_id, count(*) c')->whereNotNull('author_id')->groupBy('author_id')->pluck('c', 'author_id');
        $expertCounts = Blog::selectRaw('expert_id, count(*) c')->whereNotNull('expert_id')->groupBy('expert_id')->pluck('c', 'expert_id');

        return view('blog::blog_author_list', compact('authors', 'counts', 'expertCounts'));
    }

    public function create()
    {
        return view('blog::blog_author_form', ['author' => new BlogAuthor(['status' => true])]);
    }

    public function store(Request $request)
    {
        $author = new BlogAuthor();
        $this->save($request, $author);

        return redirect()->route('admin.blog-author.index')->with(['message' => trans('translate.Created successfully'), 'alert-type' => 'success']);
    }

    public function edit($id)
    {
        return view('blog::blog_author_form', ['author' => BlogAuthor::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $this->save($request, BlogAuthor::findOrFail($id));

        return redirect()->route('admin.blog-author.index')->with(['message' => trans('translate.Updated successfully'), 'alert-type' => 'success']);
    }

    public function destroy($id)
    {
        $author = BlogAuthor::findOrFail($id);
        Blog::where('author_id', $author->id)->update(['author_id' => null]);
        Blog::where('expert_id', $author->id)->update(['expert_id' => null]);
        if ($author->photo && str_starts_with($author->photo, 'uploads/')) {
            FileUploadHelper::deleteImage($author->photo);
        }
        $author->delete();

        return redirect()->route('admin.blog-author.index')->with(['message' => trans('translate.Deleted successfully'), 'alert-type' => 'success']);
    }

    private function save(Request $request, BlogAuthor $author): void
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'role'        => 'nullable|string|max:255',
            'specialty'   => 'nullable|string|max:255',
            'bio'         => 'nullable|string|max:2000',
            'profile_url' => 'nullable|string|max:255',
            'photo'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            $old = $author->photo;
            $data['photo'] = FileUploadHelper::uploadImage($request->file('photo'), 'blog-author', 'uploads/custom-images');
            if ($old && str_starts_with($old, 'uploads/')) {
                FileUploadHelper::deleteImage($old);
            }
        } else {
            unset($data['photo']);
        }

        $author->fill($data);
        $author->is_medical = $request->boolean('is_medical');
        $author->status = $request->boolean('status');
        $author->is_default = $request->boolean('is_default');
        $author->save();

        if ($author->is_default) {
            BlogAuthor::where('id', '!=', $author->id)->update(['is_default' => false]);
        }
    }
}
