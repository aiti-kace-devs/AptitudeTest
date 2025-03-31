<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DynamicFormRequest extends FormRequest
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
        return
            [
                'title' => ['required', 'string', 'max:100'],
                'description' => ['nullable'],
                'image' => [
                    'nullable',
                    $this->boolean('isDirty') ? ['required', 'image'] : [],
                    'max:512',
                ],
                'schema' => ['required', 'array'],
                'schema.*.title' => ['required'],
                'schema.*.description' => ['nullable'],
                'schema.*.type' => ['required', 'string', 'in:select,radio,checkbox,text,number'],
                'schema.*.options' => [
                    'nullable',
                    'required_if:schema.*.type,select,radio,checkbox',
                    'string',
                    'min:1'
                ],
                'schema.*.is_required' => ['nullable', 'boolean']
            ];
    }

    public function attributes()
    {
        return [
            'schema' => 'question',
            'schema.*.title' => 'question',
            'schema.*.description' => 'description',
            'schema.*.options' => 'options',
        ];
    }

    public function messages()
    {
        return [
            // 'schema.required'   => 'A question is required.',
            // 'schema.*.title.required'  => 'The question field is required.',
            'schema.*.options.required_if'  => 'The options field is required.',
        ];
    }
}
