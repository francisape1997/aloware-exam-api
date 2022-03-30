<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Scopes\CommentScopes;

class Comment extends Model
{
    use HasFactory, CommentScopes;

    protected $fillable =
    [
        'post_id',
        'title',
        'description',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo($this, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany($this, 'parent_id', 'id')->with('children');
    }
}
