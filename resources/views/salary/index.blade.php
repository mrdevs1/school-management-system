@extends('layouts.app')
@section('page-title', 'বেতন প্রদান')
@section('breadcrumb', 'Home / Salary')
@section('content')

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px;">
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); font-family:var(--font-en); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">এ মাসে পরিশোধ</div>
        <div style="font-size:22px; font-weight:700; color:#16a34a;">৳{{ number_format($totalPaid) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); font-family:var(--font-en); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">পরিশোধ হয়েছে</div>
        <div style="font-size:22px; font-weight:700; color:#1d4ed8;">{{ $paidCount }} জন</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); font-family:var(--font-en); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">বাকি আছে</div>
        <div style="font-size:22px; font-weight:700; color:#d97706;">{{ $unpaidCount }} জন</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); font-family:var(--font-en); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">মোট শিক্ষক</div>
        <div style="font-size:22px; font-weight:700; color:var(--text);">{{ $teachers->count() }} জন</div>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <div class="filter-bar">
            <div>
                <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">মাস নির্বাচন</label>
                <form method="GET" action="{{ route('salaries.index') }}">
                    <div style="display:flex; gap:8px;">
                        <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
                    </div>
                </form>
            </div>
            <div style="margin-left:auto;">
                <form method="POST" action="{{ route('salaries.pay-all') }}" onsubmit="return confirm('সব বাকি বেতন পরিশোধ করবেন?')">
                    @csrf
                    <input type="hidden" name="month_year" value="{{ $month }}">
                    <button type="submit" class="btn btn-primary" {{ $unpaidCount==0?'disabled':'' }}>
                        <i class="bi bi-cash-coin"></i> সব বেতন এক সাথে পরিশোধ ({{ $unpaidCount }} জন)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-wallet2"></i> শিক্ষক বেতন তালিকা — {{ \Carbon\Carbon::createFromFormat('Y-m',$month)->format('F Y') }}</div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>শিক্ষক</th>
                    <th>পদবি</th>
                    <th style="text-align:right">মূল বেতন</th>
                    <th style="text-align:right">বোনাস</th>
                    <th style="text-align:right">কর্তন</th>
                    <th style="text-align:right">নিট বেতন</th>
                    <th style="text-align:center">অবস্থা</th>
                    <th style="text-align:center">কার্যক্রম</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                @php $payment = $payments[$teacher->id] ?? null; @endphp
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div class="avatar-placeholder" style="background:#dbeafe; color:#1d4ed8;">{{ mb_substr($teacher->name,0,1) }}</div>
                            <div>
                                <div style="font-weight:600; font-size:13.5px;">{{ $teacher->name }}</div>
                                <div style="font-size:11.5px; color:var(--text-muted); font-family:var(--font-en);">{{ $teacher->teacher_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $teacher->designation }}</td>
                    <td style="text-align:right; font-family:var(--font-en); font-weight:600;">৳{{ number_format($teacher->salary) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#16a34a;">{{ $payment ? '৳'.number_format($payment->bonus) : '—' }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#dc2626;">{{ $payment ? '৳'.number_format($payment->deduction) : '—' }}</td>
                    <td style="text-align:right; font-family:var(--font-en); font-weight:700; color:var(--green);">৳{{ number_format($payment?->net_salary ?? $teacher->salary) }}</td>
                    <td style="text-align:center;">
                        @if($payment)
                        <span class="badge badge-green">✅ পরিশোধ</span>
                        @else
                        <span class="badge badge-orange">⏳ বাকি</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:5px; justify-content:center;">
                            @if($payment)
                            <a href="{{ route('salaries.slip',$payment->id) }}" class="btn btn-icon btn-outline btn-sm" target="_blank" title="স্লিপ"><i class="bi bi-printer"></i></a>
                            @else
                            <button class="btn btn-primary btn-sm" onclick="openPayModal({{ $teacher->id }},'{{ $teacher->name }}',{{ $teacher->salary }})">
                                <i class="bi bi-cash"></i> বেতন দিন
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pay Modal -->
<div class="modal-overlay" id="payModal">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <h5 style="font-weight:700; font-size:16px;"><i class="bi bi-cash-coin" style="color:var(--green)"></i> বেতন প্রদান</h5>
            <button onclick="closeModal()" style="background:none; border:none; font-size:22px; cursor:pointer; color:var(--text-muted);">×</button>
        </div>
        <form method="POST" action="{{ route('salaries.pay') }}">
            @csrf
            <input type="hidden" name="teacher_id" id="modalTeacherId">
            <input type="hidden" name="month_year" value="{{ $month }}">
            <div class="modal-body">
                <div id="modalTeacherName" style="font-size:15px; font-weight:700; margin-bottom:16px; padding:10px 14px; background:var(--green-light); border-radius:var(--radius-sm); color:var(--green);"></div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:14px;">
                    <div>
                        <label class="form-label">মূল বেতন</label>
                        <input type="number" id="modalBasicSalary" class="form-control" readonly style="background:var(--bg);">
                    </div>
                    <div>
                        <label class="form-label">বোনাস</label>
                        <input type="number" name="bonus" class="form-control" value="0" min="0" id="modalBonus" onchange="calcNet()">
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:14px;">
                    <div>
                        <label class="form-label">কর্তন</label>
                        <input type="number" name="deduction" class="form-control" value="0" min="0" id="modalDeduction" onchange="calcNet()">
                    </div>
                    <div>
                        <label class="form-label">নিট বেতন</label>
                        <input type="number" id="modalNetSalary" class="form-control" readonly style="background:var(--bg); font-weight:700; color:var(--green);">
                    </div>
                </div>
                <div style="margin-bottom:14px;">
                    <label class="form-label">পেমেন্ট পদ্ধতি</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash">💵 নগদ</option>
                        <option value="bank">🏦 ব্যাংক ট্রান্সফার</option>
                        <option value="bkash">📱 bKash</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">মন্তব্য</label>
                    <input type="text" name="note" class="form-control" placeholder="ঐচ্ছিক...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn btn-outline">বাতিল</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> বেতন পরিশোধ করুন</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
let baseSalary = 0;
function openPayModal(id, name, salary) {
    baseSalary = salary;
    document.getElementById('modalTeacherId').value = id;
    document.getElementById('modalTeacherName').textContent = name;
    document.getElementById('modalBasicSalary').value = salary;
    document.getElementById('modalNetSalary').value = salary;
    document.getElementById('modalBonus').value = 0;
    document.getElementById('modalDeduction').value = 0;
    document.getElementById('payModal').classList.add('open');
}
function closeModal() { document.getElementById('payModal').classList.remove('open'); }
function calcNet() {
    const bonus     = parseFloat(document.getElementById('modalBonus').value) || 0;
    const deduction = parseFloat(document.getElementById('modalDeduction').value) || 0;
    document.getElementById('modalNetSalary').value = Math.max(0, baseSalary + bonus - deduction);
}
</script>
@endpush
