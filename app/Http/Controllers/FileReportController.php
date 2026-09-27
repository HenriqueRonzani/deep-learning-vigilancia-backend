<?php

namespace App\Http\Controllers;

use App\Models\FileReport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class FileReportController extends Controller
{
    public function createBatch(Request $request)
    {
        $data = $request->validate([
            'reports' => 'array|required',
            'reports.*.file_id' => 'int|required',
            'reports.*.irregularity' => ['required', 'string', Rule::in(['open_water_tank', 'abandoned_pool'])],
            'reports.*.agent_report' => 'required|string',
        ]);

        $toInsert = array_map(function ($item) {
            return [
                ...$item,
                'status' => 'created',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }, $data['reports']);

        $created = FileReport::insert($toInsert);

        return response()->json($created, Response::HTTP_CREATED);
    }

    public function feedback(int $id, Request $request)
    {
        $data = $request->validate([
            'feedback' => [
                'string',
                Rule::in(['correct', 'incorrect'])
            ]
        ]);

        $report = FileReport::findOrFail($id);
        $report->update([
            'user_feedback' => $data['feedback']
        ]);

        return response()->json($report, Response::HTTP_OK);
    }
}
