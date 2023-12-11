<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'votes');
    }

    public function vote($user, $vote)
    {
        $this->votes()->updateOrCreate([
            'user_id' => $user,
        ], [
            'vote' => $vote,
        ]);
    }

    public function upvote($user)
    {
        $this->vote($user, true);
    }


}
