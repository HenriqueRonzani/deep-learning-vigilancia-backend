<?php

namespace App\Http\Requests\Inspections;

use Illuminate\Foundation\Http\FormRequest;

class BatchUploadUrlsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'files' => 'required|array',
            'files.*.ref_id' => 'required|string',
            'files.*.filename' => 'required|string'
        ];
    }
}
