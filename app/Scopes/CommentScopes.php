<?php

namespace App\Scopes;

trait CommentScopes
{
    public function scopeNestedChildren($query)
    {
        return $query->doesntHave('parent')->with('children');
    }

    public function scopeOrderByLatestCreated($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }
}
