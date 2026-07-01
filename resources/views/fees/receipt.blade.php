<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @font-face {
        font-family: 'Hind';
        src: url('https://fonts.gstatic.com/s/hindsiliguri/v18/ijwOs5juQtsyLLR5jN4cxBEofJvQxuk.ttf') format('truetype');
        font-weight: normal;
    }
    @font-face {
        font-family: 'Hind';
        src: url('https://fonts.gstatic.com/s/hindsiliguri/v18/ijwNs5juQtsyLLR5jN4cxBEoRDe9XPaU.ttf') format('truetype');
        font-weight: bold;
    }
    @page { margin: 8mm; size: 80mm 150mm; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Hind', sans-serif; font-size: 11px; color: #1a2e28; }
    .header { text-align: center; border-bottom: 2px solid #0f7a55; padding-bottom: 8px; margin-bottom: 10px; }
    .school-name { font-size: 15px; font-weight: bold; color: #0f7a55; }
    .sub { font-size: 9px; color: #666; margin-top: 2px; }
    .receipt-title { font-size: 12px; font-weight: bold; margin: 5px 0 2px; }
    .receipt-no { font-size: 9px; color: #666; }
    .info-row { display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed #e0ebe7; font-size: 10px; }
    .info-label { color: #666; }
    .info-value { font-weight: bold; text-align: right; max-width: 55%; }
    .amount-section { background: #f0faf6; border-radius: 5px; padding: 8px; margin: 8px 0; }
    .amount-row { display: flex; justify-content: space-between; font-size: 10px; padding: 2px 0; }
    .amount-total { display: flex; justify-content: space-between; font-size: 13px; font-weight: bold; color: #0f7a55; border-top: 1px solid #0f7a55; margin-top: 4px; padding-top: 4px; }
    .stamp { text-align: center; margin: 8px 0; }
    .stamp-paid { display: inline-block; border: 2px solid #16a34a; color: #16a34a; font-size: 12px; font-weight: bold; padding: 3px 10px; border-radius: 4px; }
    .stamp-due { display: inline-block; border: 2px solid #dc2626; color: #dc2626; font-size: 11px; font-weight: bold; padding: 3px 10px; border-radius: 4px; }
    .footer { font-size: 9px; color: #999; text-align: center; margin-top: 10px; border-top: 1px dashed #e0ebe7; padding-top: 6px; }
</style>
</head>
<body>

<div class="header">
    <div class="school-name">{{ $school['name'] }}</div>
    <div class="sub">{{ $school['address'] }}</div>
    @if($school['phone'])<div class="sub">{{ $school['phone'] }}</div>@endif
    <div class="receipt-title">ফি রসিদ / Fee Receipt</div>
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
@if($fee->transaction_id)
<div class="info-row"><span class="info-label">ট্রানজেকশন আইডি</span><span class="info-value">{{ $fee->transaction_id }}</span></div>
@endif

<div class="amount-section">
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
    <span class="stamp-paid">পরিশোধিত ✓</span>
    @else
    <span class="stamp-due">বকেয়া: ৳{{ number_format($fee->due_amount) }}</span>
    @endif
</div>

<div class="footer">
    গ্রহণকারী: {{ $fee->collectedBy->name ?? '—' }} | কম্পিউটার প্রিন্টেড রশিদ
</div>

</body>
</html>
