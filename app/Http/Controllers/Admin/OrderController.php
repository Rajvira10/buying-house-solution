<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Imports\OrdersImport;
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
        
        if($request->ajax()){

            $orders = Order::with('items', 'items.colors')
            ->latest()
            ->get();

            return DataTables::of($orders)
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

        return view('admin.order.order.index');
    }

    public function show(Request $request)
    {
        if(!in_array('order.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.order.order.index');
        
        $order = Order::with('items', 'items.colors')
        ->find($request->order_id);

        return view('admin.order.order.show', compact('order'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'orderFile' => 'required|file|mimes:xlsx,xls,csv',
            'query_id' => 'required|integer',
        ]);

        try {
            $queryId = $request->input('query_id');

            Excel::import(new OrdersImport($queryId), $request->file('orderFile'));

            return response()->json(['success' => true, 'message' => 'Orders imported successfully!']);
        } catch (\Throwable $th) {
            dd($th->getMessage());
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
}
