<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Traits\RespondsWithHttpStatus;

class PostController extends Controller
{
    use RespondsWithHttpStatus;

    public function index()
    {
        $posts = Post::with('category')->paginate(20);
        if (count($posts) > 0) {
            return $this->success('All Posts', $posts, 200);
        }
        return $this->failure('No Posts found', 200);
    }

    public function store(CreatePostRequest $request)
    {
        $postCreated = Post::create($request->only(['title', 'description', 'category_id']));
        if ($postCreated) {
            return $this->success('Post created successfully', ['data' => $postCreated], 201);
        }
        return $this->failure('Create new post failed', 417);
    }

    public function show(Post $post)
    {
        if ($post) {
            return $this->success('post', $post->load('category'), 200);
        }
        return $this->failure('No post found', 417);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $postUpdated = $post->update($request->only(['title', 'description', 'category_id']));
        if ($postUpdated) {
            return $this->success('Post updated successfully', $post->load('category'), 200);
        }
        return $this->failure('Update post failed', 417);
    }

    public function destroy(Post $post)
    {
        $postDeleted = $post->delete();
        if ($postDeleted) {
            return $this->success('Post updated successfully', $post, 200);
        }
        return $this->failure('delete post failed', 417);
    }
}
