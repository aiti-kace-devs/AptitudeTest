<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// class RegisteredUserController extends Controller
// {
//     /**
//      * Display the registration view.
//      *
//      * @return \Illuminate\View\View
//      */
//     public function create()
//     {
//         return view('auth.register');
//     }

//     /**
//      * Handle an incoming registration request.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\RedirectResponse
//      *
//      * @throws \Illuminate\Validation\ValidationException
//      */
//     public function store(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|confirmed|min:8',
//         ]);

//         Auth::login($admin = Admin::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]));

//         event(new Registered($admin));

//         // return redirect('admin/dashboard');
//         return redirect(RouteServiceProvider::ADMIN_HOME);
//     }
// }


class RegisteredUserController extends Controller
{
    /**
     * Display a listing of admins.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins =  $admins = Admin::all(); 
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created admin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.admins.index')->with([
            'flash' => 'Admin created successfully!',
            'key' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified admin.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id, Admin::find($id));
        $admin = Admin::findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }
        
        $admin->save();

        return redirect()->route('admin.admins.index')->with([
            'flash' => 'Admin updated successfully!',
            'key' => 'success'
        ]);
    }

    /**
     * Remove the specified admin from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.admins.index')->with([
            'flash' => 'Admin deleted successfully!',
            'key' => 'success'
        ]);
    }
}
