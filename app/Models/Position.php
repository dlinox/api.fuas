<?php

namespace App\Models;

use App\Traits\HasDataTable;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory, HasDataTable, HasStatus;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $searchColumns = ['name', 'description',];
}
