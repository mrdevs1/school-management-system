<?php
namespace App\Http\Controllers;
use App\Models\FeeCategory;
use Illuminate\Http\Request;

class FeeCategoryController extends Controller
{
    public function index() {
        $categories = FeeCategory::all();
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('fee-categories.index', compact('categories','currentSession'));
    }
    public function create() { return $this->index(); }
    public function show(FeeCategory $feeCategory) { return $this->index(); }
    public function edit(FeeCategory $feeCategory) { return $this->index(); }
    public function store(Request $request) {
        FeeCategory::create($request->validate([
            'name'        => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'frequency'   => 'required|in:monthly,yearly,once',
            'description' => 'nullable|string',
        ]));
        return back()->with('success', 'Fee category added successfully!');
    }
    public function update(Request $request, FeeCategory $feeCategory) {
        $feeCategory->update($request->validate([
            'name'      => 'required|string',
            'amount'    => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,yearly,once',
        ]));
        return back()->with('success', 'Updated successfully!');
    }
    public function destroy(FeeCategory $feeCategory) {
        $feeCategory->delete();
        return back()->with('success', 'Deleted successfully!');
    }
}
