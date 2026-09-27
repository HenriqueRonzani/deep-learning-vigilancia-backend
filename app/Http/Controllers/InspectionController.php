<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inspections\BatchUploadUrlsRequest;
use App\Http\Requests\Inspections\ProcessRequest;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class InspectionController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'string|required',
            'type' => 'string|required',
            'address' => 'string|required',
            'dengue_breeding_site_spotted' => 'boolean|required',
        ]);

        $inspection = Inspection::query()->create([
            ...$data,
            'status' => 'draft',
        ]);

        return response()->json($inspection, Response::HTTP_CREATED);
    }

    public function batchUploadUrls(int $id, BatchUploadUrlsRequest $request)
    {
        $data = $request->validated();

        $responseUrls = [];

        foreach ($data['files'] as $file) {
            $extension = pathinfo($file['filename'], PATHINFO_EXTENSION);
            $s3FileName = \Str::uuid() . '.' . $extension;
            $path = "inspections/{$id}/raw/{$s3FileName}";

            $uploadUrl = \Storage::disk('s3')->temporaryUploadUrl(
                $path,
                now()->addMinutes(60)
            );

            $responseUrls[] = [
                'ref_id' => $file['ref_id'],
                'upload_url' => $uploadUrl,
                'path' => $path
            ];
        }

        return response()->json([
            'urls' => $responseUrls
        ]);
    }

    public function process(int $id, ProcessRequest $request)
    {
        $data = $request->validated();

        $inspection = Inspection::findOrFail($id);

        $files = $inspection->files()->createMany($data['files']);

        $queueData = [
            'inspection_id' => $inspection->id,
            'files' => $files->map->only(['id', 'path', 'mime_type'])->toArray()
        ];

        $dataEncoded = json_encode($queueData);

        \Queue::pushRaw($dataEncoded);

        $inspection->update(['status' => 'queued']);

        return response()->json([
            'inspection' => $inspection,
            'files' => $files
        ], Response::HTTP_OK);
    }

    public function statusPolling(int $id) {
        $status = Inspection::findOrFail($id)->status;
        return response()->json(['status' => $status], Response::HTTP_OK);
    }

    public function updateStatus(int $id, Request $request) {
        $data = $request->validate([
            'status' => ['required', Rule::in(['processing', 'completed', 'failed'])]
        ]);

        $inspection = Inspection::findOrFail($id);
        $inspection->update(['status' => $data['status']]);

        return response()->json($inspection, Response::HTTP_OK);
    }

    public function update(int $id, Request $request)
    {
        $data = $request->validate([
            'name' => 'string|required',
            'type' => 'string|required',
            'address' => 'string|required',
            'dengue_breeding_site_spotted' => 'boolean|required',
        ]);

        $inspection = Inspection::query()->findOrFail($id);

        $inspection->update($data);

        return response()->json($inspection, Response::HTTP_OK);
    }

    public function getAll()
    {
        $inspection = Inspection::query()->paginate();

        return response()->json($inspection, Response::HTTP_OK);
    }

    public function getById(int $id)
    {
        $inspection = Inspection::query()
            ->with('files', 'files.fileReports', 'requester')
            ->findOrFail($id);

        return response()->json($inspection, Response::HTTP_OK);
    }

    // Removed possibility to delete!!
}
