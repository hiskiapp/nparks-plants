<?php

use App\Models\Plant;
use App\Models\PlantField;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('returns all plants with pagination', function () {
    PlantField::factory(15)->create();

    $response = $this->postJson(route('api.v1.plants.index'));
    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'per_page',
                'total',
            ],
            'data',
        ]);
});

it('returns plant details by id', function () {
    $plant = Plant::factory()->create();
    $plant->fields()->saveMany(PlantField::factory(3)->make());

    $response = $this->getJson(route('plants.show', ['id' => $plant->id]));

    $response->assertStatus(200)
        ->assertJson([
            'status' => 200,
            'data' => [
                'id' => $plant->id,
                'image' => $plant->image,
                'name' => $plant->name,
                'common_name' => $plant->common_name,
            ],
        ]);
});
