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

        foreach ($schema as $field) {
            $fieldKey = "response_data.{$field['field_name']}";
            $rules = [];

            if (!empty($field['is_required'])) {
                $rules[] = 'required';
            }

            switch ($field['type']) {
                case 'text':
                case 'textarea':
                case 'radio':
                case 'select':
                    $rules[] = 'string';
                    break;
                case 'number':
                    $rules[] = 'numeric';
                    break;
                case 'email':
                    $rules[] = 'email';
                    break;
                case 'checkbox':
                    $rules[] = 'array';
                    break;
                case 'file':
                    $rules[] = 'file';
                    break;
                default:
                    $rules[] = 'nullable';
                    break;
            }

            $validationRules[$fieldKey] = implode('|', $rules);
        }

        $validated = $request->validate($validationRules);
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
