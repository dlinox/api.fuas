<?php

namespace App\Models;

use App\Traits\HasDataTable;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory, HasDataTable, HasStatus;



    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'paternal_last_name',
        'maternal_last_name',
        'birth_date',
        'gender',
        'phone_number',
        'email',
        'status',
        'position_id',
        'office_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $searchColumns = ['name', 'paternal_last_name', 'maternal_last_name', 'document_number', 'phone_number', 'email',];


    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->paternal_last_name . ' ' . $this->maternal_last_name;
    }

    public function getGenderAttribute($value)
    {
        return $value == 'M' ? 'Masculino' : 'Femenino';
    }

    // public function setGenderAttribute($value)
    // {
    //     $this->attributes['gender'] = $value == 'Masculino' ? 'M' : 'F';
    // }

}
