<?php

namespace App\Http\Requests\Inspections;

use Illuminate\Foundation\Http\FormRequest;

class ProcessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'files' => 'required|array|min:1',
            'files.*.path' => 'required|string',
            'files.*.name' => 'required|string',
            'files.*.mime_type' => 'required|string'
        ];
    }
}
