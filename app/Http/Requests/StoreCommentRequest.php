<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return
        [
            'post_id'        => 'required|integer|exists:posts,id',
            'comment_id'     => 'nullable|integer|exists:comments,id',
            'title'          => 'required|string',
            'description'    => 'required|string',
        ];
    }
}
