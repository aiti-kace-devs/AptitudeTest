<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class FormResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function fetch()
    {

        $data = FormResponse::get(['uuid', 'created_at', 'updated_at']);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('date', function ($row) {
                return '<span class="hidden">' . strtotime($row->updated_at) . '</span>' . Carbon::parse($row->updated_at)->toDayDateTimeString();
            })
            ->editColumn('title', function ($row) {
                return '#RESPONSE_' . strtotime($row->created_at);
            })
            ->addColumn('action', function ($row) {
                $linkClass = 'inline-flex items-center w-full px-4 py-2 text-sm text-gray-700 disabled:cursor-not-allowed disabled:opacity-25 hover:text-gray-50 hover:bg-gray-100';

                $action =
                    '<div class="relative inline-block text-left">
                        <div class="flex justify-end">
                          <button type="button" class="dropdown-toggle py-2 rounded-md">
                          <span class="material-symbols-outlined dropdown-span" dropdown-log="' . $row->uuid . '">
                            more_vert
                          </span> 
                          </button>
                        </div>

                        <div id="dropdown-menu-' . $row->uuid . '" class="hidden dropdown-menu absolute right-0 z-50 mt-2 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <button type="button" data-id="' . $row->uuid . '" class="edit ' . $linkClass . '">
                                Edit
                            </button>
                            <button type="button" data-id="' . $row->uuid . '" class="view ' . $linkClass . '">
                                View
                            </button>
                            <button type="button" data-id="' . $row->uuid . '" class="delete ' . $linkClass . '">
                                 Delete
                            </button>
                        </div>
                      </div>
                      ';

                return $action;
            })
            ->rawColumns(['title', 'date', 'action'])
            ->make(true);
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
    public function show($uuid)
    {
        $formResponse = FormResponse::where('uuid', $uuid)->with('form')->first();
        $admissionForm = $formResponse->form;

        return Inertia::render('FormResponse/Show', compact('formResponse', 'admissionForm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        $formResponse = FormResponse::where('uuid', $uuid)->with('form')->first();
        $admissionForm = $formResponse->form;

        return Inertia::render('FormResponse/Edit', compact('formResponse', 'admissionForm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $formReponse = FormResponse::where('uuid', $uuid)->with('form')->first();
        $form = $formReponse->form;
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

        $formReponse->fill($validated)->save();

        return redirect()->route('admin.form.show', $form->uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $data = FormResponse::where('uuid', $uuid)->first();

        $data->delete();
    }
}
