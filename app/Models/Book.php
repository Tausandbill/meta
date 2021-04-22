<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $guarded = [];

    //Alterando route model binding para utilizar ISBN en vez de id
    public function getRouteKeyName()
    {
        return 'isbn';
    }
}
