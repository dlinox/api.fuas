<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $searchColumns = ['name',];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
