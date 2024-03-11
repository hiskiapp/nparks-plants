<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\PlantRequest;
use App\Models\Plant;
use App\Models\PlantField;

class PlantController extends Controller
{
    public function index(PlantRequest $request)
    {
        $data = $request->json()->all();
        $search = @$data['search'];
        $page = @$data['page'];
        $filters = @$data['filters'];
        $sorts = @$data['sorts'];

        $plants = PlantField::query()
            ->join('plants', 'plant_fields.plant_id', '=', 'plants.id')
            ->join('fields', 'plant_fields.field_id', '=', 'fields.id')
            ->select([
                'plants.id',
                'plants.image',
                'plants.name',
                'plants.common_name',
            ])
            ->when($search, function ($query, $search) {
                $query->where('plants.name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('plants.common_name', 'ILIKE', '%' . $search . '%');
            })
            ->when($filters, function ($query, $filters) {
                foreach ($filters as $index => $filter) {
                    $method = $index === 0 ? 'where' : 'orWhere';

                    if (isset($filter['text'])) {
                        if (isset($filter['text']['contains'])) {
                            $query->{$method}(function ($query) use ($filter) {
                                $query->where('fields.name', $filter['field'])
                                    ->where('plant_fields.text_value', 'ILIKE', '%' . $filter['text']['contains'] . '%');
                            });
                        }
                    }

                    if (isset($filter['number'])) {
                        if (isset($filter['number']['equals'])) {
                            $query->{$method}(function ($query) use ($filter) {
                                $query->where('fields.name', $filter['field'])
                                    ->where('plant_fields.number_value', $filter['number']['equals']);
                            });
                        } elseif (isset($filter['number']['greater_than'])) {
                            $query->{$method}(function ($query) use ($filter) {
                                $query->where('fields.name', $filter['field'])
                                    ->where('plant_fields.number_value', '>', $filter['number']['greater_than']);
                            });
                        } elseif (isset($filter['number']['less_than'])) {
                            $query->{$method}(function ($query) use ($filter) {
                                $query->where('fields.name', $filter['field'])
                                    ->where('plant_fields.number_value', '<', $filter['number']['less_than']);
                            });
                        } elseif (isset($filter['number']['greater_than_or_equal_to'])) {
                            $query->{$method}(function ($query) use ($filter) {
                                $query->where('fields.name', $filter['field'])
                                    ->where('plant_fields.number_value', '>=', $filter['number']['greater_than_or_equal_to']);
                            });
                        } elseif (isset($filter['number']['less_than_or_equal_to'])) {
                            $query->{$method}(function ($query) use ($filter) {
                                $query->where('fields.name', $filter['field'])
                                    ->where('plant_fields.number_value', '<=', $filter['number']['less_than_or_equal_to']);
                            });
                        }
                    }

                    if (isset($filter['select'])) {
                        if (isset($filter['select']['equals'])) {
                            $query->{$method}(function ($query) use ($filter) {
                                $query->where('fields.name', $filter['field'])
                                    ->where('plant_fields.text_value', $filter['select']['equals']);
                            });
                        }
                    }
                }
            })
            ->when($sorts, function ($query, $sorts) {
                foreach ($sorts as $sort) {
                    $query->orderBy($sort['field'], $sort['direction']);
                }
            })
            ->groupBy('plants.id')
            ->paginate(10, ['*'], 'page', $page);

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
