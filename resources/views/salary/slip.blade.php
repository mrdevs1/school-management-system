<!DOCTYPE html>
<html lang="bn">
<head>
<meta charset="UTF-8">
<title>বেতন স্লিপ</title>
<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Hind Siliguri',sans-serif; background:#f5f5f5; padding:20px; display:flex; justify-content:center; }
.slip { background:#fff; width:320px; padding:24px; border-radius:8px; box-shadow:0 2px 15px rgba(0,0,0,0.1); }
.header { text-align:center; border-bottom:2px solid #0f7a55; padding-bottom:12px; margin-bottom:14px; }
.school-name { font-size:16px; font-weight:700; color:#0f7a55; }
.school-sub { font-size:10px; color:#666; margin-top:2px; }
.title { font-size:13px; font-weight:700; margin:8px 0 3px; }
.sub-title { font-size:10px; color:#666; }
.info-row { display:flex; justify-content:space-between; padding:5px 0; border-bottom:1px dashed #eee; font-size:12px; }
.info-label { color:#666; }
.info-value { font-weight:600; }
.amount-box { background:#f0faf6; border-radius:6px; padding:10px; margin:10px 0; }
.amount-row { display:flex; justify-content:space-between; font-size:12px; padding:2px 0; }
.amount-total { display:flex; justify-content:space-between; font-size:15px; font-weight:700; color:#0f7a55; border-top:1px solid #0f7a55; margin-top:6px; padding-top:6px; }
.stamp { text-align:center; border:2px solid #16a34a; color:#16a34a; font-size:14px; font-weight:700; padding:5px; border-radius:5px; margin:12px 0; }
.signatures { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-top:30px; text-align:center; font-size:10px; }
.sig-line { border-top:1px solid #666; margin-bottom:4px; }
.print-btn { display:block; width:100%; padding:10px; background:#0f7a55; color:#fff; border:none; border-radius:6px; font-size:14px; font-family:'Hind Siliguri',sans-serif; font-weight:600; cursor:pointer; margin-top:16px; }
@media print { body{background:none;padding:0;} .slip{box-shadow:none;border-radius:0;} .print-btn{display:none;} }
</style>
</head>
<body>
<div class="slip">
    <div class="header">
        <div class="school-name">{{ setting('school_name','বিদ্যাপীঠ') }}</div>
        <div class="school-sub">{{ setting('school_address','') }}</div>
        <div class="title">বেতন স্লিপ / Salary Slip</div>
        <div class="sub-title">
            মাস: {{ \Carbon\Carbon::createFromFormat('Y-m',$payment->month_year)->format('F Y') }}
            | তারিখ: {{ $payment->payment_date->format('d/m/Y') }}
        </div>
    </div>

    <div class="info-row"><span class="info-label">শিক্ষকের নাম</span><span class="info-value">{{ $payment->teacher->name }}</span></div>
    <div class="info-row"><span class="info-label">আইডি</span><span class="info-value">{{ $payment->teacher->teacher_id }}</span></div>
    <div class="info-row"><span class="info-label">পদবি</span><span class="info-value">{{ $payment->teacher->designation }}</span></div>
    <div class="info-row"><span class="info-label">যোগদান</span><span class="info-value">{{ $payment->teacher->joining_date->format('d/m/Y') }}</span></div>

    <div class="amount-box">
        <div class="amount-row"><span>মূল বেতন</span><span>৳{{ number_format($payment->basic_salary,2) }}</span></div>
        @if($payment->bonus > 0)
        <div class="amount-row"><span>বোনাস</span><span style="color:#16a34a;">+ ৳{{ number_format($payment->bonus,2) }}</span></div>
        @endif
        @if($payment->deduction > 0)
        <div class="amount-row"><span>কর্তন</span><span style="color:#dc2626;">- ৳{{ number_format($payment->deduction,2) }}</span></div>
        @endif
        <div class="amount-total"><span>নিট বেতন</span><span>৳{{ number_format($payment->net_salary,2) }}</span></div>
    </div>

    <div class="stamp">✅ পরিশোধিত</div>

    <div class="info-row">
        <span class="info-label">পেমেন্ট পদ্ধতি</span>
        <span class="info-value">{{ ['cash'=>'নগদ','bank'=>'ব্যাংক','bkash'=>'bKash'][$payment->payment_method] }}</span>
    </div>
    @if($payment->note)
    <div class="info-row"><span class="info-label">মন্তব্য</span><span class="info-value">{{ $payment->note }}</span></div>
    @endif

    <div class="signatures">
        <div><div class="sig-line"></div>শিক্ষকের স্বাক্ষর</div>
        <div><div class="sig-line"></div>পরিশোধকারী</div>
        <div><div class="sig-line"></div>প্রধান শিক্ষক</div>
    </div>

    <button class="print-btn" onclick="window.print()">🖨️ প্রিন্ট করুন</button>
</div>
</body>
</html>
