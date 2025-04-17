<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseCategoryRequest;
use App\Models\CourseCategory;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;


class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('CourseCategory/List');
    }

    public function fetch()
    {
        $data = CourseCategory::get();;
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $linkClass = 'inline-flex items-center w-full px-4 py-2 text-sm text-gray-700 disabled:cursor-not-allowed disabled:opacity-25 hover:text-gray-50 hover:bg-gray-100';

                $action =
                    '<div class="relative inline-block text-left">
                        <div class="flex justify-end">
                          <button type="button" class="dropdown-toggle py-2 rounded-md">
                          <span class="material-symbols-outlined dropdown-span" dropdown-log="' . $row->id . '">
                            more_vert
                          </span>
                          </button>
                        </div>

                        <div id="dropdown-menu-' . $row->id . '" class="hidden dropdown-menu absolute right-0 z-50 mt-2 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <button type="button" data-id="' . $row->id . '" class="edit ' . $linkClass . '">
                                Edit
                            </button>
                            
                            <button type="button" data-id="' . $row->id . '" class="delete ' . $linkClass . '">
                                 Delete
                            </button>
                        </div>
                      </div>
                      ';

                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isCreateMethod = true;
        return Inertia::render('CourseCategory/Form', compact('isCreateMethod'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(CourseCategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = \Str::slug($validated['title']);

        CourseCategory::create($validated);

        return redirect()->route('admin.course_category.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isCreateMethod = false;
        $category = CourseCategory::find($id);

        return Inertia::render('CourseCategory/Form', compact('category', 'isCreateMethod'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourseCategoryRequest $request, CourseCategory $category)
    {
        $validated = $request->validated();

        $validated['slug'] = \Str::slug($validated['title']);

        $category->fill($validated)->save();

        return redirect()->route('admin.course_category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $courseCategory = CourseCategory::find($id);

        $courseCategory->delete();
    }
}
