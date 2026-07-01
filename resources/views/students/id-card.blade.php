<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>আইডি কার্ড - {{ $student->student_id }}</title>
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Hind Siliguri',sans-serif; background:#f5f5f5; padding:30px; display:flex; justify-content:center; flex-direction:column; align-items:center; gap:20px; }
.id-card { width:340px; border-radius:14px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.15); }
.card-header { background:linear-gradient(135deg,#0a2e22,#0f7a55); padding:16px; display:flex; align-items:center; gap:12px; }
.school-logo { width:48px; height:48px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
.school-info .name { color:#fff; font-weight:700; font-size:13px; line-height:1.3; }
.school-info .tagline { color:rgba(255,255,255,0.7); font-size:10px; }
.id-badge { margin-left:auto; background:rgba(255,255,255,0.15); padding:4px 10px; border-radius:20px; font-size:10px; color:#fff; font-weight:600; text-align:center; }
.card-body { background:#fff; padding:16px; display:flex; gap:14px; }
.photo { width:80px; height:95px; border-radius:8px; border:2px solid #e0ebe7; overflow:hidden; flex-shrink:0; background:#f0faf6; display:flex; align-items:center; justify-content:center; }
.photo img { width:100%; height:100%; object-fit:cover; }
.photo-placeholder { font-size:36px; }
.info .student-name { font-size:15px; font-weight:700; color:#0a2e22; margin-bottom:8px; }
.info-row { display:flex; gap:6px; font-size:11px; margin-bottom:4px; }
.info-label { color:#888; min-width:55px; }
.info-value { font-weight:600; color:#1a2e28; }
.blood { color:#dc2626; font-weight:700; }
.card-footer { background:#f0faf6; padding:10px 16px; display:flex; justify-content:space-between; align-items:center; border-top:1px solid #e0ebe7; }
.barcode { font-size:10px; color:#888; font-family:monospace; }
.session { font-size:10px; font-weight:600; color:#0f7a55; background:#dcfce7; padding:3px 8px; border-radius:20px; }
.print-btn { padding:10px 30px; background:#0f7a55; color:#fff; border:none; border-radius:6px; font-size:14px; font-family:'Hind Siliguri',sans-serif; font-weight:600; cursor:pointer; }
@media print { body{background:none;padding:0;} .id-card{box-shadow:none;} .print-btn{display:none;} }
</style>
</head>
<body>
<div class="id-card">
    <div class="card-header">
        @if(setting('school_logo'))
        <img src="{{ asset('storage/'.setting('school_logo')) }}" style="width:48px; height:48px; border-radius:50%; object-fit:cover;">
        @else
        <div class="school-logo">🕌</div>
        @endif
        <div class="school-info">
            <div class="name">{{ setting('school_name','বিদ্যাপীঠ') }}</div>
            <div class="tagline">{{ setting('school_address','') }}</div>
        </div>
        <div class="id-badge">STUDENT ID<br>{{ $student->student_id }}</div>
    </div>

    <div class="card-body">
        <div class="photo">
            @if($student->photo)
            <img src="{{ asset('storage/'.$student->photo) }}">
            @else
            <div class="photo-placeholder">👤</div>
            @endif
        </div>
        <div class="info">
            <div class="student-name">{{ $student->name }}</div>
            <div class="info-row"><span class="info-label">পিতা:</span><span class="info-value">{{ $student->father_name }}</span></div>
            <div class="info-row"><span class="info-label">মাতা:</span><span class="info-value">{{ $student->mother_name }}</span></div>
            <div class="info-row"><span class="info-label">শ্রেণী:</span><span class="info-value">{{ $student->studentClass->name??'—' }} — {{ $student->section->name??'' }}</span></div>
            <div class="info-row"><span class="info-label">জন্ম:</span><span class="info-value">{{ $student->date_of_birth->format('d/m/Y') }}</span></div>
            <div class="info-row"><span class="info-label">ফোন:</span><span class="info-value">{{ $student->guardian_phone }}</span></div>
            @if($student->blood_group)
            <div class="info-row"><span class="info-label">রক্ত:</span><span class="info-value blood">{{ $student->blood_group }}</span></div>
            @endif
        </div>
    </div>

    <div class="card-footer">
        <span class="barcode">{{ $student->student_id }} | {{ now()->year }}</span>
        <span class="session">সেশন: {{ $currentSession->name??'—' }}</span>
    </div>
</div>

<button class="print-btn" onclick="window.print()">🖨️ প্রিন্ট করুন</button>
</body>
</html>
