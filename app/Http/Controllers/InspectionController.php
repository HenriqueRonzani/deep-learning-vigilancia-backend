<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InspectionController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'string|required',
            'type' => 'string|required',
            'status' => 'string|required',
            'address' => 'string|required',
            'dengue_breeding_site_spotted' => 'boolean|required',
        ]);

        $inspection = Inspection::query()->create($data);

        return response()->json($inspection, Response::HTTP_CREATED);
    }
}
