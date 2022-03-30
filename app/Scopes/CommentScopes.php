<?php

namespace App\Scopes;

trait CommentScopes
{
    public function scopeNestedChildren($query)
    {
        return $query->doesntHave('parent')->with('children');
    }
}
