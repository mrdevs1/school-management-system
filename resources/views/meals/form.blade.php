@extends('layouts.app')
@section('page-title', 'খোরাকি এন্ট্রি')
@section('breadcrumb', 'Home / Meals / New Entry')
@section('content')

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('meals.create') }}" id="filterForm">
            <div class="filter-bar">
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">শ্রেণী</label>
                    <select name="class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">শ্রেণী বেছে নিন</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id')==$class->id?'selected':'' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

@if(request('class_id') && $students->count())
<form method="POST" action="{{ route('meals.bulk') }}">
    @csrf
    <div class="card">
        <div class="card-header" style="padding:14px 20px;">
            <div class="card-title"><i class="bi bi-cup-hot-fill"></i> খোরাকি বাল্ক এন্ট্রি</div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save-fill"></i> সংরক্ষণ</button>
        </div>

        <div style="padding:14px 20px; background:var(--bg); border-bottom:1px solid var(--border);">
            <div class="filter-bar">
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">মাস <span style="color:red">*</span></label>
                    <input type="month" name="month_year" class="form-control" value="{{ now()->format('Y-m') }}" required>
                </div>
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">প্রতিদিন হার (৳) <span style="color:red">*</span></label>
                    <input type="number" name="rate_per_day" class="form-control" value="60" min="0" step="0.5" required id="ratePerDay" onchange="calcAll()">
                </div>
                <input type="hidden" name="class_id" value="{{ request('class_id') }}">
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ছাত্র</th>
                        <th style="text-align:center; width:80px;">মোট দিন</th>
                        <th style="text-align:center; width:100px;">উপস্থিত দিন</th>
                        <th style="text-align:right; width:110px;">মোট খরচ</th>
                        <th style="text-align:right; width:120px;">ছাত্র দিয়েছে</th>
                        <th style="text-align:right; width:130px;">প্রতিষ্ঠান দিয়েছে</th>
                        <th style="text-align:right; width:100px;">বকেয়া</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>
                            <div style="font-weight:600; font-size:13px;">{{ $student->name }}</div>
                            <div style="font-size:11.5px; color:var(--text-muted);">{{ $student->student_id }}</div>
                        </td>
                        <td style="padding:6px 8px;">
                            <input type="number" name="entries[{{ $student->id }}][total_days]" class="form-control" value="30" min="1" style="text-align:center; padding:5px 6px;">
                        </td>
                        <td style="padding:6px 8px;">
                            <input type="number" name="entries[{{ $student->id }}][present_days]" class="form-control present-days" value="0" min="0" style="text-align:center; padding:5px 6px;" data-id="{{ $student->id }}" onchange="calcRow(this)">
                        </td>
                        <td style="text-align:right; font-family:var(--font-en); font-weight:600;">
                            <span id="total_{{ $student->id }}">৳0</span>
                        </td>
                        <td style="padding:6px 8px;">
                            <input type="number" name="entries[{{ $student->id }}][student_paid]" class="form-control student-paid" value="0" min="0" step="0.5" style="text-align:right; padding:5px 6px;" data-id="{{ $student->id }}" onchange="calcDue({{ $student->id }})">
                        </td>
                        <td style="padding:6px 8px;">
                            <input type="number" name="entries[{{ $student->id }}][institution_paid]" class="form-control inst-paid" value="0" min="0" step="0.5" style="text-align:right; padding:5px 6px;" data-id="{{ $student->id }}" onchange="calcDue({{ $student->id }})">
                        </td>
                        <td style="text-align:right;">
                            <span id="due_{{ $student->id }}" class="badge badge-gray">৳0</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:14px 20px; border-top:1px solid var(--border); display:flex; justify-content:flex-end;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> সব সংরক্ষণ করুন</button>
        </div>
    </div>
</form>
@else
<div class="card">
    <div class="empty-state" style="padding:60px;">
        <i class="bi bi-cup-hot" style="font-size:50px; color:var(--border);"></i>
        <p style="margin-top:12px; font-size:15px; font-weight:600;">শ্রেণী বেছে নিন</p>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
const totals = {};

function calcRow(input) {
    const id   = input.dataset.id;
    const days = parseFloat(input.value) || 0;
    const rate = parseFloat(document.getElementById('ratePerDay').value) || 0;
    const total = days * rate;
    totals[id] = total;
    document.getElementById('total_'+id).textContent = '৳' + total.toLocaleString();
    calcDue(id);
}

function calcDue(id) {
    const total = totals[id] || 0;
    const studentPaid = parseFloat(document.querySelector(`.student-paid[data-id="${id}"]`)?.value) || 0;
    const instPaid    = parseFloat(document.querySelector(`.inst-paid[data-id="${id}"]`)?.value) || 0;
    const due = Math.max(0, total - studentPaid - instPaid);
    const el = document.getElementById('due_'+id);
    el.textContent = '৳' + due.toLocaleString();
    el.className = due > 0 ? 'badge badge-red' : 'badge badge-green';
}

function calcAll() {
    document.querySelectorAll('.present-days').forEach(input => calcRow(input));
}
</script>
@endpush
