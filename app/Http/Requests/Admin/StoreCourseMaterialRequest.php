<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()
            && ($this->user()->isAdmin() || $this->user()->hasPermission('materials.create'));
    }

    public function rules(): array
    {
        $type = $this->input('type');

        $rules = [
            'module_id' => ['required', 'exists:course_modules,id'],
            'type' => ['required', 'in:video,documento,presentacion,texto,recurso'],
            'title' => ['required', 'string', 'max:300'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_downloadable' => ['nullable', 'boolean'],
        ];

        if ($type === 'video') {
            $rules['video_source'] = ['required', 'in:youtube,vimeo,upload'];
            $source = $this->input('video_source');

            if ($source === 'youtube' || $source === 'vimeo') {
                $rules['video_url'] = ['required', 'url', 'max:500'];
            } elseif ($source === 'upload') {
                $videoConfig = config('lms.uploads.video');
                $extensions = implode(',', $videoConfig['allowed_extensions']);
                $maxSize = $videoConfig['max_size_kb'];

                $rules['file'] = ['required', 'file', 'mimes:' . $extensions, 'max:' . $maxSize];
            }
            $rules['duration_minutes'] = ['nullable', 'integer', 'min:0'];
        } elseif ($type === 'documento') {
            $docConfig = config('lms.uploads.document');
            $extensions = implode(',', $docConfig['allowed_extensions']);
            $maxSize = $docConfig['max_size_kb'];

            $rules['file'] = ['required', 'file', 'mimes:' . $extensions, 'max:' . $maxSize];
        } elseif ($type === 'presentacion') {
            $presConfig = config('lms.uploads.presentation');
            $extensions = implode(',', $presConfig['allowed_extensions']);
            $maxSize = $presConfig['max_size_kb'];

            $rules['file'] = ['required', 'file', 'mimes:' . $extensions, 'max:' . $maxSize];
        } elseif ($type === 'texto') {
            $rules['content'] = ['required', 'string'];
        } elseif ($type === 'recurso') {
            $resConfig = config('lms.uploads.resource');
            $extensions = implode(',', $resConfig['allowed_extensions']);
            $maxSize = $resConfig['max_size_kb'];

            $rules['file'] = ['required', 'file', 'mimes:' . $extensions, 'max:' . $maxSize];
        }

        return $rules;
    }
}
