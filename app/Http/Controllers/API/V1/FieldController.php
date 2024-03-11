<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\FieldRequest;
use App\Models\Field;
use Illuminate\Support\Facades\DB;

class FieldController extends Controller
{
    public function index(FieldRequest $request)
    {
        $fieldGroupId = $request->field_group_id;

        $fields = Field::query()
            ->join('field_groups', 'fields.field_group_id', '=', 'field_groups.id')
            ->select([
                'field_groups.name as field_group_name',
                'fields.name',
                'fields.type',
                DB::raw('CASE
                        WHEN fields.type = \'select\' THEN
                            (SELECT JSON_ARRAYAGG(value)
                            FROM (
                                SELECT DISTINCT text_value AS value
                                FROM plant_fields
                                WHERE field_id = fields.id
                                AND text_value IS NOT NULL
                                ORDER BY text_value
                            ))
                        ELSE NULL
                    END as options')
            ])
            ->when($fieldGroupId, function ($query, $fieldGroupId) {
                return $query->where('fields.field_group_id', $fieldGroupId);
            })
            ->orderBy('fields.id')
            ->get()
            ->map(function ($field) {
                if ($field->options) {
                    $field->options = json_decode($field->options);
                }

                return $field;
            });

        return response()->json([
            'status' => 200,
            'data' => $fields
        ], 200);
    }
}
