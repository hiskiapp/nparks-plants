<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use App\Models\PlantField;

class PlantController extends Controller
{
    public function index()
    {
        // TODO: Implement filter and sort
        // Filter text: contains
        // Filter number: equals,greater_than,greater_than_or_equal_to,less_than,less_than_or_equal_to
        // Filter select: equals
        // Sort: asc,desc

        $plants = PlantField::query()
            ->join('plants', 'plant_fields.plant_id', '=', 'plants.id')
            ->select([
                'plants.id',
                'plants.image',
                'plants.name',
                'plants.common_name',
            ])
            ->groupBy('plants.ids')
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'meta' => [
                'current_page' => $plants->currentPage(),
                'from' => $plants->firstItem(),
                'last_page' => $plants->lastPage(),
                'per_page' => $plants->perPage(),
                'total' => $plants->total(),
            ],
            'data' => $plants->items(),
        ], 200);
    }

    public function show($id)
    {
        $plant = Plant::query()
            ->select([
                'plants.id',
                'plants.image',
                'plants.name',
                'plants.common_name',
            ])
            ->selectSub(function ($query) {
                $query->selectRaw("JSON_AGG(data) as fields")
                    ->from(function ($sub) {
                        $sub->select('field_groups.name as field_group_name')
                            ->selectRaw("
                                    JSON_AGG(
                                        JSON_BUILD_OBJECT(
                                            'name', fields.name,
                                            'type', fields.type,
                                            'text_value', plant_fields.text_value,
                                            'number_value', plant_fields.number_value
                                        )
                                    ) as fields")
                            ->from('field_groups')
                            ->join('fields', 'fields.field_group_id', '=', 'field_groups.id')
                            ->leftJoin('plant_fields', function ($join) {
                                $join->on('plant_fields.field_id', '=', 'fields.id')
                                    ->on('plant_fields.plant_id', '=', 'plants.id');
                            })
                            ->whereRaw('(plant_fields.text_value IS NOT NULL) OR (plant_fields.number_value IS NOT NULL)')
                            ->groupBy('field_groups.id');
                    }, 'data');
            }, 'field_groups')
            ->where('plants.id', $id)
            ->groupBy('plants.id')
            ->firstOrFail();

        if ($plant->field_groups) {
            $plant->field_groups = json_decode($plant->field_groups);
        }

        return response()->json([
            'status' => 200,
            'data' => $plant
        ], 200);
    }
}
