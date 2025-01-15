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
                'schema' => ['required', 'array'],
                'schema.*.title' => ['required'],
                'schema.*.type' => ['required', 'string', 'in:select,radio,checkbox,text,number'],
                'schema.*.options' => [
                    'nullable',
                    'required_if:schema.*.type,select,radio,checkbox',
                    'string',
                    'min:1'
                ],
            ],
            [
                'schema.*.title.required'  => 'The question field is required.',
                'schema.*.options.required_if'  => 'The options field is required.',
            ]
        );

        Form::create($validated);

        return redirect()->route('admin.setup.admission_form.index');
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
