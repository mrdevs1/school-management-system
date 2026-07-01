<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>মার্কশিট - {{ $student->student_id }}</title>
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Hind Siliguri',sans-serif; background:#f5f5f5; padding:20px; }
.page { background:#fff; max-width:700px; margin:0 auto; padding:30px; border-radius:8px; box-shadow:0 2px 15px rgba(0,0,0,0.1); }
.header { text-align:center; border-bottom:3px double #0f7a55; padding-bottom:14px; margin-bottom:16px; }
.school-name { font-size:22px; font-weight:700; color:#0f7a55; }
.school-sub { font-size:12px; color:#666; margin-top:3px; }
h2 { font-size:16px; font-weight:700; text-align:center; border:2px solid #0f7a55; padding:7px; color:#0f7a55; margin:12px 0; }
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:16px; font-size:12px; }
.info-row { display:flex; gap:8px; }
.info-label { color:#666; min-width:90px; }
.info-value { font-weight:600; }
table { width:100%; border-collapse:collapse; margin:12px 0; font-size:12px; }
th { background:#0f7a55; color:#fff; padding:8px 10px; text-align:center; }
td { padding:7px 10px; border-bottom:1px solid #e0ebe7; text-align:center; }
td:first-child { text-align:left; }
.result-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin:14px 0; }
.result-box { border:1.5px solid #e0ebe7; border-radius:6px; padding:10px; text-align:center; }
.result-box.highlight { background:#e6f4f0; border-color:#0f7a55; }
.result-label { font-size:10px; color:#666; }
.result-value { font-size:18px; font-weight:700; color:#0f7a55; }
.passed { background:#dcfce7; color:#15803d; border:2px solid #16a34a; border-radius:6px; padding:8px; text-align:center; font-size:16px; font-weight:700; }
.failed { background:#fee2e2; color:#dc2626; border:2px solid #dc2626; border-radius:6px; padding:8px; text-align:center; font-size:16px; font-weight:700; }
.signatures { display:flex; justify-content:space-between; margin-top:40px; }
.sig { text-align:center; }
.sig-line { border-top:1px solid #666; width:120px; margin:0 auto 4px; }
.print-btn { display:block; width:100%; padding:11px; background:#0f7a55; color:#fff; border:none; border-radius:6px; font-size:14px; font-family:'Hind Siliguri',sans-serif; font-weight:600; cursor:pointer; margin-top:20px; }
@media print { body{background:none;padding:0;} .page{box-shadow:none;border-radius:0;} .print-btn{display:none;} }
</style>
</head>
<body>
<div class="page">
    <div class="header">
        <div class="school-name">{{ setting('school_name','বিদ্যাপীঠ') }}</div>
        <div class="school-sub">{{ setting('school_address','') }}</div>
        <h2>{{ $exam->name }} — একাডেমিক মার্কশিট</h2>
    </div>

    <div class="info-grid">
        <div>
            <div class="info-row"><span class="info-label">ছাত্রের নাম:</span><span class="info-value">{{ $student->name }}</span></div>
            <div class="info-row" style="margin-top:5px;"><span class="info-label">পিতার নাম:</span><span class="info-value">{{ $student->father_name }}</span></div>
            <div class="info-row" style="margin-top:5px;"><span class="info-label">শ্রেণী:</span><span class="info-value">{{ $student->studentClass->name??'—' }} — {{ $student->section->name??'' }}</span></div>
        </div>
        <div>
            <div class="info-row"><span class="info-label">ছাত্র আইডি:</span><span class="info-value">{{ $student->student_id }}</span></div>
            <div class="info-row" style="margin-top:5px;"><span class="info-label">শিক্ষাবর্ষ:</span><span class="info-value">{{ $exam->session->name??'—' }}</span></div>
            <div class="info-row" style="margin-top:5px;"><span class="info-label">পরীক্ষার তারিখ:</span><span class="info-value">{{ $exam->start_date->format('d/m/Y') }}</span></div>
        </div>
    </div>

    <table>
        <thead>
            <tr><th style="text-align:left;">বিষয়</th><th>পূর্ণমান</th><th>উত্তীর্ণ</th><th>প্রাপ্ত নম্বর</th><th>গ্রেড</th><th>গ্রেড পয়েন্ট</th></tr>
        </thead>
        <tbody>
            @foreach($results as $result)
            <tr>
                <td>{{ $result->subject->name }}</td>
                <td>{{ $result->subject->full_marks }}</td>
                <td>{{ $result->subject->pass_marks }}</td>
                <td style="font-weight:600;">{{ $result->marks_obtained }}</td>
                <td style="font-weight:700; color:{{ $result->grade==='F'?'#dc2626':($result->grade==='A+'?'#16a34a':'#1d4ed8') }}">{{ $result->grade }}</td>
                <td>{{ number_format($result->grade_point,2) }}</td>
            </tr>
            @endforeach
            <tr style="background:#f0f4f2; font-weight:700;">
                <td colspan="3" style="text-align:right;">মোট</td>
                <td>{{ $results->sum('marks_obtained') }}</td>
                <td>—</td>
                <td>{{ number_format($gpa,2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="result-grid">
        <div class="result-box"><div class="result-label">মোট নম্বর</div><div class="result-value">{{ $results->sum('marks_obtained') }}</div></div>
        <div class="result-box"><div class="result-label">গড়</div><div class="result-value">{{ number_format($results->avg('marks_obtained'),1) }}%</div></div>
        <div class="result-box highlight"><div class="result-label">GPA</div><div class="result-value">{{ number_format($gpa,2) }}</div></div>
        <div class="{{ $overallGrade!=='F'?'passed':'failed' }}">{{ $overallGrade!=='F'?'✅ উত্তীর্ণ':'❌ অনুত্তীর্ণ' }}</div>
    </div>

    <div class="signatures">
        <div class="sig"><div class="sig-line"></div><div style="font-size:11px;">শ্রেণী শিক্ষক</div></div>
        <div class="sig"><div class="sig-line"></div><div style="font-size:11px;">পরীক্ষা নিয়ন্ত্রক</div></div>
        <div class="sig"><div class="sig-line"></div><div style="font-size:11px;">প্রধান শিক্ষক</div></div>
        <div class="sig"><div class="sig-line" style="width:80px;"></div><div style="font-size:11px;">প্রতিষ্ঠানের সিল</div></div>
    </div>

    <button class="print-btn" onclick="window.print()">🖨️ প্রিন্ট করুন</button>
</div>
</body>
</html>
