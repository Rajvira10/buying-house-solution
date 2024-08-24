<?php

namespace App\Http\Controllers\Admin;

use App\Models\File;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->now('view_name', 'admin.settings.index');

        $settings = Setting::first();
        
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'full_name' => 'required',
            'short_name' => 'required',
            'website' => 'nullable',
            'email1' => 'nullable|email',
            'email2' => 'nullable|email',
            'contact1' => 'nullable',
            'contact2' => 'nullable',
            'address' => 'nullable',
        ]);

        if($request->contact1 !== null && $request->contact2 !== null){

            if(!$this->validateContactNo($request->contact1))
            {
                return redirect()->back()->withInput()->withErrors(['contact1' => 'Invalid contact number']);
            }

            if(!$this->validateContactNo($request->contact2))
            {
                return redirect()->back()->withInput()->withErrors(['contact2' => 'Invalid contact number']);
            }
        }

        $setting = Setting::firstOrNew(['id' => 1]);

        if($setting->logo_id === null || $setting->favicon_id === null)
        {
            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
                'favicon' => 'required|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            ]);
        }
        else
        {
            $request->validate([
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
                'favicon' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            ]);
        }

        $setting->full_name = $request->full_name;

        $setting->short_name = $request->short_name;

        $setting->website = $request->website;

        $setting->email1 = $request->email1;

        $setting->email2 = $request->email2;

        $setting->contact1 = $request->contact1;

        $setting->contact2 = $request->contact2;

        $setting->address = $request->address;
        
        if($request->hasFile('logo'))
        {
            $logo_name = $request->file('logo')->hashName();

            $logo_path = $request->file('logo')->storeAs('public/settings', $logo_name);

            $logo_absolute_path = asset('public' . Storage::url($logo_path));

            $logo_file = new File();

            $logo_file->file_type = 'Document';

            $logo_file->file_path = $logo_path;

            $logo_file->absolute_path = $logo_absolute_path;

            $logo_file->save();

            $setting->logo_id = $logo_file->id;
            
        }

        if($request->hasFile('favicon'))
        {
            $favicon_name = $request->file('favicon')->hashName();

            $favicon_path = $request->file('favicon')->storeAs('public/settings', $favicon_name);

            $favicon_absolute_path = asset('public' . Storage::url($favicon_path));

            $favicon_file = new File();

            $favicon_file->file_type = 'Document';

            $favicon_file->file_path = $favicon_path;

            $favicon_file->absolute_path = $favicon_absolute_path;

            $favicon_file->save();

            $setting->favicon_id = $favicon_file->id;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }


    public function deploy()
    {
        $command = "git pull https://rajvira10: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@github.com/wardantech/inventory_solution.git";

        exec($command);

        return redirect()->back()->with('success', 'Deployed successfully.');
    }

    public function validateContactNo($input)
    {
        $arr = ['0','1','2','3','4','5','6','7','8','9','-','+','(',')',' '];

        $flag = true;

        for($i = 0; $i < strlen($input); $i++)
        {
            if(!in_array($input[$i], $arr))
            {
                $flag = false;

                break;
            }
        }

        return $flag;
    }
}
