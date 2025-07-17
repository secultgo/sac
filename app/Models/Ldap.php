<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ldap extends Model
{
    protected $table = 'ldap';
    protected $primaryKey = 'ldap_id';
    public $timestamps = false;

    protected $fillable = [
        'ldap_id',
        'ldap_server',
        'ldap_user',
        'ldap_pass',
        'ldap_tree',
        'ldap_filter',
    ];
}
