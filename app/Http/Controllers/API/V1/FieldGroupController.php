<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\FieldGroup;
use Illuminate\Http\Request;

class FieldGroupController extends Controller
{
    public function index()
    {
        try {
            $fieldGroups = FieldGroup::query()
                ->select([
                    'id',
                    'name'
                ])
                ->orderBy('id')
                ->get();

            return response()->json([
                'status' => 200,
                'data' => $fieldGroups
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
