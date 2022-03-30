<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Comment;

class EnsureCommentsAreThreeLevelsDeepOnly
{
    private const LIMIT = 3;

    public function handle(Request $request, Closure $next)
    {
        if (isset($request->comment_id)) {

            $comment = Comment::with('parent')->findOrFail($request->comment_id);

            if (!$this->validateParentCount($comment)) {
                abort(403, 'Comments must only have 3 layers');
            }
        }

        return $next($request);
    }

    /**
     * It checks if the comment you want to comment to has less than 3 layers
     * @param mixed $comment model instance
     * @return bool whether the comment has exceeded 3 layers
     */
    private function validateParentCount($comment)
    {
        $commentLimit = 0;

        $parent = $comment->parent;

        if (!isset($parent)) {
            return true;
        }

        while ($commentLimit < self::LIMIT) {

            $commentLimit++;

            $parent = $parent->parent;

            if (!isset($parent)) {
                break;
            }
        }

        if ($commentLimit === self::LIMIT) {
            return false;
        }

        return true;
    }
}
