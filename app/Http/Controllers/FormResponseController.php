<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use Illuminate\Http\Request;

class FormResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $form = Form::where('uuid', $request->form_uuid)->first();
        $schema = $form->schema;

        $validationRules = [
            'response_data' => 'required|array',
        ];

        $customMessages = [
            'response_data.required' => 'The form responses are required.',
        ];

        foreach ($schema as $field) {
            $fieldKey = "response_data.{$field['field_name']}";

            $rules = [];

            $field['title'] = strtolower($field['title']);

            if (!empty($field['is_required'])) {
                $rules[] = 'required';
                $customMessages["{$fieldKey}.required"] = "The {$field['title']} field is required.";
            } else {
                $rules[] = 'nullable'; 
            }

            switch ($field['type']) {
                case 'text':
                case 'textarea':
                    $rules[] = 'string';
                    $customMessages["{$fieldKey}.string"] = "The {$field['title']} must be a valid string.";
                    break;

                case 'radio':
                case 'select':
                    $rules[] = 'string';
                    $customMessages["{$fieldKey}.string"] = "The {$field['title']} must be a valid option.";
                    break;

                case 'number':
                    $rules[] = 'numeric';
                    $customMessages["{$fieldKey}.numeric"] = "The {$field['title']} must be a valid number.";
                    break;

                case 'email':
                    $rules[] = 'email';
                    $customMessages["{$fieldKey}.email"] = "The {$field['title']} must be a valid email address.";
                    break;

                case 'checkbox':
                    $rules[] = 'array'; 
                    $customMessages["{$fieldKey}.array"] = "The {$field['title']} must be a valid array.";
                    break;

                case 'file':
                    $rules[] = 'file';
                    $customMessages["{$fieldKey}.file"] = "The {$field['title']} must be a valid file.";
                    break;

                default:
                    $rules[] = 'nullable'; 
                    break;
            }

            $validationRules[$fieldKey] = implode('|', $rules);
        }

        $validated = $request->validate($validationRules, $customMessages);

        $response = new FormResponse($validated);

        $form->responses()->save($response);
    }


    /**
     * Display the specified resource.
     */
    public function show(FormResponse $formResponse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormResponse $formResponse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormResponse $formResponse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormResponse $formResponse)
    {
        //
    }
}
