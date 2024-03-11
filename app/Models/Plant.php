<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = ['image', 'name', 'common_name'];

    public function fields()
    {
        return $this->belongsToMany(Field::class, 'plant_fields')
            ->withPivot('text_value', 'number_value');
    }
}
