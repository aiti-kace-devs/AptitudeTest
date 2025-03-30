<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Course;
use App\Http\Requests\DynamicFormRequest;
use App\Models\Form;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Form/List');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function fetch()
    {

        $data = Form::get(['uuid', 'title', 'active', 'updated_at']);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('date', function ($row) {
                return '<span class="hidden">' . strtotime($row->updated_at) . '</span>' . Carbon::parse($row->updated_at)->toDayDateTimeString();
            })
            ->editColumn('active', function ($row) {
                return '<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">' . $row->active ? 'Active' : 'Inactive' . '</span>';
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
                            <button type="button" data-id="' . $row->uuid . '" class="preview ' . $linkClass . '">
                                Preview
                            </button>
                            <button type="button" data-id="' . $row->uuid . '" class="responses ' . $linkClass . '">
                                Responses
                            </button>
                            <button type="button" data-id="' . $row->uuid . '" class="delete ' . $linkClass . '">
                                 Delete
                            </button>
                        </div>
                      </div>
                      ';

                return $action;
            })
            ->rawColumns(['date', 'action'])
            ->make(true);
    }

    public function create()
    {
        return Inertia::render('Form/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DynamicFormRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $destinationPath = 'form/banner/';
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();

            // Delete old image if it exists
            if (\Storage::disk('public')->exists($destinationPath . $fileName)) {
                \Storage::disk('public')->delete($destinationPath . $fileName);
            }

            // Save new image
            \Storage::disk('public')->putFileAs($destinationPath, $image, $fileName);
            $validated['image'] = $fileName;
        }

        Form::create($validated);

        return redirect()->route('admin.form.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        $form = Form::where('uuid', $uuid)->first();

        return Inertia::render('Form/Show', compact('form'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        $admissionForm = Form::where('uuid', $uuid)->first();
        $admissionForm->image = $admissionForm->image ? asset('storage/form/banner/' . $admissionForm->image) : null;

        return Inertia::render('Form/Edit', compact('admissionForm'));
    }

    public function preview($uuid)
    {
        $admissionForm = Form::where('uuid', $uuid)->first();
        //        $admissionForm->image = $admissionForm->image ? asset('storage/form/banner/' . $admissionForm->image) : null;

        $courses = [];
        $branches = [];
        $withLayout = true;

        if (isset($admissionForm->schema)) {
            $courses = Course::get();
            $branches = Branch::orderBy('title')->get();
        }
        return Inertia::render('Form/Preview', compact('admissionForm', 'courses', 'branches', 'withLayout'));
    }


    public function submitForm($formCode)
    {
        // 679c89bf-91ec-488e-9878-0d010468ca3e
        $admissionForm = Form::where('code', $formCode)->first();
        if (!$admissionForm) {
            return redirect('home');
        }
        $withLayout = false;

        $courses = Course::get();
        $branches = Branch::orderBy('title')->get();

        return Inertia::render('Form/Preview', compact('admissionForm', 'courses', 'branches', 'withLayout'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DynamicFormRequest $request, $uuid)
    {
        $validated = $request->validated();
        $form = Form::where('uuid', $uuid)->first();

        // Handle image upload if necessary
        if ($request->isDirty && $request->hasFile('image')) {
            $destinationPath = 'form/banner/';
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();

            // Delete old image if it exists
            if ($form->image && \Storage::disk('public')->exists($destinationPath . $form->image)) {
                \Storage::disk('public')->delete($destinationPath . $form->image);
            }

            // Save new image
            \Storage::disk('public')->putFileAs($destinationPath, $image, $fileName);
            $validated['image'] = $fileName;
        } else {
            // Retain existing image
            $validated['image'] = $form->image;
        }

        $form->fill($validated)->save();

        return redirect()->route('admin.form.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $form = Form::where('uuid', $uuid)->first();

        $form->delete();
    }
}
