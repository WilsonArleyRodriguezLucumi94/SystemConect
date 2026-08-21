<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    //

    public function index()
    {
        $expenses = Expense::latest('expense_date')->paginate(15);
        $totalExpenses = Expense::sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpenses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description'  => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'category'     => 'nullable|string|max:100',
            'expense_date' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Gasto registrado correctamente.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Gasto eliminado.');
    }
}
