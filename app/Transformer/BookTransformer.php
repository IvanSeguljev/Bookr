<?php

namespace app\Transformer;

use App\Book;
use League\Fractal\TransformerAbstract;

class BookTransformer extends TransformerAbstract
{
    public function transform(Book $book)
    {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author->name,
            'description' => $book->description,
            'created' => $book->created_at->toIso8601String(),
            'updated' => $book->updated_at->toIso8601String()
        ];
    }
}