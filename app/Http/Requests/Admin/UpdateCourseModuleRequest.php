<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()
            && ($this->user()->isAdmin() || $this->user()->hasPermission('modules.edit'));
    }

    public function rules(): array
    {
        return [
            'course_id' => ['sometimes', 'required', 'exists:courses,id'],
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:activo,inactivo'],
        ];
    }
}
