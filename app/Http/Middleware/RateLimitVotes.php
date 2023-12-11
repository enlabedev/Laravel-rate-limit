<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class RateLimitVotes
{
    public function handle($request, Closure $next)
    {
        $userId = Auth::check() ? Auth::id() : $this->getAnonUserId($request);
        $postId = $request->get('postId');

        if (!$postId) {
            abort(400, 'Post ID is required.');
        }

        $key = 'votes_limit_'.$userId.'_post_'.$postId;
        $decayMinutes = 60 * 24;

        if (Cache::has($key)) {
            abort(429, 'You have already voted on this post.');
        }

        Cache::put($key, true, $decayMinutes);

        return $next($request);
    }

    protected function getAnonUserId($request)
    {
        return md5($request->ip() . $request->header('User-Agent'));
    }
}