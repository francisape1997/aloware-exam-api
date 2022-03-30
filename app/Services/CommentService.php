<?php

namespace App\Services;

use App\Models\Comment;

class CommentService
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getComments()
    {
        return $this->comment->nestedChildren()->orderByLatestCreated()->paginate();
    }

    public function storeComment($request)
    {
        return $this->comment->create(
            [
                'post_id'     => $request->post_id,
                'parent_id'   => $request->comment_id,
                'title'       => $request->title,
                'description' => $request->description,
            ]
        );
    }
}
