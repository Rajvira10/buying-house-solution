<?php

namespace App\Http\Controllers\Admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\File;
use App\Models\Employee;
use App\Models\Warehouse;
use App\Models\Department;
use App\Models\JobDuration;
use Illuminate\Http\Request;
use App\Models\SalaryStructure;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('employee.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.employee.index');

        $warehouse = session('user_warehouse');
        
        if($request->ajax()){
                    
            $employees = Employee::join('departments as d', 'd.id', '=', 'employees.department_id')
                ->leftJoin('files as f', 'f.id', '=', 'employees.image_id')
                ->select(
                    'employees.*',
                    'd.name as department_name',
                    'f.absolute_path as image_path'
                )
                ->where('employees.warehouse_id', '=', $warehouse->id)
                ->orderBy('id', 'asc')
                ->get();


            return DataTables::of($employees)

                ->addColumn('image', function ($employee){

                    return '<img src="'.$employee->image_path.'" style="height: 100px; width:100px; object-fit: cover" alt="">';

                })    

                ->addColumn('action', function ($employee) {

                    if(!in_array('employee.index', session('user_permissions')))
                    {
                        return '';
                    }

                    $edit_button = '<div class="dropdown d-inline-block">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="'.route('employees.show', $employee->id).'" class="dropdown-item"><i class="ri-eye-fill eye-icon align-bottom me-2 text-muted"></i>View</a></li>
                                            ';
                    if(in_array('employee.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('employees.edit', $employee->id).'" class="dropdown-item"><i class="ri-pencil-line me-2"></i>Edit</a></li>';
                    }

                    // if(in_array('employee.delete', session('user_permissions')))
                    // {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteEmployee(' . $employee->id . ')">
                                                <i class="ri-delete-bin-6-fill align-bottom me-2 text-danger"></i> Delete
                                            </button>
                                        </li>';
                    // }

                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['image','action'])
                ->make(true);
        }
        return view('admin.hrm.employee.index', compact('warehouse'));
    }

    public function show(Request $request, $employee_id)
    {
        if(!in_array('employee.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.employee.index');

        $employee = Employee::join('departments as d', 'd.id', '=', 'employees.department_id')
        ->leftJoin('files as image', 'image.id', '=', 'employees.image_id')
        ->leftJoin('files as cv', 'cv.id', '=', 'employees.cv_id')
        ->select(
            'employees.*',
            'd.name as department_name',
            'image.absolute_path as image_path',
            'cv.absolute_path as cv_path',
        )
        ->with('job_durations', 'salary_structures')
        ->where('employees.id','=', $employee_id)
        ->first();
        
        
        if($employee != null){
            return view('admin.hrm.employee.show', compact('employee'));
        }
        else{
            return redirect()->route('employees.index')->with('error', 'Employee Not Found');
        }
    }

    public function create(Request $request)
    {
        if(!in_array('employee.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.employee.index');

        $departments = Department::select(
            'id',
            'name'
        )
        ->orderBy('id', 'asc')
        ->get();

        return view('admin.hrm.employee.create', compact('departments'));
    }

    public function edit(Request $request, $employee_id)
    {
        if(!in_array('employee.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.employee.index');

        $employee = Employee::find($employee_id);
        
        if($employee != null){

            $departments = Department::select(
                'id',
                'name'
            )->get();

            return view('admin.hrm.employee.edit', compact('employee','departments'));
        }
        else{
            return redirect()->route('employee.index')->with('error', 'Employee Not Found');
        }
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required',
            'email' => 'required|email|unique:employees',
            'nid' => 'required|unique:employees',
            'contact_no' => 'required|unique:employees',
            'present_address' => 'required',
            'permanent_address' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cv'=> 'nullable|file|mimes:pdf|max:2048',
            'joining_date' => 'required',
            'gross_salary' => 'required|numeric|min:1',
            'current_salary_starting_date' => 'required',
            'h_rent_percent' => 'required|numeric|min:0|max:100',
            'med_percent' => 'required|numeric|min:0|max:100',
            'conv_percent' => 'required|numeric|min:0|max:100',
        ]);

        if(!$this->validateContactNo($request->contact_no))
        {
            return redirect()->back()->withInput()->withErrors(['contact_no' => 'Invalid contact number']);
        }

        DB::beginTransaction();

        try {
            
            $employee = new Employee();

            $employee->warehouse_id = session('user_warehouse')->id;

            $employee->name = $request->name;

            $employee->department_id = $request->department_id;

            $employee->designation = $request->designation;

            $employee->email = $request->email;

            $employee->nid = $request->nid;

            $employee->contact_no = $request->contact_no;

            $employee->present_address = $request->present_address;

            $employee->permanent_address = $request->permanent_address;

            // EMPLOYEE IMAGE

            $image_name = $request->file('image')->hashName();

            $image_path = $request->file('image')->storeAs('public/employee_images', $image_name);

            $absolute_path = asset('public' . Storage::url($image_path));

            $image_file = new File();

            $image_file->file_type = 'Image';
            
            $image_file->file_path = $image_path;

            $image_file->absolute_path = $absolute_path;

            $image_file->save();

            // EMPLOYEE CV

            if($request->hasFile('cv')){

                // EMPLOYEE CV

                $cv_name = $request->file('cv')->hashName();

                $cv_path = $request->file('cv')->storeAs('public/employee_cvs', $cv_name);

                $cv_absolute_path = asset('public' . Storage::url($cv_path));

                $cv_file = new File();

                $cv_file->file_type = 'Document';

                $cv_file->file_path = $cv_path;

                $cv_file->absolute_path = $cv_absolute_path;

                $cv_file->save();

                $employee->cv_id = $cv_file->id;

            }

            $employee->image_id = $image_file->id;

            $employee->status = 'Active';
            
            $employee->save();

            $employee->unique_id = 'Emp-' . ($employee->id + 1000);

            $employee->save();

            
            $job_duration = new JobDuration();

            $job_duration->employee_id = $employee->id;

            $job_duration->start_date = Carbon::createFromFormat('d/m/Y', $request->joining_date)->toDateTimeString();

            $job_duration->save();


            $salary_structure = new SalaryStructure();

            $salary_structure->employee_id = $employee->id;

            $salary_structure->gross = $request->gross_salary;

            $salary_structure->h_rent_percent = $request->h_rent_percent;

            $salary_structure->med_percent = $request->med_percent;

            $salary_structure->conv_percent = $request->conv_percent;

            $salary_structure->start_date = Carbon::createFromFormat('d/m/Y',$request->current_salary_starting_date)->toDateTimeString();
            
            $salary_structure->save();

            DB::commit();

            return redirect()->route('employees.index')->with('success', 'Employee Created Successfully');
            
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->withInput()->withErrors(['error' => 'Something went wrong. Please try again later']);
        }

    }

    public function update(Request $request, $employee_id)
    {
        $request->validate([
            'name' => 'required',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required',
            'email' => 'required|email|unique:employees,email,'.$employee_id,
            'nid' => 'required|unique:employees,nid,'.$employee_id,
            'contact_no' => 'required|unique:employees,contact_no,'.$employee_id,
            'present_address' => 'required',
            'permanent_address' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cv'=> 'nullable|file|mimes:pdf|max:2048',
        ]);
        
        if(!$this->validateContactNo($request->contact_no))
        {
            return redirect()->back()->withInput()->withErrors(['contact_no' => 'Invalid contact number']);
        }

        $employee = Employee::find($employee_id);

        if($employee != null){

            $employee->warehouse_id = session('user_warehouse')->id;

            $employee->name = $request->name;

            $employee->department_id = $request->department_id;

            $employee->designation = $request->designation;

            $employee->email = $request->email;

            $employee->nid = $request->nid;

            $employee->contact_no = $request->contact_no;

            $employee->present_address = $request->present_address;

            $employee->permanent_address = $request->permanent_address;

            if($request->hasFile('image')){

                // EMPLOYEE IMAGE

                $image_name = $request->file('image')->hashName();

                $image_path = $request->file('image')->storeAs('public/employee_images', $image_name);

                $absolute_path = asset('public' . Storage::url($image_path));

                $image_file = new File();

                $image_file->file_type = 'Image';
                
                $image_file->file_path = $image_path;

                $image_file->absolute_path = $absolute_path;

                $image_file->save();

                $employee->image_id = $image_file->id;
            }

            if($request->hasFile('cv')){

                // EMPLOYEE CV

                $cv_name = $request->file('cv')->hashName();

                $cv_path = $request->file('cv')->storeAs('public/employee_cvs', $cv_name);

                $cv_absolute_path = asset('public' . Storage::url($cv_path));

                $cv_file = new File();

                $cv_file->file_type = 'Document';

                $cv_file->file_path = $cv_path;

                $cv_file->absolute_path = $cv_absolute_path;

                $cv_file->save();

                $employee->cv_id = $cv_file->id;
            }

            $employee->save();

            return redirect()->route('employees.index')->with('success', 'Employee Updated Successfully');
        }
        else{
            return redirect()->route('employee.index')->with('error', 'Employee Not Found');
        }
    }

    public function print(Request $request, $employee_id)
    {
        if(!in_array('employee.print', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $employee = Employee::join('departments as d', 'd.id', '=', 'employees.department_id')
        ->leftJoin('files as image', 'image.id', '=', 'employees.image_id')
        ->leftJoin('files as cv', 'cv.id', '=', 'employees.cv_id')
        ->select(
            'employees.*',
            'd.name as department_name',
            'image.absolute_path as image_path',
            'cv.absolute_path as cv_path',
        )
        ->with('job_durations', 'salary_structures')
        ->where('employees.id','=', $employee_id)
        ->first();
        
        if($employee != null){
            return view('admin.hrm.employee.print', compact('employee'));
        }
        else{
            return redirect()->route('employees.index')->with('error', 'Employee Not Found');
        }
    } 

    public function createSalaryStructure(Request $request, $employee_id)
    {
        if(!in_array('salary_structure.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.hrm.employee.index');

        $employee = Employee::find($employee_id);

        if($employee != null){

            return view('admin.hrm.employee.create_salary_structure', compact('employee'));
        }
        else{
            return redirect()->route('employee.index')->with('error', 'Employee Not Found');
        }
    }

    public function storeSalaryStructure(Request $request, $employee_id)
    {
        $request->validate([
            'gross_salary' => 'required|numeric|min:1',
            'h_rent_percent' => 'required|numeric|min:0|max:100',
            'med_percent' => 'required|numeric|min:0|max:100',
            'conv_percent' => 'required|numeric|min:0|max:100',
            'current_salary_starting_date' => 'required',
        ]);

        $employee = Employee::find($employee_id);

        if($employee != null){

            $latest_salary_structure = Employee::find($employee_id)->salary_structures()->orderBy('id', 'desc')->first();

            if($latest_salary_structure != null){

                $latest_salary_structure->end_date = Carbon::createFromFormat('d/m/Y',$request->current_salary_starting_date)->subDay()->toDateTimeString();

                $latest_salary_structure->save();
            }

            $salary_structure = new SalaryStructure();

            $salary_structure->employee_id = $employee->id;

            $salary_structure->gross = $request->gross_salary;

            $salary_structure->h_rent_percent = $request->h_rent_percent;

            $salary_structure->med_percent = $request->med_percent;

            $salary_structure->conv_percent = $request->conv_percent;

            $salary_structure->start_date = Carbon::createFromFormat('d/m/Y',$request->current_salary_starting_date)->toDateTimeString();
            
            $salary_structure->save();

            return redirect()->route('employees.show', $employee->id)->with([
                'success' => 'Salary Structure Created Successfully',
                'tab' => 'salary_structure'
            ]);
        }
        else{
            return redirect()->route('employee.index')->with('error', 'Employee Not Found');
        }
    }

    public function activate(Request $request, $employee_id)
    {
        if(!in_array('job_duration.toggle_status', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $employee = Employee::find($employee_id);

        if($employee != null){

            $employee->status = 'Active';

            $employee->save();

            $job_duration = new JobDuration();

            $job_duration->employee_id = $employee->id;

            $job_duration->start_date = Carbon::parse($request->start_date)->format('Y-m-d');

            $job_duration->save();

            return back()->with('success', 'Employee Activated Successfully');
        }
        else{
            return back()->with('error', 'Employee Not Found');
        }
    }

    
    public function deactivate(Request $request, $employee_id)
    {
        if(!in_array('job_duration.toggle_status', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $employee = Employee::find($employee_id);

        if($employee != null){

            $employee->status = 'Inactive';

            $employee->save();

            $job_duration = $employee->job_durations()->orderBy('id', 'desc')->first();

            if($request->end_date < $job_duration->start_date)
            {
                return back()->with('error', 'End date must be greater than start date');
            }

            $job_duration->end_date = Carbon::parse($request->end_date)->format('Y-m-d');

            $job_duration->save();

            return back()->with('success', 'Employee Deactivated Successfully');
        }
        else{
            return back()->with('error', 'Employee Not Found');
        }
    }

    public function getEmployeesByWarehouse(Request $request)
    {
        if($request->ajax())
        {
            $employees = Employee::where('warehouse_id', $request->warehouse_id)->get();

            return response()->json($employees);
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $employee = Employee::find($request->employee_id);

            if($employee != null)
            {
                $employee->job_durations()->delete();
                $employee->salary_structures()->delete();
                $employee->payroll()->detach();
                $employee->delete();
                return response()->json(['success' => 'Employee Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Employee Not Found']);
            }
        }
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
