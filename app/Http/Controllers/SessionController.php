<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionFormRequest;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sessions = Session::get();

        return Inertia::render('Session/List');
    }

    public function fetch()
    {
        $data = Session::get(['uuid', 'title', 'starts_at', 'ends_at', 'updated_at']);
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('starts_at', function ($row) {
                return '<span class="hidden">' . strtotime($row->starts_at) . '</span>' . Carbon::parse($row->starts_at)->format('h:i A');
            })
            ->editColumn('ends_at', function ($row) {
                return '<span class="hidden">' . strtotime($row->ends_at) . '</span>' . Carbon::parse($row->ends_at)->format('h:i A');
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
                            
                            <button type="button" data-id="' . $row->uuid . '" class="delete ' . $linkClass . '">
                                 Delete
                            </button>
                        </div>
                      </div>
                      ';

                return $action;
            })
            ->rawColumns(['starts_at', 'ends_at', 'action'])
            ->make(true);
    }

    public function create()
    {
        return Inertia::render('Session/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(SessionFormRequest $request)
    {
        $validated = $request->validated();

        Session::create($validated);

        return redirect()->route('admin.session.index');
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
    public function edit($uuid)
    {
        $session = Session::where('uuid', $uuid)->firstOrFail();

        return Inertia::render('Session/Edit', compact('session'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SessionFormRequest $request, $uuid)
    {
        $validated = $request->validated();
        $session = Session::where('uuid', $uuid)->first();

        $session->fill($validated)->save();
        return redirect()->route('admin.session.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $session = Session::where('uuid', $uuid)->first();

        $session->delete();
    }
}
