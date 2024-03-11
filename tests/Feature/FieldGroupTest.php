<?php

use App\Models\FieldGroup;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('returns a collection of field groups', function () {
    FieldGroup::factory(2)->create();

    $response = $this->getJson(route('api.v1.field-groups.index'));
    $response->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'id',
                    'name',
                ],
            ],
        ]);
});
