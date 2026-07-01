@extends('layouts.app')
@section('page-title', 'সেটিংস')
@section('breadcrumb', 'Home / Settings')
@section('content')

<form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" id="mainSettingsForm">
@csrf

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-building"></i> প্রতিষ্ঠানের তথ্য</div>
        </div>
        <div class="card-body">
            <div class="form-grid" style="gap:14px;">
                <div style="text-align:center;">
                    <div id="logoPreview" style="width:90px; height:90px; border-radius:50%; border:3px dashed var(--border); margin:0 auto 10px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:var(--bg); cursor:pointer;" onclick="document.getElementById('logoInput').click()">
                        @if($settings->get('school_logo'))
                        <img src="{{ asset('storage/'.$settings->get('school_logo')) }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                        <span style="font-size:36px;">🕌</span>
                        @endif
                    </div>
                    <input type="file" id="logoInput" name="school_logo" accept="image/*" style="display:none">
                    <p style="font-size:11.5px; color:var(--text-muted);">লোগো পরিবর্তন করতে ক্লিক করুন</p>
                </div>
                <div>
                    <label class="form-label">প্রতিষ্ঠানের নাম (বাংলা) <span style="color:red">*</span></label>
                    <input type="text" name="school_name" class="form-control" required value="{{ $settings->get('school_name','') }}">
                </div>
                <div>
                    <label class="form-label">প্রতিষ্ঠানের নাম (English)</label>
                    <input type="text" name="school_name_en" class="form-control" value="{{ $settings->get('school_name_en','') }}">
                </div>
                <div>
                    <label class="form-label">ঠিকানা</label>
                    <textarea name="school_address" class="form-control" rows="2">{{ $settings->get('school_address','') }}</textarea>
                </div>
                <div>
                    <label class="form-label">ফোন নম্বর</label>
                    <input type="text" name="school_phone" class="form-control" value="{{ $settings->get('school_phone','') }}" placeholder="01XXXXXXXXX">
                </div>
                <div>
                    <label class="form-label">ইমেইল</label>
                    <input type="email" name="school_email" class="form-control" value="{{ $settings->get('school_email','') }}">
                </div>
                <div>
                    <label class="form-label">ওয়েবসাইট</label>
                    <input type="text" name="school_website" class="form-control" value="{{ $settings->get('school_website','') }}" placeholder="https://school.com">
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex; flex-direction:column; gap:20px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-palette-fill"></i> ভাষা ও UI সেটিংস</div>
            </div>
            <div class="card-body">
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">ভাষা</label>
                        <select name="language" class="form-select">
                            <option value="bn"   {{ $settings->get('language','bn')==='bn'  ?'selected':'' }}>🇧🇩 বাংলা</option>
                            <option value="en"   {{ $settings->get('language','bn')==='en'  ?'selected':'' }}>🇬🇧 English</option>
                            <option value="both" {{ $settings->get('language','bn')==='both'?'selected':'' }}>বাংলা + English</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">ফন্ট সাইজ</label>
                        <select name="font_size" class="form-select">
                            <option value="small"  {{ $settings->get('font_size','medium')==='small' ?'selected':'' }}>ছোট</option>
                            <option value="medium" {{ $settings->get('font_size','medium')==='medium'?'selected':'' }}>মাঝারি</option>
                            <option value="large"  {{ $settings->get('font_size','medium')==='large' ?'selected':'' }}>বড়</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">ফন্ট পরিবার</label>
                        <select name="font_family" class="form-select">
                            <option value="hind"   {{ $settings->get('font_family','hind')==='hind'  ?'selected':'' }}>Hind Siliguri</option>
                            <option value="noto"   {{ $settings->get('font_family','hind')==='noto'  ?'selected':'' }}>Noto Sans Bengali</option>
                            <option value="siyam"  {{ $settings->get('font_family','hind')==='siyam' ?'selected':'' }}>SiyamRupali</option>
                            <option value="roboto" {{ $settings->get('font_family','hind')==='roboto'?'selected':'' }}>Roboto</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">মুদ্রা চিহ্ন</label>
                        <select name="currency_symbol" class="form-select">
                            <option value="৳" {{ $settings->get('currency_symbol','৳')==='৳'?'selected':'' }}>৳ (টাকা)</option>
                            <option value="BDT" {{ $settings->get('currency_symbol','৳')==='BDT'?'selected':'' }}>BDT</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">তারিখ ফরম্যাট</label>
                        <select name="date_format" class="form-select">
                            <option value="d/m/Y" {{ $settings->get('date_format','d/m/Y')==='d/m/Y'?'selected':'' }}>{{ date('d/m/Y') }}</option>
                            <option value="d-m-Y" {{ $settings->get('date_format','d/m/Y')==='d-m-Y'?'selected':'' }}>{{ date('d-m-Y') }}</option>
                            <option value="Y-m-d" {{ $settings->get('date_format','d/m/Y')==='Y-m-d'?'selected':'' }}>{{ date('Y-m-d') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-award-fill"></i> একাডেমিক সেটিংস</div>
            </div>
            <div class="card-body">
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">ফলাফল পদ্ধতি</label>
                        <select name="result_system" class="form-select">
                            <option value="gpa"        {{ $settings->get('result_system','gpa')==='gpa'       ?'selected':'' }}>GPA</option>
                            <option value="percentage" {{ $settings->get('result_system','gpa')==='percentage'?'selected':'' }}>শতকরা</option>
                            <option value="both"       {{ $settings->get('result_system','gpa')==='both'      ?'selected':'' }}>উভয়</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">পাস নম্বর</label>
                        <input type="number" name="pass_marks" class="form-control" value="{{ $settings->get('pass_marks','33') }}" min="1" max="100">
                    </div>
                    <div>
                        <label class="form-label">রশিদ ফুটার</label>
                        <input type="text" name="footer_text" class="form-control" value="{{ $settings->get('footer_text','') }}">
                    </div>
                    <div>
                        <label class="form-label">SMS</label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; padding:9px 12px; border:1.5px solid var(--border); border-radius:var(--radius-sm);">
                            <input type="checkbox" name="sms_enabled" value="1" {{ $settings->get('sms_enabled','0')==='1'?'checked':'' }} style="width:16px; height:16px; accent-color:var(--green);">
                            <span style="font-size:13px;">SMS নোটিফিকেশন চালু করুন</span>
                        </label>
                    </div>
                    <div>
                        <label class="form-label">SMS API Key</label>
                        <input type="text" name="sms_api_key" class="form-control" value="{{ $settings->get('sms_api_key','') }}" placeholder="API key">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display:flex; justify-content:flex-end; gap:12px; margin-bottom:20px;">
    <button type="submit" class="btn btn-primary" style="padding:11px 32px;">
        <i class="bi bi-save-fill"></i> সব সেটিংস সংরক্ষণ করুন
    </button>
</div>

</form>

{{-- Academic session management section --}}
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-calendar-range"></i> শিক্ষাবর্ষ ব্যবস্থাপনা</div>
    </div>
    <div class="card-body">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
                <p style="font-size:13px; font-weight:600; margin-bottom:12px;">নতুন শিক্ষাবর্ষ যোগ করুন:</p>
                <form method="POST" action="{{ route('settings.store-session') }}">
                    @csrf
                    <div class="form-grid" style="gap:10px;">
                        <div>
                            <label class="form-label">শিক্ষাবর্ষের নাম</label>
                            <input type="text" name="name" class="form-control" placeholder="যেমন: 2025-2026" required>
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            <div>
                                <label class="form-label">শুরু</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div>
                                <label class="form-label">শেষ</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus"></i> যোগ করুন
                        </button>
                    </div>
                </form>
            </div>
            <div>
                <p style="font-size:13px; font-weight:600; margin-bottom:12px;">বিদ্যমান শিক্ষাবর্ষ:</p>
                @foreach($sessions as $session)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 12px; background:var(--bg); border-radius:var(--radius-sm); margin-bottom:8px;">
                    <div>
                        <span style="font-weight:600; font-size:13.5px;">{{ $session->name }}</span>
                        @if($session->is_current)
                        <span class="badge badge-green" style="margin-left:8px;">চলমান</span>
                        @endif
                    </div>
                    @if(!$session->is_current)
                    <form method="POST" action="{{ route('settings.set-current-session',$session) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">চলমান করুন</button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('logoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById('logoPreview').innerHTML =
            '<img src="' + ev.target.result + '" style="width:100%; height:100%; object-fit:cover;">';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
