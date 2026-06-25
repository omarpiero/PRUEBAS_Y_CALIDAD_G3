<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()
            && ($this->user()->isAdmin() || $this->user()->hasPermission('courses.edit'));
    }

    public function rules(): array
    {
        $coverConfig = config('lms.uploads.cover');
        $extensions = implode(',', $coverConfig['allowed_extensions']);
        $maxSize = $coverConfig['max_size_kb'];

        $courseId = $this->route('course')?->id ?? $this->route('course');

        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:220', 'unique:courses,slug,' . $courseId],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:basico,intermedio,avanzado'],
            'status' => ['required', 'in:borrador,publicado,archivado'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'sale_start' => ['nullable', 'date'],
            'sale_end' => ['nullable', 'date', 'after_or_equal:sale_start'],
            'duration_weeks' => ['required', 'integer', 'min:1'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'is_featured' => ['nullable', 'boolean'],
        ];

        if ($this->hasFile('cover_image')) {
            $rules['cover_image'] = ['file', 'image', 'mimes:' . $extensions, 'max:' . $maxSize];
        } else {
            $rules['cover_image'] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }
}
