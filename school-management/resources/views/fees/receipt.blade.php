{{-- ================================================================ --}}
{{-- resources/views/fees/receipt.blade.php (PDF) --}}
{{-- ================================================================ --}}
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 8mm; }
    body { font-family: 'SolaimanLipi', 'Kalpurush', Arial; font-size: 11px; color: #1a2e28; margin: 0; }
    .header { text-align: center; border-bottom: 2px solid #0f7a55; padding-bottom: 10px; margin-bottom: 12px; }
    .school-name { font-size: 18px; font-weight: 700; color: #0f7a55; }
    .address { font-size: 10px; color: #666; margin-top: 2px; }
    .receipt-title { font-size: 14px; font-weight: 700; margin: 8px 0 4px; }
    .receipt-no { font-size: 11px; color: #666; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    td { padding: 5px 8px; border-bottom: 1px dashed #e0ebe7; font-size: 11px; }
    td:first-child { color: #666; width: 45%; }
    td:last-child { font-weight: 600; }
    .amount-box { background: #e6f4f0; border-radius: 6px; padding: 10px 12px; text-align: center; margin: 10px 0; }
    .amount-box .label { font-size: 11px; color: #666; }
    .amount-box .value { font-size: 20px; font-weight: 700; color: #0f7a55; }
    .footer { margin-top: 16px; display: flex; justify-content: space-between; font-size: 10px; color: #999; }
    .paid-stamp { border: 3px solid #16a34a; color: #16a34a; font-size: 16px; font-weight: 900; padding: 4px 12px; border-radius: 4px; transform: rotate(-15deg); display: inline-block; margin: 8px 0; }
    .due-stamp { border: 3px solid #dc2626; color: #dc2626; font-size: 13px; font-weight: 900; padding: 4px 10px; border-radius: 4px; }
</style>
</head>
<body>
<div class="header">
    <div class="school-name">{{ $school['name'] }}</div>
    <div class="address">{{ $school['address'] }} | {{ $school['phone'] ?? '' }}</div>
    <div style="margin-top: 8px; text-align: right; font-size: 10px; color: #999;">
        তারিখ: {{ $fee->payment_date->format('d/m/Y') }}
    </div>
    <div class="receipt-title">ফি রসিদ / Fee Receipt</div>
    <div class="receipt-no">রশিদ নং: <strong>{{ $fee->receipt_no }}</strong></div>
</div>

<table>
    <tr><td>ছাত্রের নাম</td><td>{{ $fee->student->name }}</td></tr>
    <tr><td>ছাত্র আইডি</td><td style="font-family: monospace;">{{ $fee->student->student_id }}</td></tr>
    <tr><td>শ্রেণী</td><td>{{ $fee->student->studentClass->name ?? '—' }}</td></tr>
    <tr><td>পিতার নাম</td><td>{{ $fee->student->father_name }}</td></tr>
    <tr><td>ফি ধরন</td><td>{{ $fee->feeCategory->name }}</td></tr>
    @if($fee->month_year)
    <tr><td>মাস</td><td>{{ \Carbon\Carbon::createFromFormat('Y-m', $fee->month_year)->format('F Y') }}</td></tr>
    @endif
</table>

<table>
    <tr><td>মোট পরিমাণ</td><td>৳{{ number_format($fee->amount, 2) }}</td></tr>
    @if($fee->discount > 0)
    <tr><td>ছাড়</td><td style="color: #16a34a;">- ৳{{ number_format($fee->discount, 2) }}</td></tr>
    @endif
    <tr><td>পরিশোধিত</td><td style="color: #0f7a55; font-weight: 700;">৳{{ number_format($fee->paid_amount, 2) }}</td></tr>
    @if($fee->due_amount > 0)
    <tr><td>বকেয়া</td><td style="color: #dc2626; font-weight: 700;">৳{{ number_format($fee->due_amount, 2) }}</td></tr>
    @endif
</table>

<div class="amount-box">
    <div class="label">পরিশোধিত মোট পরিমাণ</div>
    <div class="value">৳{{ number_format($fee->paid_amount, 2) }}</div>
</div>

<div style="text-align: center; margin: 8px 0;">
    @if($fee->due_amount <= 0)
    <span class="paid-stamp">পরিশোধিত ✓</span>
    @else
    <span class="due-stamp">বকেয়া: ৳{{ number_format($fee->due_amount) }}</span>
    @endif
</div>

<div style="font-size: 10px; color: #666;">
    পেমেন্ট: {{ ['cash'=>'নগদ','bkash'=>'bKash','nagad'=>'Nagad','bank'=>'ব্যাংক'][$fee->payment_method] }}
    @if($fee->transaction_id) | ট্রানজেকশন: {{ $fee->transaction_id }} @endif
</div>

<div class="footer">
    <span>গ্রহণকারী: {{ $fee->collectedBy->name ?? '—' }}</span>
    <span>এই রশিদ কম্পিউটার জেনারেটেড</span>
</div>
</body>
</html>
