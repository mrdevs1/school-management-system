<?php
namespace App\Http\Controllers;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::orderBy('order')->get();
        $currentSession = \App\Models\Session::where('is_current', true)->first();
        return view('designations.index', compact('designations', 'currentSession'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:designations']);
        $maxOrder = Designation::max('order') ?? 0;
        Designation::create(['name' => $request->name, 'order' => $maxOrder + 1]);
        return back()->with('success', 'Designation added successfully!');
    }

    public function update(Request $request, Designation $designation)
    {
        $request->validate(['name' => 'required|string|max:255|unique:designations,name,'.$designation->id]);
        $designation->update(['name' => $request->name]);
        return back()->with('success', 'Updated successfully!');
    }

    public function toggle(Designation $designation)
    {
        $designation->update(['is_active' => !$designation->is_active]);
        return back()->with('success', 'Status updated!');
    }

    public function destroy(Designation $designation)
    {
        if ($designation->teachers()->count() > 0) {
            return back()->with('error', 'This designation is assigned to teachers, cannot delete!');
        }
        $designation->delete();
        return back()->with('success', 'Deleted successfully!');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $i => $id) {
            Designation::where('id', $id)->update(['order' => $i + 1]);
        }
        return response()->json(['success' => true]);
    }
}
