<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionFormRequest extends FormRequest
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
            'course_id' => 'required',
            'limit' => ['required', 'numeric', 'min:0'],
            'course_time' => ['required', 'string', 'max:100'],
            'session' => ['required', 'string', 'max:100']
        ];
    }

    public function attributes()
    {
        return [
            'course_id' => 'course',
            'course_time' => 'duration'
        ];
    }
}
