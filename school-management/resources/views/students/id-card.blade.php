{{-- resources/views/students/id-card.blade.php --}}
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 0; size: 85.6mm 54mm; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'SolaimanLipi', 'Kalpurush', Arial;
        width: 85.6mm; height: 54mm;
        overflow: hidden;
        font-size: 7px;
    }

    /* Front Side */
    .card-front {
        width: 85.6mm; height: 54mm;
        background: linear-gradient(135deg, #0a2e22 0%, #0f7a55 100%);
        color: white;
        display: flex;
        flex-direction: column;
        padding: 4mm;
        position: relative;
        overflow: hidden;
    }

    .card-front::before {
        content: '';
        position: absolute;
        right: -8mm; top: -8mm;
        width: 30mm; height: 30mm;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }

    .card-front::after {
        content: '';
        position: absolute;
        right: 4mm; bottom: -12mm;
        width: 40mm; height: 40mm;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }

    .card-header-row {
        display: flex;
        align-items: center;
        gap: 3mm;
        margin-bottom: 3mm;
        padding-bottom: 2mm;
        border-bottom: 0.5px solid rgba(255,255,255,0.3);
    }

    .school-logo {
        width: 10mm; height: 10mm;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0;
    }

    .school-info .name {
        font-size: 8.5px;
        font-weight: 700;
        line-height: 1.3;
    }

    .school-info .address {
        font-size: 6px;
        opacity: 0.75;
        margin-top: 1px;
    }

    .id-badge {
        margin-left: auto;
        background: rgba(255,255,255,0.15);
        padding: 1.5mm 3mm;
        border-radius: 2mm;
        font-size: 7px;
        font-weight: 700;
        text-align: center;
        flex-shrink: 0;
    }

    .id-badge .label { font-size: 5.5px; opacity: 0.75; display: block; }

    .card-body-row {
        display: flex;
        gap: 3mm;
        flex: 1;
    }

    .photo-box {
        width: 18mm; height: 24mm;
        border: 1.5px solid rgba(255,255,255,0.4);
        border-radius: 2mm;
        overflow: hidden;
        flex-shrink: 0;
        background: rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center;
    }

    .photo-box img { width: 100%; height: 100%; object-fit: cover; }
    .photo-placeholder { font-size: 20px; opacity: 0.5; }

    .student-info { flex: 1; }

    .student-name {
        font-size: 10px;
        font-weight: 900;
        line-height: 1.3;
        margin-bottom: 1mm;
    }

    .info-row {
        display: flex;
        gap: 2mm;
        margin-bottom: 1mm;
        font-size: 6.5px;
    }

    .info-row .lbl { opacity: 0.7; min-width: 14mm; }
    .info-row .val { font-weight: 600; }

    .card-footer-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2mm;
        padding-top: 2mm;
        border-top: 0.5px solid rgba(255,255,255,0.2);
    }

    .barcode-area {
        font-size: 5.5px;
        opacity: 0.6;
        letter-spacing: 1px;
        font-family: monospace;
    }

    .session-badge {
        background: rgba(255,255,255,0.15);
        padding: 1mm 2mm;
        border-radius: 1.5mm;
        font-size: 6px;
        font-weight: 600;
    }
</style>
</head>
<body>

<div class="card-front">
    <div class="card-header-row">
        <div class="school-logo">🕌</div>
        <div class="school-info">
            <div class="name">{{ config('school.name', 'বিদ্যাপীঠ') }}</div>
            <div class="address">{{ config('school.address', '') }}</div>
        </div>
        <div class="id-badge">
            <span class="label">STUDENT ID</span>
            {{ $student->student_id }}
        </div>
    </div>

    <div class="card-body-row">
        <div class="photo-box">
            @if($student->photo)
            <img src="{{ public_path('storage/'.$student->photo) }}">
            @else
            <div class="photo-placeholder">👤</div>
            @endif
        </div>

        <div class="student-info">
            <div class="student-name">{{ $student->name }}</div>

            <div class="info-row">
                <span class="lbl">পিতা:</span>
                <span class="val">{{ $student->father_name }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">মাতা:</span>
                <span class="val">{{ $student->mother_name }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">শ্রেণী:</span>
                <span class="val">{{ $student->studentClass->name ?? '—' }} — {{ $student->section->name ?? '' }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">জন্ম তারিখ:</span>
                <span class="val">{{ $student->date_of_birth->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">ফোন:</span>
                <span class="val">{{ $student->guardian_phone }}</span>
            </div>
            @if($student->blood_group)
            <div class="info-row">
                <span class="lbl">রক্তের গ্রুপ:</span>
                <span class="val" style="color:#ff6b6b; font-weight:900;">{{ $student->blood_group }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="card-footer-row">
        <div class="barcode-area">{{ $student->student_id }} | {{ now()->year }}</div>
        <div class="session-badge">সেশন: {{ $student->session->name ?? '—' }}</div>
    </div>
</div>

</body>
</html>
