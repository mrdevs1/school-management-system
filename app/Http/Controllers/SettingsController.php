<?php
namespace App\Http\Controllers;
use App\Models\{Setting, Session};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value','key');
        $sessions = Session::orderByDesc('id')->get();
        $currentSession = Session::where('is_current',true)->first();
        return view('settings.index', compact('settings','sessions','currentSession'));
    }

    public function update(Request $request)
    {
        \Log::info("Settings update called", $request->all());
        $data = $request->validate([
            'school_name'     => 'required|string|max:255',
            'school_name_en'  => 'nullable|string',
            'school_address'  => 'nullable|string',
            'school_phone'    => 'nullable|string',
            'school_email'    => 'nullable|email',
            'school_website'  => 'nullable|string',
            'language'        => 'required|in:bn,en,both',
            'font_size'       => 'required|in:small,medium,large',
            'font_family'     => 'required|in:hind,noto,siyam,roboto',
            'currency_symbol' => 'nullable|string',
            'date_format'     => 'nullable|string',
            'result_system'   => 'required|in:gpa,percentage,both',
            'pass_marks'      => 'required|integer|min:1',
            'footer_text'     => 'nullable|string',
            'sms_enabled'     => 'nullable',
            'sms_api_key'     => 'nullable|string',
        ]);

        // Logo upload
        if ($request->hasFile('school_logo')) {
            $path = $request->file('school_logo')->store('settings','public');
            Setting::set('school_logo', $path);
        }

        $data['sms_enabled'] = $request->has('sms_enabled') ? '1' : '0';
        Setting::setMany($data);

        // Clear all caches
        Cache::flush();

        return back()->with('success','সেটিংস সফলভাবে সংরক্ষণ হয়েছে!');
    }

    public function storeSession(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);
        Session::create($data);
        return back()->with('success','শিক্ষাবর্ষ যোগ হয়েছে!');
    }

    public function setCurrentSession(Session $session)
    {
        Session::where('is_current',true)->update(['is_current'=>false]);
        $session->update(['is_current'=>true]);
        return back()->with('success','চলমান সেশন পরিবর্তন হয়েছে!');
    }
}
