<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\File;
use App\Models\Trim;
use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class QueryController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('query.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.query.query.index');
        
        if($request->ajax()){

            $queries = Query::orderBy('created_at', 'desc')
            ->where('parent_id', null)
            ->get();

            return DataTables::of($queries)
                ->addColumn('date', function ($category) {
                    return Carbon::parse($category->query_date)->format('d/m/Y');
                })
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';

                    $edit_button .= '<li><a href="'.route('queries.show', $category->id).'" class
                    ="dropdown-item"><i class="ri-eye-fill me-2"></i> Show</a></li>';

                    if(in_array('query.history', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('queries.history', $category->id).'" class
                        ="dropdown-item"><i class="ri-history-fill me-2"></i> History</a></li>';
                    }

                    if(in_array('query.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('queries.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }

                    if(in_array('query.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteQuery(' . $category->id . ')">
                                                <i class="ri-delete-bin-6-fill align-bottom me-2 text-danger"></i> Delete
                                            </button>
                                        </li>';
                    }
                    $edit_button .= '</ul></div>';
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.query.query.index');
    }

    public function history(Request $request, $query_id)
    {
        if(!in_array('query.history', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.query.index');

        $query = Query::find($query_id);

        if($request->ajax()){

            $queries = Query::where('parent_id', $query_id)->orWhere('id', $query_id)
            ->latest()->get();

            return DataTables::of($queries)
                ->addColumn('date', function ($category) {
                    return Carbon::parse($category->query_date)->format('d/m/Y');
                })

                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';

                    $edit_button .= '<li><a href="'.route('queries.show', $category->id).'" class
                    ="dropdown-item"><i class="ri-eye-fill me-2"></i> Show</a></li>';

                    if(in_array('query.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteQuery(' . $category->id . ')">
                                                <i class="ri-delete-bin-6-fill align-bottom me-2 text-danger"></i> Delete
                                            </button>
                                        </li>';
                    }
                    $edit_button .= '</ul></div>';
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.query.query.history', compact('query_id', 'query'));
    }
        

        

    public function create(Request $request)
    {
        if(!in_array('query.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.query.query.index');

        $trims = Trim::select('id', 'name')->get();

        return view('admin.query.query.create', compact('trims'));
    }

    public function edit(Request $request, $query_id)
    {
        if(!in_array('query.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.query.index');

        $query = Query::find($query_id);

        if($query != null){
            $trims = Trim::select('id', 'name')->get();
            return view('admin.query.query.edit', compact('query', 'trims'));
        }
        else{
            return redirect()->route('queries.index')->with('error', 'Query Not Found');
        }
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'product_name' => 'required',
            'trim_ids' => 'required',
            'details' => 'required',
            'approximate_quantity' => 'required|numeric',
            'query_images' => 'required',
            'query_measurements' => 'required',
        ]);

        try {
            DB::beginTransaction();
            
            $query = new Query();
            $query->product_name = $request->product_name;
            $query->details = $request->details;
            $query->approximate_quantity = $request->approximate_quantity;
            $query->user_id = auth()->user()->id;
            $query->query_date = Carbon::now();
            $query->save();

            $query->query_no = 'QRY' . str_pad($query->id, 5, '0', STR_PAD_LEFT);
            $query->save();

            $query->trims()->attach($request->trim_ids);

            foreach ($request->file('query_images') as $image) {
                $image_name = $image->hashName();

                $image_path = $image->storeAs('public/query_images', $image_name);

                $absolute_path = asset('public' . Storage::url($image_path));

                $image_file = new File();
                $image_file->file_type = 'Image';
                $image_file->file_path = $image_path;
                $image_file->absolute_path = $absolute_path;
                $image_file->save();

                $query->images()->attach($image_file->id);
            }

            foreach ($request->file('query_measurements') as $measurement) {
                $measurement_name = $measurement->hashName();

                $measurement_path = $measurement->storeAs('public/query_measurements', $measurement_name);

                $absolute_path = asset('public' . Storage::url($measurement_path));

                $measurement_file = new File();
                $measurement_file->file_type = 'Measurement';
                $measurement_file->file_path = $measurement_path;
                $measurement_file->absolute_path = $absolute_path;
                $measurement_file->save();

                $query->measurements()->attach($measurement_file->id);
            }

            DB::commit();

            return redirect()->route('queries.index')->with('success', 'Query Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->route('queries.index')->with('error', 'Query Not Created');
        }

    }

    public function show(Request $request, $query_id)
    {
        if(!in_array('query.show', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.query.index');

        $query = Query::find($query_id);

        if($query != null){
            return view('admin.query.query.show', compact('query'));
        }
        else{
            return redirect()->route('queries.index')->with('error', 'Query Not Found');
        }
    }


    public function update(Request $request, $query_id)
    {
        $request->validate([
            'product_name' => 'required',
            'trim_ids' => 'required',
            'details' => 'required',
            'approximate_quantity' => 'required|numeric',
            'query_images' => 'nullable',
            'query_measurements' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            $old_query = Query::find($query_id);

            $query = new Query();
            $query->parent_id = $query_id;
            $query->product_name = $request->product_name;
            $query->details = $request->details;
            $query->approximate_quantity = $request->approximate_quantity;
            $query->user_id = auth()->user()->id;
            $query->query_date = Carbon::now();
            $query->save();

            $query->query_no = 'QRY' . str_pad($query->id, 5, '0', STR_PAD_LEFT);
            $query->save();

            $query->trims()->sync($request->trim_ids);

            if($request->hasFile('query_images'))
            {
                foreach ($request->file('query_images') as $image) {
                    $image_name = $image->hashName();

                    $image_path = $image->storeAs('public/query_images', $image_name);

                    $absolute_path = asset('public' . Storage::url($image_path));

                    $image_file = new File();
                    $image_file->file_type = 'Image';
                    $image_file->file_path = $image_path;
                    $image_file->absolute_path = $absolute_path;
                    $image_file->save();

                    $query->images()->attach($image_file->id);
                }
            }
            else{
                foreach ($old_query->images as $image) {
                    $query->images()->attach($image->id);
                }
            }

            if($request->hasFile('query_measurements'))
            {
                foreach ($request->file('query_measurements') as $measurement) {
                    $measurement_name = $measurement->hashName();

                    $measurement_path = $measurement->storeAs('public/query_measurements', $measurement_name);

                    $absolute_path = asset('public' . Storage::url($measurement_path));

                    $measurement_file = new File();
                    $measurement_file->file_type = 'Measurement';
                    $measurement_file->file_path = $measurement_path;
                    $measurement_file->absolute_path = $absolute_path;
                    $measurement_file->save();

                    $query->measurements()->attach($measurement_file->id);
                }
            }
            else{
                foreach ($old_query->measurements as $measurement) {
                    $query->measurements()->attach($measurement->id);
                }
            }

            DB::commit();

            return redirect()->route('queries.index')->with('success', 'Query Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return redirect()->route('queries.index')->with('error', 'Query Not Updated');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $query = Query::find($request->query_id);

            if($query != null)
            {
                $query->images()->detach();
                $query->measurements()->detach();
                $query->trims()->detach();
                $query->children()->delete();
                $query->delete();
                return response()->json(['success' => 'Query Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Query Not Found']);
            }
        }
    }

}
