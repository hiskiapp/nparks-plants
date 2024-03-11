<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = ['field_group_id', 'name', 'type'];

    public function fieldGroup()
    {
        return $this->belongsTo(FieldGroup::class);
    }
}
