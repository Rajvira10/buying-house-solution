<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\File;
use App\Models\Trim;
use App\Models\Buyer;
use App\Models\Query;
use App\Models\QueryItem;
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

            $queries = Query::where(function ($query) {
            $query->whereNull('parent_id')
                    ->whereNotExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                                ->from('queries as children')
                                ->whereColumn('children.parent_id', 'queries.id');
                    });
            })
            ->orWhereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('queries')
                    ->whereNotNull('parent_id')
                    ->groupBy('parent_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($queries)
                ->addColumn('buyer', function ($category) {
                    return $category->buyer->user->first_name . ' ' . $category->buyer->user->last_name;
                })
                ->addColumn('product_names', function ($category) {
                    return $category->items->pluck('product_name')->implode(', ');
                })
                ->addColumn('quantity', function ($category) {
                    return $category->items->pluck('approximate_quantity')->implode(', ');
                })
                ->addColumn('date', function ($category) {
                    return Carbon::parse($category->query_date)->format('d/m/Y');
                })
                ->addColumn('buyer', function ($category) {
                    return $category->buyer->user->first_name . ' ' . $category->buyer->user->last_name;
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

            if($query->parent_id != null)
            {
                $queries = Query::with('buyer', 'items', 'items.trims', 'items.images', 'items.measurements')
                ->where('parent_id', $query->parent_id)
                ->orWhere('id', $query->parent_id)
                ->orderBy('created_at', 'desc')
                ->get();
            }
            else{
                $queries = Query::with('buyer', 'items', 'items.trims', 'items.images', 'items.measurements')
                ->where('parent_id', $query->id)
                ->orWhere('id', $query->id)
                ->orderBy('created_at', 'desc')
                ->get();
            }
            
            return DataTables::of($queries)
                ->addColumn('date', function ($category) {
                    return Carbon::parse($category->query_date)->format('d/m/Y');
                })
                ->addColumn('buyer', function ($category) {
                    return $category->buyer->user->first_name . ' ' . $category->buyer->user->last_name;
                })
                ->addColumn('product_names', function ($category) {
                    return $category->items->pluck('product_name')->implode(', ');
                })
                ->addColumn('quantity', function ($category) {
                    return $category->items->pluck('approximate_quantity')->implode(', ');
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

        $logged_in_user_is_buyer = Buyer::where('user_id', auth()->user()->id)->first();

        if($logged_in_user_is_buyer != null)
        {
            $buyers = Buyer::where('user_id', auth()->user()->id)->with('user')->get();
        }
        else{
            $buyers = Buyer::with('user')->get();
        }

        return view('admin.query.query.create', compact('trims', 'buyers', 'logged_in_user_is_buyer'));
    }

    public function edit(Request $request, $query_id)
    {
        if(!in_array('query.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.query.index');

        $query = Query::with('items', 'items.trims', 'items.images', 'items.measurements')->find($query_id);

        if($query != null){
            $trims = Trim::select('id', 'name')->get();

            $logged_in_user_is_buyer = Buyer::where('user_id', auth()->user()->id)->first();

            if($logged_in_user_is_buyer != null)
            {
                $buyers = Buyer::where('user_id', auth()->user()->id)->with('user')->get();
            }
            else{
                $buyers = Buyer::with('user')->get();
            }
            return view('admin.query.query.edit', compact('query', 'trims', 'buyers', 'logged_in_user_is_buyer'));
        }
        else{
            return redirect()->route('queries.index')->with('error', 'Query Not Found');
        }
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'buyer_id' => 'required',
            'products' => 'required',
            'products.*.product_name' => 'required',
            'products.*.trim_ids' => 'required',
            'products.*.details' => 'required',
            'products.*.approximate_quantity' => 'required|numeric',
            'products.*.query_images' => 'nullable',
            'products.*.query_measurements' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            
            $query = new Query();
            $query->query_date = Carbon::now();
            $query->buyer_id = $request->buyer_id;
            $query->save();

            $query->query_no = 'QRY' . str_pad($query->id, 5, '0', STR_PAD_LEFT);
            $query->save();

            foreach ($request->products as $product) {
                $query_item = new QueryItem();
                $query_item->query_id = $query->id;
                $query_item->product_name = $product['product_name'];
                $query_item->details = $product['details'];
                $query_item->approximate_quantity = $product['approximate_quantity'];
                $query_item->save();

                $query_item->trims()->attach($product['trim_ids']);

                foreach ($product['query_images'] as $image) {
                    $image_name = $image->hashName();

                    $image_path = $image->storeAs('public/query_images', $image_name);

                    $absolute_path = asset('public' . Storage::url($image_path));

                    $image_file = new File();
                    $image_file->file_type = 'Image';
                    $image_file->file_path = $image_path;
                    $image_file->absolute_path = $absolute_path;
                    $image_file->save();

                    $query_item->images()->attach($image_file->id);
                }

                foreach ($product['query_measurements'] as $measurement) {
                    $measurement_name = $measurement->hashName();

                    $measurement_path = $measurement->storeAs('public/query_measurements', $measurement_name);

                    $absolute_path = asset('public' . Storage::url($measurement_path));

                    $measurement_file = new File();
                    $measurement_file->file_type = 'Measurement';
                    $measurement_file->file_path = $measurement_path;
                    $measurement_file->absolute_path = $absolute_path;
                    $measurement_file->save();

                    $query_item->measurements()->attach($measurement_file->id);
                }
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

        $query = Query::with('items', 'items.trims', 'items.images', 'items.measurements')->find($query_id);

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
            'buyer_id' => 'required',
            'products' => 'required',
            'products.*.product_name' => 'required',
            'products.*.trim_ids' => 'required',
            'products.*.details' => 'required',
            'products.*.approximate_quantity' => 'required|numeric',
            'products.*.query_images' => 'nullable',
            'products.*.query_measurements' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            $old_query = Query::find($query_id);

            $query = new Query();
            $query->parent_id = $old_query->parent_id ?? $old_query->id;
            $query->query_date = Carbon::now();
            $query->buyer_id = $request->buyer_id;
            $query->save();

            $query->query_no = 'QRY' . str_pad($query->id, 5, '0', STR_PAD_LEFT);
            $query->save();

            foreach ($request->products as $product) {
                $query_item = new QueryItem();
                $query_item->query_id = $query->id;
                $query_item->product_name = $product['product_name'];
                $query_item->details = $product['details'];
                $query_item->approximate_quantity = $product['approximate_quantity'];
                $query_item->save();

                $query_item->trims()->attach($product['trim_ids']);

                if(array_key_exists('query_images', $product))
                {
                    foreach ($product['query_images'] as $image) {
                        $image_name = $image->hashName();

                        $image_path = $image->storeAs('public/query_images', $image_name);

                        $absolute_path = asset('public' . Storage::url($image_path));

                        $image_file = new File();
                        $image_file->file_type = 'Image';
                        $image_file->file_path = $image_path;
                        $image_file->absolute_path = $absolute_path;
                        $image_file->save();

                        $query_item->images()->attach($image_file->id);
                    }
                }
                else{
                    foreach ($old_query->items as $item) {
                        $query_item->images()->attach($item->images->pluck('id'));
                    }
                }

                if(array_key_exists('query_measurements', $product))
                {
                    foreach ($product['query_measurements'] as $measurement) {
                        $measurement_name = $measurement->hashName();

                        $measurement_path = $measurement->storeAs('public/query_measurements', $measurement_name);

                        $absolute_path = asset('public' . Storage::url($measurement_path));

                        $measurement_file = new File();
                        $measurement_file->file_type = 'Measurement';
                        $measurement_file->file_path = $measurement_path;
                        $measurement_file->absolute_path = $absolute_path;
                        $measurement_file->save();

                        $query_item->measurements()->attach($measurement_file->id);
                    }
                }
                else{
                    foreach ($old_query->items as $item) {
                        $query_item->measurements()->attach($item->measurements->pluck('id'));
                    }
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
        if ($request->ajax()) {
            $query = Query::find($request->query_id);

            if ($query != null) {
                $query->items()->each(function ($item) {
                    $item->trims()->detach();
                    $item->images()->detach();
                    $item->measurements()->detach();
                    $item->delete(); 
                });

                $query->children()->each(function ($child) {
                    $child->items()->each(function ($item) {
                        $item->trims()->detach();
                        $item->images()->detach();
                        $item->measurements()->detach();
                        $item->delete();
                    });
                    $child->delete(); 
                });

                $query->delete();

                return response()->json(['success' => 'Query Deleted Successfully']);
            } else {
                return response()->json(['error' => 'Query Not Found']);
            }
        }
    }


}
