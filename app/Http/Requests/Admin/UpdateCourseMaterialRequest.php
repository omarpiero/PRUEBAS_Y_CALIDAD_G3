<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()
            && ($this->user()->isAdmin() || $this->user()->hasPermission('materials.edit'));
    }

    public function rules(): array
    {
        $material = $this->route('material');
        $type = $this->input('type', $material?->type);
        $typeSubmitted = $this->has('type');
        $typeChanged = $material && $typeSubmitted && $type !== $material->type;

        $rules = [
            'module_id' => ['sometimes', 'required', 'exists:course_modules,id'],
            'type' => ['sometimes', 'required', 'in:video,documento,presentacion,texto,recurso'],
            'title' => ['required', 'string', 'max:300'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_downloadable' => ['nullable', 'boolean'],
        ];

        if ($type === 'video') {
            $sourceSubmitted = $this->has('video_source');
            $source = $this->input('video_source', $material?->video_source ?? 'upload');
            $sourceChanged = $material && $sourceSubmitted && $source !== $material->video_source;
            $canReuseExistingFile = (bool) ($material?->file_path) && ! $typeChanged && ! $sourceChanged;

            $rules['video_source'] = [
                ($typeSubmitted || $sourceSubmitted) ? 'required' : 'sometimes',
                'in:youtube,vimeo,upload',
            ];

            if ($source === 'youtube' || $source === 'vimeo') {
                $rules['video_url'] = [
                    ($typeSubmitted || $sourceSubmitted) ? 'required' : 'sometimes',
                    'url',
                    'max:500',
                ];
                $rules['file'] = ['prohibited'];
            } elseif ($source === 'upload' && ($typeSubmitted || $sourceSubmitted || $this->hasFile('file'))) {
                $videoConfig = config('lms.uploads.video');
                $extensions = implode(',', $videoConfig['allowed_extensions']);
                $maxSize = $videoConfig['max_size_kb'];

                $rules['file'] = [
                    $canReuseExistingFile ? 'nullable' : 'required',
                    'file',
                    'mimes:' . $extensions,
                    'max:' . $maxSize,
                ];
            }
            $rules['duration_minutes'] = ['nullable', 'integer', 'min:0'];
        } elseif ($type === 'documento') {
            $docConfig = config('lms.uploads.document');
            $extensions = implode(',', $docConfig['allowed_extensions']);
            $maxSize = $docConfig['max_size_kb'];

            if ($typeSubmitted || $this->hasFile('file')) {
                $canReuseExistingFile = (bool) ($material?->file_path) && ! $typeChanged;
                $rules['file'] = [$canReuseExistingFile ? 'nullable' : 'required', 'file', 'mimes:' . $extensions, 'max:' . $maxSize];
            }
        } elseif ($type === 'presentacion') {
            $presConfig = config('lms.uploads.presentation');
            $extensions = implode(',', $presConfig['allowed_extensions']);
            $maxSize = $presConfig['max_size_kb'];

            if ($typeSubmitted || $this->hasFile('file')) {
                $canReuseExistingFile = (bool) ($material?->file_path) && ! $typeChanged;
                $rules['file'] = [$canReuseExistingFile ? 'nullable' : 'required', 'file', 'mimes:' . $extensions, 'max:' . $maxSize];
            }
        } elseif ($type === 'texto') {
            if ($typeSubmitted || $this->has('content')) {
                $rules['content'] = ['required', 'string'];
            }

            $rules['file'] = ['prohibited'];
        } elseif ($type === 'recurso') {
            $resConfig = config('lms.uploads.resource');
            $extensions = implode(',', $resConfig['allowed_extensions']);
            $maxSize = $resConfig['max_size_kb'];

            if ($typeSubmitted || $this->hasFile('file')) {
                $canReuseExistingFile = (bool) ($material?->file_path) && ! $typeChanged;
                $rules['file'] = [$canReuseExistingFile ? 'nullable' : 'required', 'file', 'mimes:' . $extensions, 'max:' . $maxSize];
            }
        }

        return $rules;
    }
}
