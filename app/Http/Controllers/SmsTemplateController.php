<?php

namespace App\Http\Controllers;

use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmsTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = SmsTemplate::all();
        return view('admin.manage_sms_template', compact('templates'));
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

        if ($request->ajax()) {

            $validation = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validation->errors()->toArray(),
                ], 422);
            }

            $input = $request->all();
            SmsTemplate::create($input);

            return response()->json([
                'status' => true,
                'message' => 'Template created successfully!',
                'reload'  => route('admin.sms.template.index')
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SmsTemplate $smsTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $template = SmsTemplate::find($id);

        return view('admin.edit_sms_template', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SmsTemplate $template)
    {
        if ($request->ajax()) {

            $validation = Validator::make($request->all(), [
               'name' => 'required|string|max:255',
                'content' => 'required|string',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validation->errors()->toArray(),
                ], 422);
            }

            $input = $request->all();
            $template->fill($input)->save();

            return response()->json([
                'status' => true,
                'message' => 'Template updated successfully!',
                'reload'  => route('admin.sms.template.index')
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $template = SmsTemplate::find($id);
        $template->delete();

        return redirect()->route('admin.sms.template.index');
    }
}
