<?php

use App\Models\Field;
use App\Models\FieldGroup;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('returns a collection of fields', function () {
    FieldGroup::factory(3)->create();
    FieldGroup::all()->each(function ($fieldGroup) {
        $fieldGroup->fields()->saveMany(Field::factory(3)->make());
    });

    $response = $this->getJson(route('api.v1.fields.index'));
    $response->assertJsonStructure([
        'status',
        'data' => [
            '*' => [
                'field_group_name',
                'name',
                'type',
                'options'
            ]
        ]
    ]);
});
