<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problema extends Model
{
    protected $table = 'problema';
    protected $primaryKey = 'problema_id';
    public $timestamps = false;

    protected $attributes = [
        'status_id' => 1,
    ];

    protected $fillable = [
        'problema_nome',
        'departamento_id',
    ];

    // Relações
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'departamento_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'status_id');
    }
}
