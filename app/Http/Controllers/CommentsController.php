<?php

namespace App\Http\Controllers;

# Requests
use App\Http\Requests\StoreCommentRequest;

# Services
use App\Services\CommentService;

# Resources
use App\Http\Resources\StoreCommentResource;

class CommentsController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index()
    {
        return response($this->commentService->getComments());
    }

    public function store(StoreCommentRequest $request)
    {
        return response(new StoreCommentResource($this->commentService->storeComment($request)));
    }
}
