<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantField extends Model
{
    use HasFactory;

    protected $fillable = ['plant_id', 'field_id', 'text_value', 'number_value'];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
