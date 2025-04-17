<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgrammeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_category_id' => 'required',
            'title' => 'required',
            'duration' => 'required',
            'start_date' => 'sometimes',
            'end_date' => 'sometimes',
            'status' => 'nullable',
            'image' => [
                'nullable',
                $this->boolean('isDirty') ? ['required', 'image'] : [],
                'max:2048',
            ],
            'description' => 'sometimes',
            'content' => 'sometimes'
        ];
    }

    public function attributes()
    {
        return [
            'course_category_id' => 'category'
        ];
    }
}
