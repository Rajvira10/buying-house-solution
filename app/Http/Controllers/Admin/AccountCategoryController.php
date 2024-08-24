<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\AccountCategory;

class AccountCategoryController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('account_category.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.accounts.account_categories.index');

        try {
            if($request->ajax()){

                $account_categories = AccountCategory::select(
                    'id',
                    'name'
                )
                ->where('name', '!=', 'Client Account')
                ->where('name', '!=', 'Supplier Account')
                ->get();

                return DataTables::of($account_categories)
                    ->addColumn('action', function ($category) {

                        if(in_array('account_category.edit', session('user_permissions')))
                        {
                            $edit_button = '<a href="' . route('account_categories.edit', $category->id) . '">
                                                <button class="btn btn-sm btn-success edit-item-btn">
                                                    Edit
                                                </button>
                                            </a>';
                            return $edit_button;
                        }
                        else
                        {
                            return '';
                        }
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
                }

            return view('admin.accounts.account_category.index');

        } catch (\Throwable $th) {

            return redirect()->route('account_categories.index')->with('error', 'Something Went Wrong');
            
        }
    }

    public function create(Request $request)
    {
        if(!in_array('account_category.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.accounts.account_categories.index');

        return view('admin.accounts.account_category.create');
    }

    public function edit(Request $request, $id)
    {
        if(!in_array('account_category.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.accounts.account_categories.index');
        
        try {
            $account_category = AccountCategory::where('id', '=', $id)->select(
                'id',
                'name'
            )->get();

            if(!empty($account_category)){
                $account_category = $account_category[0];

                return view('admin.accounts.account_category.edit', compact('account_category'));
            }
            else{
                return redirect()->route('account_categories.index')->with('error', 'Account Category Not Found');
            }
        } catch (\Throwable $th) {
            return redirect()->route('account_categories.index')->with('error', 'Something Went Wrong');
        }

    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:account_categories,name',
        ]);

        try {
            $account_category = new AccountCategory();

            $account_category->name = $request->name;

            $account_category->save();

            return redirect()->route('account_categories.index')->with('success', 'Account Category Created Successfully');

        } catch (\Throwable $th) {
            return redirect()->route('account_categories.index')->with('error', 'Something Went Wrong');
        }
        

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:account_categories,name,' .$id
        ]);

        try {
            $account_category = AccountCategory::where('id', '=', $id)->select(
                'id',
                'name'
            )->get();

            if (!empty($account_category)) {

                $account_category = $account_category[0];

                $account_category->name = $request->name;

                $account_category->save();

                return redirect()->route('account_categories.index')->with('success', 'Account Category Updated Successfully');
            }
            else{
                return redirect()->route('account_categories.index')->with('error', 'Account Category Not Found');
            }
        } catch (\Throwable $th) {
            return redirect()->route('account_categories.index')->with('error', 'Something Went Wrong');
        }
    }
}
