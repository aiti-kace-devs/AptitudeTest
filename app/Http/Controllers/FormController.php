<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Setup/AdmissionForm/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Setup/AdmissionForm/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'max:100'],
                'schema.*.title' => ['required'],
                'schema.*.options' => [
                    'nullable',
                    Rule::requiredIf(function ($attribute, $value) use ($request) {
                       
                        // Extract index from the attribute name, e.g., schema.0.options to 0
                        $index = explode('.', $attribute)[1];
            
                        // Get the 'type' value from the schema at the specific index
                        $type = data_get($request->input('schema'), "$index.type");
            
                        // Check if the 'type' is 'select', 'radio', or 'checkbox'
                        return in_array($type, ['select', 'radio', 'checkbox']);
                    }),
                    'min:1'
                ],
            ],
            [
                'schema.*.title.required'  => 'The question field is required.',
                'schema.*.options.required'  => 'The options field is required.',
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        //
    }
}
