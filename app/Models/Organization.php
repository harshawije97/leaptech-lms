<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasUuids;

    protected $table = 'organization';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'short_description',
        'description',
        'no_of_users',
        'no_of_admins',
    ];
}
