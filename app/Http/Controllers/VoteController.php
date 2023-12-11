<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Models\Vote;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoteRequest $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vote $vote)
    {
        //
    }

    public function vote(Request $request)
    {
        $postId = $request->get('postId');
        $post = Post::findOrFail($postId);
        $userId = Auth::check() ? Auth::id() : $this->getAnonUserId($request);
        
        if ($post->votes()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'You have already voted for this post.'], 403);
        }

        $post->upVote($userId);

        return response()->json(['message' => 'Registered vote.']);
    }

    protected function getAnonUserId(Request $request)
    {
        return md5($request->ip() . $request->header('User-Agent'));
    }
}
