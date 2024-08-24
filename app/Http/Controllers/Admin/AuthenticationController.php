<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Carbon\Carbon;
use App\Models\Warehouse;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthenticationController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required | email',
            'password' => 'required | string'
        ], [
            'email.required' => 'Please enter your email !',
            'email.email' => 'Please enter a valid email !',
            'password.required' => 'Please enter your password !',
            'password.string' => 'Only alphabets, numbers & special characters are allowed. Must be a string !'
        ]);

        if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $request->session()->regenerate();

            $roles = Auth::guard('admin')->user()->roles;

            $roles = $roles->pluck('name')->toArray();

            $warehouses = Warehouse::all();

            $warehouse = Warehouse::where('id', Auth::guard('admin')->user()->warehouse_id)->first();

            if(in_array('super_admin', $roles))
            {
                $permissions = Permission::all();

                $permissions = $permissions->pluck('name')->toArray();

                $request->session()->put('user_permissions', $permissions);

                if($warehouses->count() > 0)
                {
                    $request->session()->put('user_warehouse', $warehouses[0]);

                    $request->session()->put('user_warehouses', $warehouses);
                }
            }
            else{
                $request->session()->put('user_permissions', Auth::guard('admin')->user()->getPermissions());

                $request->session()->put('user_warehouse', $warehouse);
            }

            $request->session()->put('session_start', Carbon::now()->toDateTimeString());

            return redirect()->intended(route('admin-dashboard'));
        }

        return redirect()->route('admin-login')->with(['login_error' => 'Invalid Email/Password !']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin-login');
    }
}
