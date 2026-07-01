@extends('layouts.app')
@section('page-title', 'খোরাকি ব্যবস্থাপনা')
@section('breadcrumb', 'Home / Meals')
@section('content')

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px;">
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">মোট খরচ</div>
        <div style="font-size:22px; font-weight:700; color:var(--text);">৳{{ number_format($stats['total_amount'] ?? 0) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">ছাত্র প্রদান</div>
        <div style="font-size:22px; font-weight:700; color:#16a34a;">৳{{ number_format($stats['student_paid'] ?? 0) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">প্রতিষ্ঠান প্রদান</div>
        <div style="font-size:22px; font-weight:700; color:#1d4ed8;">৳{{ number_format($stats['institution_paid'] ?? 0) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">বকেয়া</div>
        <div style="font-size:22px; font-weight:700; color:#dc2626;">৳{{ number_format($stats['due_amount'] ?? 0) }}</div>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('meals.index') }}">
            <div class="filter-bar">
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">মাস</label>
                    <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
                </div>
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">শ্রেণী</label>
                    <select name="class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">শ্রেণী বেছে নিন</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $class_id==$class->id?'selected':'' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-left:auto; display:flex; gap:8px; align-items:flex-end;">
                    <a href="{{ route('meals.create') }}{{ $class_id ? '?class_id='.$class_id : '' }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> নতুন এন্ট্রি
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-cup-hot-fill"></i> খোরাকি তালিকা — {{ \Carbon\Carbon::createFromFormat('Y-m',$month)->format('F Y') }}</div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ছাত্র</th>
                    <th>শ্রেণী</th>
                    <th style="text-align:center">উপস্থিত দিন</th>
                    <th style="text-align:right">প্রতিদিন হার</th>
                    <th style="text-align:right">মোট</th>
                    <th style="text-align:right">ছাত্র দিয়েছে</th>
                    <th style="text-align:right">প্রতিষ্ঠান দিয়েছে</th>
                    <th style="text-align:right">বকেয়া</th>
                    <th style="text-align:center">কার্যক্রম</th>
                </tr>
            </thead>
            <tbody>
                @forelse($meals as $meal)
                <tr>
                    <td>
                        <div style="font-weight:600; font-size:13px;">{{ $meal->student->name }}</div>
                        <div style="font-size:11.5px; color:var(--text-muted);">{{ $meal->student->student_id }}</div>
                    </td>
                    <td><span class="badge badge-blue">{{ $meal->student->studentClass->name??'—' }}</span></td>
                    <td style="text-align:center; font-family:var(--font-en);">{{ $meal->present_days }}/{{ $meal->total_days }}</td>
                    <td style="text-align:right; font-family:var(--font-en);">৳{{ number_format($meal->rate_per_day,2) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); font-weight:600;">৳{{ number_format($meal->total_amount) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#16a34a; font-weight:600;">৳{{ number_format($meal->student_paid) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#1d4ed8; font-weight:600;">৳{{ number_format($meal->institution_paid) }}</td>
                    <td style="text-align:right;">
                        @if($meal->due_amount > 0)
                        <span class="badge badge-red">৳{{ number_format($meal->due_amount) }}</span>
                        @else
                        <span class="badge badge-green">পরিশোধ</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <form method="POST" action="{{ route('meals.destroy',$meal) }}" onsubmit="return confirm('মুছবেন?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-icon btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9"><div class="empty-state"><i class="bi bi-cup-hot"></i><p>কোনো রেকর্ড নেই</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($meals instanceof \Illuminate\Pagination\LengthAwarePaginator && $meals->hasPages())
    <div class="pagination-wrap">{{ $meals->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
