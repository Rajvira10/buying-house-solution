<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{
    public function index(Request $request)
    {
        if(!in_array('expense_categories.index', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }

        $request->session()->now('view_name', 'admin.expenses.expense_categories.index');
        
        if($request->ajax()){

            $expense_categories = ExpenseCategory::select(
                'id',
                'name'
            )
            ->orderBy('created_at', 'desc')
            ->get();

            return DataTables::of($expense_categories)
                ->addColumn('total_expense', function ($category) {
                    return number_format($category->expenses->sum('amount'),2);
                })
                ->addColumn('action', function ($category) {
                    $edit_button = '<div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">';
                    if(in_array('expense_categories.edit', session('user_permissions')))
                    {
                        $edit_button .= '<li><a href="'.route('expense_categories.edit', $category->id).'" class
                        ="dropdown-item"><i class="ri-pencil-line me-2"></i> Edit</a></li>';
                    }
                    if(in_array('expense_categories.delete', session('user_permissions')))
                    {
                        $edit_button .= '<li>
                                            <button type="submit" class="dropdown-item delete-item-btn" onclick="deleteExpenseCategory(' . $category->id . ')">
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

        return view('admin.expenses.expense_category.index');
    }

    public function create(Request $request)
    {
        if(!in_array('expense_categories.create', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        $request->session()->now('view_name', 'admin.expenses.expense_categories.index');

        return view('admin.expenses.expense_category.create');
    }

    public function edit(Request $request, $expense_category_id)
    {
        if(!in_array('expense_categories.edit', session('user_permissions')))
        {
            return redirect()->route('admin-dashboard')->with('error', 'You are not authorized');
        }
        
        $request->session()->now('view_name', 'admin.expenses.expense_categories.index');

        $expense_category = ExpenseCategory::find($expense_category_id);

        if($expense_category != null){

            return view('admin.expenses.expense_category.edit', compact('expense_category'));
        }
        else{
            return redirect()->route('expense_categories.index')->with('error', 'Expense Category Not Found');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:expense_categories,name',
        ]);

        $expense_category = new ExpenseCategory();

        $expense_category->name = $request->name;

        $expense_category->save();

        return redirect()->route('expense_categories.index')->with('success', 'Expense Category Created Successfully');

    }

    public function update(Request $request, $expense_category_id)
    {
        $request->validate([
            'name' => 'required|unique:expense_categories,name,'.$expense_category_id
        ]);
        
        $expense_category = ExpenseCategory::find($expense_category_id);

        if ($expense_category != null) {

            $expense_category->name = $request->name;

            $expense_category->save();

            return redirect()->route('expense_categories.index')->with('success', 'Expense Category Updated Successfully');
        }
        else{
            return redirect()->route('expense_categories.index')->with('error', 'Expense Category Not Found');
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax())
        {
            $expense_category = ExpenseCategory::find($request->expense_category_id);

            if($expense_category != null)
            {
                $expenses = $expense_category->expenses;
                if($expenses->count() > 0)
                {
                    return response()->json(['error' => 'This Expense Category has Expenses. Please Delete Expenses First']);
                }

                $expense_category->delete();
                return response()->json(['success' => 'Expense Category Deleted Successfully']);
            }
            else{
                return response()->json(['error' => 'Expense Category Not Found']);
            }
        }
    }

}
