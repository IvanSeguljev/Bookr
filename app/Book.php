<?php

namespace App;

use Illuminate\Database\Eloquent\Model;




class Book extends Model {
    
    protected $fillable = array("title","description",'author_id');
    
    public function author()
    {
       return $this->belongsTo(Author::class);
    }
}

