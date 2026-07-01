<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>রশিদ - {{ $fee->receipt_no }}</title>
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'Hind Siliguri', sans-serif; background: #f5f5f5; display:flex; justify-content:center; padding:20px; }
    .receipt { background:#fff; width:320px; padding:20px; border-radius:8px; box-shadow:0 2px 15px rgba(0,0,0,0.1); }
    .header { text-align:center; border-bottom:2px solid #0f7a55; padding-bottom:12px; margin-bottom:12px; }
    .school-name { font-size:16px; font-weight:700; color:#0f7a55; }
    .school-sub { font-size:10px; color:#666; margin-top:2px; }
    .title { font-size:13px; font-weight:700; margin:8px 0 3px; }
    .receipt-no { font-size:11px; color:#666; }
    .info-row { display:flex; justify-content:space-between; padding:5px 0; border-bottom:1px dashed #eee; font-size:12px; }
    .info-label { color:#666; }
    .info-value { font-weight:600; text-align:right; max-width:60%; }
    .amount-box { background:#f0faf6; border-radius:6px; padding:10px; margin:10px 0; }
    .amount-row { display:flex; justify-content:space-between; font-size:12px; padding:2px 0; }
    .amount-total { display:flex; justify-content:space-between; font-size:15px; font-weight:700; color:#0f7a55; border-top:1px solid #0f7a55; margin-top:6px; padding-top:6px; }
    .stamp { text-align:center; margin:10px 0; }
    .stamp-paid { display:inline-block; border:2px solid #16a34a; color:#16a34a; font-size:13px; font-weight:700; padding:4px 14px; border-radius:4px; }
    .stamp-due { display:inline-block; border:2px solid #dc2626; color:#dc2626; font-size:12px; font-weight:700; padding:4px 12px; border-radius:4px; }
    .footer { font-size:10px; color:#999; text-align:center; margin-top:12px; border-top:1px dashed #eee; padding-top:8px; }
    .print-btn { display:block; width:100%; padding:10px; background:#0f7a55; color:#fff; border:none; border-radius:6px; font-size:14px; font-family:'Hind Siliguri',sans-serif; font-weight:600; cursor:pointer; margin-top:16px; }
    @media print {
        body { background:none; padding:0; }
        .receipt { box-shadow:none; border-radius:0; }
        .print-btn { display:none; }
    }
</style>
</head>
<body>
<div class="receipt">
    <div class="header">
        <div class="school-name">{{ config('school.name','বিদ্যাপীঠ') }}</div>
        <div class="school-sub">{{ config('school.address','') }}</div>
        <div class="title">ফি রসিদ / Fee Receipt</div>
        <div class="receipt-no">রশিদ নং: {{ $fee->receipt_no }} | {{ $fee->payment_date->format('d/m/Y') }}</div>
    </div>

    <div class="info-row"><span class="info-label">ছাত্রের নাম</span><span class="info-value">{{ $fee->student->name }}</span></div>
    <div class="info-row"><span class="info-label">ছাত্র আইডি</span><span class="info-value">{{ $fee->student->student_id }}</span></div>
    <div class="info-row"><span class="info-label">শ্রেণী</span><span class="info-value">{{ $fee->student->studentClass->name ?? '—' }}</span></div>
    <div class="info-row"><span class="info-label">পিতার নাম</span><span class="info-value">{{ $fee->student->father_name }}</span></div>
    <div class="info-row"><span class="info-label">ফি ধরন</span><span class="info-value">{{ $fee->feeCategory->name }}</span></div>
    @if($fee->month_year)
    <div class="info-row"><span class="info-label">মাস</span><span class="info-value">{{ \Carbon\Carbon::createFromFormat('Y-m',$fee->month_year)->format('F Y') }}</span></div>
    @endif
    <div class="info-row"><span class="info-label">পেমেন্ট পদ্ধতি</span><span class="info-value">{{ ['cash'=>'নগদ','bkash'=>'bKash','nagad'=>'Nagad','bank'=>'ব্যাংক'][$fee->payment_method] }}</span></div>

    <div class="amount-box">
        <div class="amount-row"><span>মোট পরিমাণ</span><span>৳{{ number_format($fee->amount,2) }}</span></div>
        @if($fee->discount > 0)
        <div class="amount-row"><span>ছাড়</span><span style="color:#16a34a;">- ৳{{ number_format($fee->discount,2) }}</span></div>
        @endif
        @if($fee->due_amount > 0)
        <div class="amount-row"><span>বকেয়া</span><span style="color:#dc2626;">৳{{ number_format($fee->due_amount,2) }}</span></div>
        @endif
        <div class="amount-total"><span>পরিশোধিত</span><span>৳{{ number_format($fee->paid_amount,2) }}</span></div>
    </div>

    <div class="stamp">
        @if($fee->due_amount <= 0)
        <span class="stamp-paid">✓ পরিশোধিত</span>
        @else
        <span class="stamp-due">বকেয়া: ৳{{ number_format($fee->due_amount) }}</span>
        @endif
    </div>

    <div class="footer">
        গ্রহণকারী: {{ $fee->collectedBy->name ?? '—' }}<br>
        কম্পিউটার প্রিন্টেড রশিদ
    </div>

    <button class="print-btn" onclick="window.print()">🖨️ প্রিন্ট করুন</button>
</div>
</body>
</html>
