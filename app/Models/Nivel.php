<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    protected $table = 'nivel';
    protected $primaryKey = 'nivel_id';
    public $timestamps = false;

    protected $fillable = [
        'nivel_nome',
        'nivel_slug',
    ];
}
