<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminPostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', [
            'posts' => Post::paginate(50)
        ]);
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', [
           'post' => $post
        ]);
    }

    public function update(Post $post)
    {
        $attributes = request()->validate([
            'title' => ['required'],
            'thumbnail' => ['image'],
            'slug' => ['required', Rule::unique('posts','slug')->ignore($post->id)],
            'body' => ['required'],
            'excerpt' => ['required'],
            'category_id' => ['required', Rule::exists('categories','id')],
        ]);

        if (isset($attributes['thumbnail'])) {
            $attributes['thumbnail'] = request()->file('thumbnail')->store('thumbnails');
        }

        $post->update($attributes);

        return back()->with('success', 'Updated!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Post deleted!');
    }
}
