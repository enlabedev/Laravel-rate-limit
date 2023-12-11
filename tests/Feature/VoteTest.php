<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_vote_once()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/posts/vote", ['postId' => $post->id]);
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Registered vote.']);

        $this->assertDatabaseHas('votes', [
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_vote_more_than_once()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->postJson("/posts/vote", ['postId' => $post->id]);
        $response = $this->actingAs($user)->postJson("/posts/vote", ['postId' => $post->id]);

        $response->assertStatus(429)
                 ->assertJson(['message' => 'You have already voted on this post.']);

        $this->assertCount(1, $post->votes);
    }
}
