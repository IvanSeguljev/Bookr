<?php

namespace App\Transformer;

use App\Author;
use League\Fractal\TransformerAbstract;

class AuthorTransformer extends TransformerAbstract
{
    public function transform(Author $author)
    {
        return [
            'id'=>$author->id,
            'name'=>$author->name,
            'biography'=>$author->biography,
            'gender'=>$author->gender,
            'created'=>$author->created_at->toIso8601String(),
            'updated'=>$author->updated_at->toIso8601String()
        ];
    }
}
