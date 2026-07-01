{{-- ================================================================ --}}
{{-- resources/views/results/marksheet.blade.php (PDF) --}}
{{-- ================================================================ --}}
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 12mm; }
    body { font-family: 'SolaimanLipi', 'Kalpurush', Arial; font-size: 11.5px; color: #1a2e28; }
    .header { text-align: center; border-bottom: 3px double #0f7a55; padding-bottom: 12px; margin-bottom: 14px; }
    .school-name { font-size: 22px; font-weight: 900; color: #0f7a55; letter-spacing: -0.5px; }
    .school-sub  { font-size: 12px; color: #444; margin: 3px 0; }
    h2 { font-size: 16px; text-align: center; font-weight: 700; margin: 10px 0 14px; border: 2px solid #0f7a55; padding: 6px; color: #0f7a55; }
    .info-grid { display: flex; gap: 20px; margin-bottom: 14px; }
    .info-cell { flex: 1; }
    .info-row { display: flex; margin-bottom: 4px; font-size: 11px; }
    .info-label { width: 100px; color: #666; }
    .info-value { font-weight: 600; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th { background: #0f7a55; color: #fff; padding: 8px 10px; font-size: 11px; text-align: center; }
    td { padding: 7px 10px; border-bottom: 1px solid #e0ebe7; font-size: 11px; }
    td.center { text-align: center; }
    .grade-AP { color: #15803d; font-weight: 700; }
    .grade-A  { color: #1d4ed8; font-weight: 700; }
    .grade-F  { color: #dc2626; font-weight: 700; }
    .result-summary { display: flex; gap: 10px; margin: 14px 0; }
    .summary-box { flex: 1; border: 2px solid #e0ebe7; border-radius: 6px; padding: 8px 12px; text-align: center; }
    .summary-box.highlight { background: #e6f4f0; border-color: #0f7a55; }
    .summary-label { font-size: 10px; color: #666; }
    .summary-value { font-size: 18px; font-weight: 700; color: #0f7a55; }
    .result-badge { font-size: 16px; font-weight: 900; text-align: center; padding: 8px; border-radius: 6px; margin: 10px 0; }
    .passed  { background: #dcfce7; color: #15803d; border: 2px solid #16a34a; }
    .failed  { background: #fee2e2; color: #dc2626; border: 2px solid #dc2626; }
    .signatures { display: flex; justify-content: space-between; margin-top: 40px; }
    .sig { text-align: center; }
    .sig-line { border-top: 1px solid #666; width: 120px; margin: 0 auto 4px; }
    .sig-label { font-size: 10.5px; color: #444; }
</style>
</head>
<body>

<div class="header">
    <div class="school-name">{{ $school['name'] }}</div>
    <div class="school-sub">{{ $school['address'] ?? '' }}</div>
    <div class="school-sub">📞 {{ config('school.phone','') }}</div>
</div>

<h2>{{ $exam->name }} — একাডেমিক মার্কশিট</h2>

<div style="display: flex; margin-bottom: 14px;">
    <div style="flex: 1;">
        <table style="width: auto; margin: 0;">
            <tr><td style="border: none; padding: 3px 8px 3px 0; color: #666; width: 110px;">ছাত্রের নাম</td><td style="border: none; padding: 3px 0; font-weight: 700;">{{ $student->name }}</td></tr>
            <tr><td style="border: none; padding: 3px 8px 3px 0; color: #666;">পিতার নাম</td><td style="border: none; padding: 3px 0; font-weight: 600;">{{ $student->father_name }}</td></tr>
            <tr><td style="border: none; padding: 3px 8px 3px 0; color: #666;">শ্রেণী</td><td style="border: none; padding: 3px 0;">{{ $student->studentClass->name ?? '—' }} — {{ $student->section->name ?? '' }}</td></tr>
        </table>
    </div>
    <div style="flex: 1;">
        <table style="width: auto; margin: 0;">
            <tr><td style="border: none; padding: 3px 8px 3px 0; color: #666; width: 110px;">ছাত্র আইডি</td><td style="border: none; padding: 3px 0; font-weight: 700; font-family: monospace;">{{ $student->student_id }}</td></tr>
            <tr><td style="border: none; padding: 3px 8px 3px 0; color: #666;">শিক্ষাবর্ষ</td><td style="border: none; padding: 3px 0;">{{ $exam->session->name ?? '—' }}</td></tr>
            <tr><td style="border: none; padding: 3px 8px 3px 0; color: #666;">পরীক্ষার তারিখ</td><td style="border: none; padding: 3px 0;">{{ $exam->start_date->format('d/m/Y') }}</td></tr>
        </table>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th style="text-align: left;">বিষয়</th>
            <th>পূর্ণমান</th>
            <th>উত্তীর্ণ</th>
            <th>প্রাপ্ত নম্বর</th>
            <th>গ্রেড</th>
            <th>গ্রেড পয়েন্ট</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $result)
        <tr>
            <td>{{ $result->subject->name }}</td>
            <td class="center">{{ $result->subject->full_marks }}</td>
            <td class="center">{{ $result->subject->pass_marks }}</td>
            <td class="center" style="font-weight: 600;">{{ $result->marks_obtained }}</td>
            <td class="center grade-{{ str_replace('+','P',$result->grade) }}">{{ $result->grade }}</td>
            <td class="center">{{ number_format($result->grade_point, 2) }}</td>
        </tr>
        @endforeach
        <tr style="background: #f0f4f2; font-weight: 700;">
            <td colspan="3" style="text-align: right;">মোট</td>
            <td class="center">{{ $results->sum('marks_obtained') }}</td>
            <td class="center">—</td>
            <td class="center">{{ number_format($gpa, 2) }}</td>
        </tr>
    </tbody>
</table>

<div style="display: flex; gap: 12px; margin: 12px 0;">
    <div style="flex: 1; border: 1.5px solid #e0ebe7; border-radius: 6px; padding: 8px 12px; text-align: center;">
        <div style="font-size: 10px; color: #666;">প্রাপ্ত মোট নম্বর</div>
        <div style="font-size: 20px; font-weight: 700; color: #1a2e28;">{{ $results->sum('marks_obtained') }}</div>
    </div>
    <div style="flex: 1; border: 1.5px solid #e0ebe7; border-radius: 6px; padding: 8px 12px; text-align: center;">
        <div style="font-size: 10px; color: #666;">GPA</div>
        <div style="font-size: 20px; font-weight: 700; color: #0f7a55;">{{ number_format($gpa, 2) }}</div>
    </div>
    <div style="flex: 1; border: 1.5px solid #e0ebe7; border-radius: 6px; padding: 8px 12px; text-align: center;">
        <div style="font-size: 10px; color: #666;">সার্বিক গ্রেড</div>
        <div style="font-size: 20px; font-weight: 700; color: {{ $overallGrade === 'F' ? '#dc2626' : '#0f7a55' }};">{{ $overallGrade }}</div>
    </div>
    <div style="flex: 2; border-radius: 6px; padding: 8px 12px; text-align: center;
                background: {{ $overallGrade !== 'F' ? '#dcfce7' : '#fee2e2' }};
                border: 2px solid {{ $overallGrade !== 'F' ? '#16a34a' : '#dc2626' }};">
        <div style="font-size: 11px; color: #666;">ফলাফল</div>
        <div style="font-size: 18px; font-weight: 900; color: {{ $overallGrade !== 'F' ? '#15803d' : '#dc2626' }};">
            {{ $overallGrade !== 'F' ? '✅ উত্তীর্ণ' : '❌ অনুত্তীর্ণ' }}
        </div>
    </div>
</div>

<div class="signatures">
    <div class="sig"><div class="sig-line"></div><div class="sig-label">শ্রেণী শিক্ষক</div></div>
    <div class="sig"><div class="sig-line"></div><div class="sig-label">পরীক্ষা নিয়ন্ত্রক</div></div>
    <div class="sig"><div class="sig-line"></div><div class="sig-label">প্রধান শিক্ষক</div></div>
    <div class="sig"><div class="sig-line" style="width: 80px;"></div><div class="sig-label">প্রতিষ্ঠানের সিল</div></div>
</div>

<div style="text-align: center; margin-top: 10px; font-size: 9.5px; color: #aaa;">
    মুদ্রণের তারিখ: {{ now()->format('d/m/Y') }} | এই মার্কশিট কম্পিউটার প্রিন্টেড
</div>

</body>
</html>
