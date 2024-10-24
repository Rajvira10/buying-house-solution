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
            'credential' => 'required',
            'password' => 'required | string'
        ], [
            'credential.required' => 'Please enter your credential !',
            'password.required' => 'Please enter your password !',
            'password.string' => 'Only alphabets, numbers & special characters are allowed. Must be a string !'
        ]);

        if(Auth::guard('admin')->attempt(['email' => $request->credential, 'password' => $request->password]) || Auth::guard('admin')->attempt(['username' => $request->credential, 'password' => $request->password]))
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

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required | string',
            'new_password' => 'required | string | min:8',
            'confirm_password' => 'required | string | same:new_password'
        ], [
            'current_password.required' => 'Please enter your current password !',
            'current_password.string' => 'Only alphabets, numbers & special characters are allowed. Must be a string !',
            'new_password.required' => 'Please enter your new password !',
            'new_password.string' => 'Only alphabets, numbers & special characters are allowed. Must be a string !',
            'new_password.min' => 'Password must be at least 8 characters long !',
            'confirm_password.required' => 'Please confirm your new password !',
            'confirm_password.string' => 'Only alphabets, numbers & special characters are allowed. Must be a string !',
            'confirm_password.same' => 'New password & confirm password must be same !'
        ]);

        if(Auth::guard('admin')->attempt(['email' => Auth::guard('admin')->user()->email, 'password' => $request->current_password]))
        {
            $user = Auth::guard('admin')->user();

            $user->password = bcrypt($request->new_password);

            $user->save();

            return back()->with(['success' => 'Password changed successfully !']);
        }

        return back()->with(['error' => 'Invalid Current Password !']);
    }
}
