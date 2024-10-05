<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Tna;
use App\Models\Order;
use App\Models\Query;
use App\Models\OrderTna;
use Illuminate\Http\Request;
use App\Imports\OrdersImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('order.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.orders.order.index');
        
        $tnas = Tna::all();

        if($request->ajax()){

            $orders = Order::with('items')
                ->whereHas('queryModel', function ($query) {
                    $query->where('status', 'Approved');
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
                    if(in_array('order.add_tna', session('user_permissions')) && $category->tnas->count() == 0)
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item" onclick="addTna(' . $category->id . ')">
                                                <i class="ri-add-fill align-bottom me-2"></i> Add TNA
                                            </button>
                                        </li>';
                    }
                    
                    if(in_array('order.view_tna', session('user_permissions')) && $category->tnas->count() > 0)
                    {
                        $edit_button .= '<li>
                                            <a href="'.route('orders.show_tna', $category->id).'" class="dropdown-item">
                                                <i class="ri-eye-fill align-bottom me-2"></i> View TNA
                                            </a>
                                        </li>';
                    }

                    if(in_array('order.print_tna', session('user_permissions')) && $category->tnas->count() > 0)
                    {
                        $edit_button .= '<li>
                                            <a href="'.route('orders.print_tna', $category->id).'" class="dropdown-item">
                                                <i class="ri-printer-line align-bottom me-2"></i> Print TNA
                                            </a>
                                        </li>';
                    }
                    
                    if(in_array('order.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteOrder(' . $category->id . ')">
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

        return view('admin.order.order.index', compact('tnas'));
    }

    public function show(Request $request)
    {
        if(!in_array('order.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.orders.order.index');
        
        $order = Order::with('items')->find($request->order_id);

        return view('admin.order.order.show', compact('order'));
    }
    
   public function store(Request $request)
    {

        $request->validate([
            'query_id' => 'required|integer',
            'sizes' => 'nullable',
            'product_code.*' => 'nullable',
            'product_function.*' => 'nullable',
            'product_model.*' => 'nullable',
            'product_shipment_date.*' => 'nullable',
            'product_fit.*' => 'nullable',
            'product_details.*' => 'nullable',
            'product_fabric.*' => 'nullable',
            'product_weight.*' => 'nullable',
            'product_master_box.*' => 'nullable',
            'colors' => 'required',
            'colors.*' => 'required',   
            'product_image.*' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            
        $order = Order::create([
            'query_id' => $request->input('query_id'),
            'order_date' => now(),
        ]);

        $product_type = Query::find($request->query_id)->product_type->name;

        $total_quantities = 0;

        foreach ($request->product_fabric as $index => $product_code) {
            $total_color_quantities = 0;
            foreach($request->colors[$index] as $color_quantity){
                foreach($color_quantity['quantities'] as $quantity){
                    $total_color_quantities += $quantity;
                }
            }

            $total_quantities += $total_color_quantities;
            
            $image_name = '';
            
            if($request->file('product_image')[$index] ?? false){
                $image = $request->file('product_image')[$index];
                $image_name = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/products'), $image_name);
            }

            $orderItem = $order->items()->create([
                'type' => $product_type,
                'image' => $image_name ? 'public/uploads/products/' . $image_name : '',
                'code' => $request->product_code[$index] ?? '',
                'function' => $request->product_function[$index] ?? '',
                'model' => $request->product_model[$index] ?? '',
                'details' => $request->product_details[$index] ?? '',
                'fit' => $request->product_fit[$index],
                'fabric' => $request->product_fabric[$index],
                'weight' => $request->product_weight[$index],
                'sizes' => json_encode($request->sizes),
                'master_box' => $request->product_master_box[$index] ?? 0,
                'shipment_date' => $request->product_shipment_date[$index] ?? now(),
                'colors' => json_encode($request->colors[$index]),
                'pieces' => $total_color_quantities,
            ]);
        }

        $order->update([
            'order_no' => 'ORD-' . $order->id,
            'total_quantity' => $total_quantities,
        ]);

        $query = Query::find($request->query_id);
        $query->update([
            'status' => 'Sent For Approval',
        ]);

        DB::commit();
        

        return redirect()->route('queries.index')->with('success', 'Query Has Been Sent For Approval');

        } catch (\Throwable $th) {
            dd($th->getMessage());
            DB::rollBack();
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()){
            try {
                $order = Order::find($request->order_id);
                $order->delete();

                return response()->json(['success' => 'Order Deleted Successfully']);
            } catch (\Throwable $th) {
                dd($th->getMessage());
                return response()->json(['error' => 'Something went wrong!']);
            }
        }
    }

    public function storeTna(Request $request)
    {
        $order_id = $request->input('order_id');

        foreach ($request->input('plan_date') as $tna_id => $plan_date) {
            OrderTna::create([
                'order_id' => $order_id,
                'tna_id' => $tna_id,
                'plan_date' => $plan_date,
                'actual_date' => $request->input('actual_date')[$tna_id],
                'remarks' => $request->input('remarks')[$tna_id],
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function printTna(Request $request, $id)
    {
        $order = Order::with('tnas', 'tnas.tna')->find($id);

        return view('admin.order.order.print_tna', compact('order'));
    }

    public function showTna(Request $request, $id)
    {
        $request->session()->now('view_name', 'admin.orders.order.index');

        $order = Order::with('tnas', 'tnas.tna')->find($id);

        return view('admin.order.order.show_tna', compact('order'));
    }

    public function updateTna(Request $request)
    {
        $order_id = $request->input('order_id');
        try {
            foreach ($request->input('plan_date') as $index => $plan_date) {
                $orderTna = OrderTna::where('order_id', $order_id)
                ->where('tna_id', $request->tna_id[$index])
                ->first();

                $orderTna->update([
                    'plan_date' => $plan_date,
                    'actual_date' => $request->input('actual_date')[$index],
                    'remarks' => $request->input('remarks')[$index],
                ]);
            }

            return back()->with('success', 'TNA Updated Successfully');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
       
    }

    public function destroyTna(Request $request)
    {
        if($request->ajax()){
            try {
                $orderTnas = OrderTna::where('order_id', $request->order_id)
                ->get();

                $orderTnas->each->delete();
                
                return response()->json(['success' => 'TNA Deleted Successfully']);
            } catch (\Throwable $th) {
                dd($th->getMessage());
                return response()->json(['error' => 'Something went wrong!']);
            }
        }
    }
}
