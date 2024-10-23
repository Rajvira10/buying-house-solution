<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Tna;
use App\Models\File;
use App\Models\Trim;
use App\Models\User;
use App\Models\Brand;
use App\Models\Buyer;
use App\Models\Order;
use App\Models\Query;
use App\Models\Factory;
use App\Models\Product;
use App\Models\Employee;
use App\Models\QueryChat;
use App\Models\QueryItem;
use App\Models\Department;
use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Models\QueryMerchandiser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\QueryItemSpecificationSheet;

class QueryController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('query.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.query.query.index');

        $merchandisers = Employee::whereHas('user.roles', function ($query) {
            $query->where('name', 'merchandiser');
        })->get();
        
        if($request->ajax()){

            if(in_array('see_everything', session('user_permissions')))
            {
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
            }
            else{
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
                ->whereHas('brand.buyer.user', function ($query) {
                    $query->where('id', auth()->user()->id);
                })
                ->orWhereHas('merchandiser.user', function ($query) {
                    $query->where('id', auth()->user()->id);
                })
                ->orderBy('created_at', 'desc')
                ->get();
            }
            
            return DataTables::of($queries)
                ->addColumn('status', function ($category) {
                    if($category->status == 'Pending')
                    {
                        return '<span class="badge bg-soft-warning text-warning">Pending</span>';
                    }
                    elseif($category->status == 'Approved')
                    {
                        return '<span class="badge bg-soft-success text-success">Approved</span>';
                    }
                    elseif($category->status == "Sent For Approval")
                    {
                        return '<span class="badge bg-soft-primary text-primary">Sent For Approval</span>';
                    }
                    elseif($category->status == 'Rejected')
                    {
                        return '<span class="badge bg-soft-danger text-danger">Rejected</span>';
                    }
                    else{
                        return '<span class="badge bg-soft-info text-info">Updated</span>';
                    }
                })
                ->addColumn('brand', function ($category) {
                    return $category->brand->name;
                })
                ->addColumn('product_type', function ($category) {
                    return $category->product_type->name;
                })
                ->addColumn('quantity', function ($category) {
                    return $category->items->pluck('approximate_quantity')->implode(', ');
                })
                ->addColumn('date', function ($category) {
                    return Carbon::parse($category->query_date)->format('d/m/Y');
                })
                ->addColumn('merchandiser', function ($category) {
                    return $category->merchandiser ? $category->merchandiser->user->username : 'N/A';
                })
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';

                    $edit_button .= '<li><a href="'.route('queries.show', $category->id).'" class
                    ="dropdown-item"><i class="ri-eye-fill me-2"></i> Show</a></li>';

                    if(in_array('query.chat', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('queries.chat', $category->id).'" class
                        ="dropdown-item"><i class="ri-chat-1-line me-2"></i> Chat</a></li>';
                    }


                    if(in_array('query.change_status', session('user_permissions')) && ($category->status != 'Waiting For Approval' || $category->status != 'Approved'))
                    {
                        $edit_button .= '<li><button type="submit" class="dropdown-item" onclick="changeQueryStatus(' . $category->id . ', \'' . addslashes($category->product_type->name) . '\')">
                                            <i class="ri-checkbox-circle-fill me-2"></i> Change Status
                                        </button></li>';
                    }

                    if(in_array('query.change_status', session('user_permissions')) && $category->status == 'Approved')
                    {
                        $edit_button .= '<li><button type="submit" class="dropdown-item" onclick="inputFactoryCost(' . $category->id . ')">
                                            <i class="ri-checkbox-circle-fill me-2"></i> Input Factory Cost
                                        </button></li>';
                    }


                    if($category->status == 'Rejected')
                    {
                        $edit_button .= '<li><button type="submit" class="dropdown-item" onclick="showRejectionNote(\'' . addslashes($category->rejection_note) . '\')">
                                            <i class="ri-error-warning-fill me-2"></i> Show Rejection Note
                                        </button></li>';
                    }


                    if(in_array('query.history', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('queries.history', $category->id).'" class
                        ="dropdown-item"><i class="ri-history-fill me-2"></i> History</a></li>';
                    }

                    if(in_array('query.view_merchandiser_assign_history', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('queries.merchandiser_assign_history', $category->id).'" class
                        ="dropdown-item"><i class="ri-history-fill me-2"></i> Merchandiser Assign History</a></li>';
                    }

                    if(in_array('query.assign_merchandiser', session('user_permissions')))
                    {
                        $edit_button .= '<li><button type="submit" class="dropdown-item" onclick="assignMerchandiser(' . $category->id . ')">
                                            <i class="ri-user-add-fill me-2"></i> Assign Merchandiser
                                        </button></li>';
                    }

                    if(in_array('query.edit', session('user_permissions')) && $category->status != 'Approved')
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
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.query.query.index', compact('merchandisers'));
    }

    public function updateFactoryCost(Request $request)
    {
        if ($request->ajax()) {
            $order = Order::where('query_id', $request->query_id)->first();
            $order_items = $order->items;

            $factory_costs = $request->factory_costs;

            foreach ($order_items as $item) {
                foreach($factory_costs as $factory_cost)
                {
                    if($item->id == $factory_cost['id'])
                    {
                        $item->factory_cost = $factory_cost['factory_cost'];
                        $item->save();
                    }
                }
            }

            return response()->json(['success' => 'Factory Cost Updated Successfully']);
        }
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
                $queries = Query::with('brand', 'items', 'items.images', 'items.measurements')
                ->where('parent_id', $query->parent_id)
                ->orWhere('id', $query->parent_id)
                ->orderBy('created_at', 'desc')
                ->get();
            }
            else{
                $queries = Query::with('brand', 'items', 'items.images', 'items.measurements')
                ->where('parent_id', $query->id)
                ->orWhere('id', $query->id)
                ->orderBy('created_at', 'desc')
                ->get();
            }
            
            return DataTables::of($queries)
                ->addColumn('date', function ($category) {
                    return Carbon::parse($category->query_date)->format('d/m/Y');
                })
                ->addColumn('brand', function ($category) {
                    return $category->brand->name;
                })
                ->addColumn('product_names', function ($category) {
                    return $category->items->pluck('product.name')->implode(', ');
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


        $logged_in_user_is_buyer = Buyer::where('user_id', auth()->user()->id)->first();

        if($logged_in_user_is_buyer != null)
        {
            $brands = Brand::where('buyer_id', $logged_in_user_is_buyer->id)->get();
        }
        else{
            $brands = Brand::all();
        }

        $products = Product::all();

        $product_types = ProductType::all();

        $merchandisers = Employee::whereHas('user.roles', function ($query) {
            $query->where('name', 'merchandiser');
        })->get();

        $departments = Department::all();

        $buyers = Buyer::all();

        return view('admin.query.query.create', compact('brands', 'buyers', 'logged_in_user_is_buyer', 'products', 'product_types', 'merchandisers', 'departments'));
    }

    public function edit(Request $request, $query_id)
    {
        if(!in_array('query.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.query.index');

        $query = Query::with('items', 'items.images', 'items.measurements')->find($query_id);

        if($query != null){

            $logged_in_user_is_buyer = Buyer::where('user_id', auth()->user()->id)->first();

            if($logged_in_user_is_buyer != null)
            {
                $brands = Brand::where('buyer_id', $logged_in_user_is_buyer->id)->get();
            }
            else{
                $brands = Brand::all();
            }

            $products = Product::all();

            $product_types = ProductType::all();

            $merchandisers = Employee::whereHas('user.roles', function ($query) {
                $query->where('name', 'merchandiser');
            })->get();

            $buyers = Buyer::all();

            return view('admin.query.query.edit', compact('query',  'buyers', 'brands', 'logged_in_user_is_buyer', 'products', 'product_types', 'merchandisers'));
        } 
        else{
            return redirect()->route('queries.index')->with('error', 'Query Not Found');
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'brand_id' => 'required',
            'employee_id' => 'nullable',
            'product_type_id' => 'required',
            'products' => 'required',
            'products.*.product_id' => 'required',
            'products.*.target_price' => 'required|numeric',
            'products.*.price_submission_date' => 'required',
            'products.*.sample_submission_date' => 'nullable',
            'products.*.product_model' => 'required',
            'products.*.details' => 'required',
            'products.*.approximate_quantity' => 'required|numeric',
            'products.*.query_images' => 'required',
            'products.*.query_measurements' => 'required',
        ]);

        try {
            DB::beginTransaction();
            
            $query = new Query();
            $query->query_date = Carbon::now();
            $query->brand_id = $request->brand_id;
            $query->employee_id = $request->employee_id;
            $query->product_type_id = $request->product_type_id;
            $query->save();

            if($request->employee_id != null)
            {
                $query_merchandiser = new QueryMerchandiser();
                $query_merchandiser->query_id = $query->id;
                $query_merchandiser->employee_id = $request->employee_id;
                $query_merchandiser->save();
            }

            $query->query_no = 'QRY' . str_pad($query->id, 5, '0', STR_PAD_LEFT);
            $query->save();


            foreach ($request->products as $product) {
                $query_item = new QueryItem();
                $query_item->query_id = $query->id;
                $query_item->product_id = $product['product_id'];
                $query_item->target_price = $product['target_price'];
                $query_item->price_submission_date = Carbon::createFromFormat('d/m/Y', $product['price_submission_date'])->toDateTimeString();
                $query_item->sample_submission_date = $product['sample_submission_date'] ? Carbon::createFromFormat('d/m/Y', $product['sample_submission_date'])->toDateTimeString() : null;
                $query_item->product_model = $product['product_model'];
                $query_item->details = $product['details'];
                $query_item->approximate_quantity = $product['approximate_quantity'];
                $query_item->save();


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

        $query = Query::with('items',  'items.images', 'items.measurements')->find($query_id);

        if($query != null){
            $factories = Factory::all();
            return view('admin.query.query.show', compact('query','factories'));
        }
        else{
            return redirect()->route('queries.index')->with('error', 'Query Not Found');
        }
    }

    public function getItems(Request $request)
    {
        $order = Order::with('items')->where('query_id', $request->query_id)->first();
        return response()->json($order->items);
    }

    public function update(Request $request, $query_id)
    {

        $request->validate([
            'brand_id' => 'required',
            'employee_id' => 'nullable',
            'product_type_id' => 'required',
            'products' => 'required',
            'products.*.product_id' => 'required',
            'products.*.target_price' => 'required|numeric',
            'products.*.price_submission_date' => 'required',
            'products.*.sample_submission_date' => 'nullable',
            'products.*.product_model' => 'required',
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
            $query->employee_id = $request->employee_id;
            $query->query_date = Carbon::now();
            $query->brand_id = $request->brand_id;
            $query->product_type_id = $request->product_type_id;
            $query->status = $old_query->status == "Sent For Approval" ? "Sent For Approval" : "Pending";
            $query->save();

            $query->query_no = 'QRY' . str_pad($query->id, 5, '0', STR_PAD_LEFT);
            $query->save();

            if($request->employee_id != null)
            {
                $query_merchandiser = new QueryMerchandiser();
                $query_merchandiser->query_id = $query->id;
                $query_merchandiser->employee_id = $request->employee_id;
                $query_merchandiser->save();
            }

            foreach ($request->products as $product) {
                $query_item = new QueryItem();
                $query_item->query_id = $query->id;
                $query_item->product_id = $product['product_id'];
                $query_item->target_price = $product['target_price'];
                $query_item->price_submission_date = Carbon::createFromFormat('d/m/Y', $product['price_submission_date'])->toDateTimeString();
                $query_item->sample_submission_date = $product['sample_submission_date'] ? Carbon::createFromFormat('d/m/Y', $product['sample_submission_date'])->toDateTimeString() : null;
                $query_item->product_model = $product['product_model'];
                $query_item->details = $product['details'];
                $query_item->approximate_quantity = $product['approximate_quantity'];
                $query_item->save();


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
                        $query_item->images()->sync($item->images->pluck('id'));
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
                        $query_item->measurements()->sync($item->measurements->pluck('id'));
                    }
                }
            }

            $old_query_messages = QueryChat::where('query_id', $old_query->id)->get();

            foreach ($old_query_messages as $message) {
                $message->query_id = $query->id;
                $message->save();
            }

            $orders = Order::where('query_id', $old_query->id)->get();

            foreach ($orders as $order) {
                $order->query_id = $query->id;
                $order->save();
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
                    $item->images()->detach();
                    $item->measurements()->detach();
                    $item->delete(); 
                });

                $query->children()->each(function ($child) {
                    $child->items()->each(function ($item) {
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

    public function changeStatus(Request $request)
    {
        if ($request->ajax()) {
            $query = Query::find($request->query_id);

            if ($query != null) {
                $query->status = $request->status;
                $query->rejection_note = $request->rejection_note;
                $query->save();

                return response()->json(['success' => 'Query Status Changed Successfully']);
            } else {
                return response()->json(['error' => 'Query Not Found']);
            }
        }
    }

    public function storeSpecificationSheet(Request $request)
    {
        if(!in_array('query.store_specification_sheet', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $validatedData = $request->validate([
            'query_item_id' => 'required|exists:query_items,id',
            'factory_id' => 'nullable|exists:factories,id',
            'date' => 'required',
            'approximate_delivery_date' => 'nullable',
            'express_courier' => 'nullable',
            'AWB' => 'nullable',
            'AWB_date' => 'nullable',
            'required_size' => 'nullable',
            'quantity' => 'nullable',
            'fitting' => 'nullable',
            'styling' => 'nullable',
            'required_fabric_composition' => 'nullable',
            'GSM' => 'nullable',
            'fabric_color' => 'nullable',
            'main_label' => 'nullable',
            'care_label' => 'nullable',
            'hang_tag' => 'nullable',
            'print_instructions' => 'nullable',
            'embroidery_instructions' => 'nullable',
            'button_type' => 'nullable',
            'button_size' => 'nullable',
            'button_color' => 'nullable',
            'button_thread' => 'nullable',
            'button_hole' => 'nullable',
            'zipper_type' => 'nullable',
            'zipper_size' => 'nullable',
            'zipper_color' => 'nullable',
            'zipper_tape' => 'nullable',
            'zipper_puller' => 'nullable',
            'other_instructions' => 'nullable',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d/m/Y', $validatedData['date'])->toDateTimeString();

        if(array_key_exists('approximate_delivery_date', $validatedData))
        {
            $validatedData['approximate_delivery_date'] = Carbon::createFromFormat('d/m/Y', $validatedData['approximate_delivery_date'])->toDateTimeString();
        }

        if(array_key_exists('AWB_date', $validatedData))
        {
            $validatedData['AWB_date'] = Carbon::createFromFormat('d/m/Y', $validatedData['AWB_date'])->toDateTimeString();
        }
        

        QueryItemSpecificationSheet::create($validatedData);

        return redirect()->back()->with('success', 'Specification sheet added successfully.');
    }

    public function updateSpecificationSheet(Request $request, QueryItemSpecificationSheet $specificationSheet)
    {
        $validatedData = $request->validate([
            'factory_id' => 'required|exists:factories,id',
            'date' => 'required',
            'approximate_delivery_date' => 'nullable',
            'express_courier' => 'nullable',
            'AWB' => 'nullable',
            'AWB_date' => 'nullable',
            'required_size' => 'nullable',
            'quantity' => 'nullable',
            'fitting' => 'nullable',
            'styling' => 'nullable',
            'required_fabric_composition' => 'nullable',
            'GSM' => 'nullable',
            'fabric_color' => 'nullable',
            'main_label' => 'nullable',
            'care_label' => 'nullable',
            'hang_tag' => 'nullable',
            'print_instructions' => 'nullable',
            'embroidery_instructions' => 'nullable',
            'button_type' => 'nullable',
            'button_size' => 'nullable',
            'button_color' => 'nullable',
            'button_thread' => 'nullable',
            'button_hole' => 'nullable',
            'zipper_type' => 'nullable',
            'zipper_size' => 'nullable',
            'zipper_color' => 'nullable',
            'zipper_tape' => 'nullable',
            'zipper_puller' => 'nullable',
            'other_instructions' => 'nullable',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d/m/Y', $validatedData['date'])->toDateTimeString();

        if(array_key_exists('approximate_delivery_date', $validatedData))
        {
            $validatedData['approximate_delivery_date'] = Carbon::createFromFormat('d/m/Y', $validatedData['approximate_delivery_date'])->toDateTimeString();
        }

        if(array_key_exists('AWB_date', $validatedData))
        {
            $validatedData['AWB_date'] = Carbon::createFromFormat('d/m/Y', $validatedData['AWB_date'])->toDateTimeString();
        }

        $specificationSheet->update($validatedData);

        return redirect()->back()->with('success', 'Specification sheet updated successfully.');
    }

    public function destroySpecificationSheet(QueryItemSpecificationSheet $specificationSheet)
    {
        $specificationSheet->delete();

        return redirect()->back()->with('success', 'Specification sheet deleted successfully.');
    }

    public function printSpecificationSheet(QueryItemSpecificationSheet $specificationSheet)
    {
        return view('admin.query.query.print', compact('specificationSheet'));
    }

    public function merchandiserAssignHistory(Request $request, $query_id)
    {
        if(!in_array('query.view_merchandiser_assign_history', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.query.index');

        $query = Query::find($query_id);

        if($query != null){
            if($request->ajax())
            {
                $queryMerchandisers = QueryMerchandiser::where('query_id', $query_id)->latest()->get();
                return DataTables::of($queryMerchandisers)
                    ->addColumn('merchandiser', function ($category) {
                        return $category->employee->user->username;
                    })
                    ->addColumn('query_no', function ($category) {
                        return $category->queryModel->query_no;
                    })
                    ->addColumn('date', function ($category) {
                        return Carbon::parse($category->created_at)->format('d/m/Y');
                    })
                    ->addIndexColumn()
                    ->make(true);
            }

            return view('admin.query.query.merchandiser_assign_history', compact('query_id', 'query'));
        }
        else{
            return redirect()->route('queries.index')->with('error', 'Query Not Found');
        }
    }

    public function assignMerchandiser(Request $request)
    {
        if ($request->ajax()) {
            $query = Query::find($request->query_id);

            if ($query != null) {
                $last_merchandiser = QueryMerchandiser::where('query_id', $query->id)->latest()->first();

                if($last_merchandiser->employee_id == $request->merchandiser_id)
                {
                    return response()->json(['error' => 'This Merchandiser Has Already Been Assigned to This Query']);
                }

                $query_merchandiser = new QueryMerchandiser();
                $query_merchandiser->query_id = $query->id;
                $query_merchandiser->employee_id = $request->merchandiser_id;
                $query_merchandiser->save();

                return response()->json(['success' => 'Merchandiser Assigned Successfully']);
            } else {
                return response()->json(['error' => 'Query Not Found']);
            }
        }
    }

    public function chat(Request $request, $query_id)
    {
        if(!in_array('query.chat', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.query.query.index');

        $query = Query::find($query_id);

        $chat_members = User::whereIn('id', QueryChat::where('query_id', $query_id)->pluck('user_id'))->get();

        if($query != null){
            return view('admin.query.query.chat', compact('query_id', 'query', 'chat_members'));
        }
        else{
            return redirect()->route('queries.index')->with('error', 'Query Not Found');
        }
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'query_id' => 'required|exists:queries,id',
            'message' => 'required_without:attachment|max:1000',
            'attachment' => 'nullable|file'
        ]);

        $messageData = [
            'query_id' => $request->query_id,
            'user_id' => auth()->id(),
            'message' => $request->message ?? '',
            'type' => $request->hasFile('attachment') ? 'attachment' : 'text',
            'status' => 'sent',
            'sent_at' => now()
        ];

        if ($request->hasFile('attachment')) {
            $file_name = $request->file('attachment')->hashName();
            $path = $request->file('attachment')->storeAs('public/query_chat_attachments', $file_name);
            $absolute_path = asset('public' . Storage::url($path));

            $messageData['attachment'] = $absolute_path;
            $messageData['message'] = $file_name;
        }

        $message = QueryChat::create($messageData);

        $message->load('user');

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    public function getMessages(Request $request)
    {
        $request->validate([
            'query_id' => 'required|exists:queries,id'
        ]);

        $messages = QueryChat::with('user')
            ->where('query_id', $request->query_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'messages' => $messages
        ]);
    }
    
    public function approve(Request $request)
    {
        if(!in_array('query.approve', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.orders.order.index');

        if($request->ajax()){

            $orders = Order::with('items')
                ->whereHas('queryModel', function ($query) {
                    $query->where('status', 'Sent For Approval');
                })
                ->latest()
                ->get();


            return DataTables::of($orders)
                ->addColumn('brand', function ($category) {
                    return $category->queryModel->brand->name;
                })
                ->addColumn('product_type', function ($category) {
                    return $category->queryModel->product_type->name;
                })
                ->addColumn('merchandiser', function ($category) {
                    return $category->queryModel->merchandiser ? $category->queryModel->merchandiser->user->username : '';
                })
                ->addColumn('date', function ($category) {
                    return Carbon::parse($category->order_date)->format('d/m/Y');
                })
                ->addColumn('query_no', function ($category) {
                    return $category->queryModel->query_no;
                })
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('order.index', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('orders.show', $category->id).'" class
                        ="dropdown-item"><i class="ri-eye-fill me-2"></i> View</a></li>';
                    }
                    // if(in_array('order.edit', session('user_permissions')))
                    // {
                    //     $edit_button .= '<li><a href="'.route('factories.edit', $category->id).'" class
                    //     ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    // }
                    
                    if(in_array('order.create', session('user_permissions')))
                    {
                        $edit_button .= '<li><button type="submit" class="dropdown-item" onclick="changeQueryStatus(' . $category->queryModel->id . ')">
                                            <i class="ri-checkbox-circle-fill me-2"></i> Change Status
                                        </button></li>';
                    }
                    
                    $edit_button .= '</ul></div>';
                    return $edit_button;
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.order.order.approve');
    }

}
