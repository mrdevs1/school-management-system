@extends('layouts.app')
@section('page-title', 'ছাত্র Import')
@section('breadcrumb', 'Students / Import')
@section('content')

@if(session('import_errors'))
<div class="alert alert-warning">
    <div>
        <div style="font-weight:600; margin-bottom:6px;">⚠️ কিছু error হয়েছে:</div>
        @foreach(session('import_errors') as $err)
        <div style="font-size:12.5px;">{{ $err }}</div>
        @endforeach
    </div>
</div>
@endif

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    <!-- Instructions -->
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-info-circle-fill"></i> নির্দেশনা</div></div>
        <div class="card-body">
            <div style="font-size:13.5px; line-height:1.8; color:var(--text-muted);">
                <p style="margin-bottom:12px; color:var(--text); font-weight:600;">CSV বা Excel ফাইল দিয়ে একসাথে অনেক ছাত্র ভর্তি করুন।</p>
                <p>১. নমুনা ফাইল ডাউনলোড করুন</p>
                <p>২. Excel বা Google Sheets এ খুলুন</p>
                <p>৩. ছাত্রদের তথ্য পূরণ করুন</p>
                <p>৪. CSV বা Excel হিসেবে save করুন</p>
                <p>৫. এখানে আপলোড করুন</p>
            </div>
            <div style="margin-top:16px; padding:12px 16px; background:var(--green-light); border-radius:var(--radius-sm); border-left:3px solid var(--green);">
                <div style="font-size:13px; font-weight:600; color:var(--green); margin-bottom:6px;">Column গুলো হবে:</div>
                <div style="font-size:12px; color:var(--text-muted);">
                    নাম | ইংরেজি নাম | জন্ম তারিখ | লিঙ্গ | ধর্ম | রক্তের গ্রুপ | ঠিকানা | পিতার নাম | মাতার নাম | ফোন | রোল
                </div>
            </div>
            <a href="{{ route('students.sample') }}" class="btn btn-outline" style="width:100%; justify-content:center; margin-top:14px;">
                <i class="bi bi-download"></i> নমুনা CSV ফাইল ডাউনলোড
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-upload"></i> ফাইল আপলোড</div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('students.import.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">CSV/Excel ফাইল <span style="color:red">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                        <p style="font-size:11.5px; color:var(--text-muted); margin-top:4px;">সর্বোচ্চ ৫ MB, .csv বা .xlsx</p>
                    </div>
                    <div>
                        <label class="form-label">শ্রেণী <span style="color:red">*</span></label>
                        <select name="class_id" class="form-select" required id="classSelect">
                            <option value="">বেছে নিন</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">বিভাগ <span style="color:red">*</span></label>
                        <select name="section_id" class="form-select" required id="sectionSelect">
                            <option value="">প্রথমে শ্রেণী বেছে নিন</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">শিক্ষাবর্ষ <span style="color:red">*</span></label>
                        <select name="session_id" class="form-select" required>
                            @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ $session->is_current?'selected':'' }}>
                                {{ $session->name }} {{ $session->is_current?'(চলমান)':'' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Upload area -->
                    <div id="uploadArea" style="border:2px dashed var(--border); border-radius:var(--radius-sm); padding:24px; text-align:center; cursor:pointer; transition:all 0.2s;" onclick="document.querySelector('input[name=file]').click()">
                        <i class="bi bi-cloud-upload" style="font-size:32px; color:var(--text-muted); display:block; margin-bottom:8px;"></i>
                        <div style="font-size:13.5px; color:var(--text-muted);">ফাইল drag করুন বা ক্লিক করুন</div>
                        <div id="fileName" style="font-size:12px; color:var(--green); margin-top:6px; font-weight:600;"></div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="bi bi-upload"></i> Import করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelector('input[name=file]').addEventListener('change', function() {
    const name = this.files[0]?.name;
    if (name) {
        document.getElementById('fileName').textContent = '✓ ' + name;
        document.getElementById('uploadArea').style.borderColor = 'var(--green)';
        document.getElementById('uploadArea').style.background = 'var(--green-light)';
    }
});

document.getElementById('classSelect').addEventListener('change', function() {
    const classId = this.value;
    const sectionSelect = document.getElementById('sectionSelect');
    if (!classId) { sectionSelect.innerHTML = '<option value="">প্রথমে শ্রেণী বেছে নিন</option>'; return; }
    sectionSelect.innerHTML = '<option value="">লোড হচ্ছে...</option>';
    fetch(`/api/sections?class_id=${classId}`)
        .then(r => r.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">বিভাগ বেছে নিন</option>';
            data.forEach(s => sectionSelect.innerHTML += `<option value="${s.id}">${s.name}</option>`);
        });
});
</script>
@endpush
