<?php
namespace App\Http\Controllers;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index() {
        $notices = Notice::with('creator')->orderByDesc('publish_date')->paginate(20);
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('notices.index', compact('notices','currentSession'));
    }
    public function create() {
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('notices.form', compact('currentSession'));
    }
    public function store(Request $request) {
        $data = $request->validate(['title'=>'required|string|max:255','content'=>'required|string','audience'=>'required|in:all,students,teachers,parents','publish_date'=>'required|date','expire_date'=>'nullable|date']);
        $data['created_by'] = auth()->id();
        Notice::create($data);
        return redirect()->route('notices.index')->with('success','নোটিশ প্রকাশিত হয়েছে!');
    }
    public function edit(Notice $notice) {
        $currentSession = \App\Models\Session::where('is_current',true)->first();
        return view('notices.form', compact('notice','currentSession'));
    }
    public function update(Request $request, Notice $notice) {
        $notice->update($request->validate(['title'=>'required|string','content'=>'required|string','audience'=>'required|in:all,students,teachers,parents','publish_date'=>'required|date','expire_date'=>'nullable|date']));
        return redirect()->route('notices.index')->with('success','নোটিশ আপডেট হয়েছে!');
    }
    public function show(Notice $notice) { return $this->index(); }
    public function destroy(Notice $notice) {
        $notice->delete();
        return redirect()->route('notices.index')->with('success','মুছে ফেলা হয়েছে!');
    }
}
