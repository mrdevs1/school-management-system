@extends('layouts.app')
@section('page-title', 'আর্থিক রিপোর্ট')
@section('breadcrumb', 'Reports / Financial')
@section('content')

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('reports.financial') }}">
            <div class="filter-bar">
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">বছর</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">মাস</label>
                    <select name="month" class="form-select" onchange="this.form.submit()">
                        <option value="">সব মাস</option>
                        @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $month==$m?'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="align-self:flex-end;"><i class="bi bi-search"></i></button>
                <button type="button" class="btn btn-outline no-print" onclick="window.print()" style="align-self:flex-end;"><i class="bi bi-printer"></i> প্রিন্ট</button>
            </div>
        </form>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-cash-coin"></i> ফি সংগ্রহ</div></div>
        <div class="card-body">
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); font-size:13px;">
                <span style="color:var(--text-muted);">মোট সংগ্রহ</span>
                <span style="font-weight:700; color:#16a34a;">৳{{ number_format($data['fee_collected']) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; font-size:13px;">
                <span style="color:var(--text-muted);">মোট বকেয়া</span>
                <span style="font-weight:700; color:#dc2626;">৳{{ number_format($data['fee_due']) }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-cup-hot-fill"></i> খোরাকি</div></div>
        <div class="card-body">
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); font-size:13px;">
                <span style="color:var(--text-muted);">মোট খরচ</span>
                <span style="font-weight:700;">৳{{ number_format($data['meal_total']) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); font-size:13px;">
                <span style="color:var(--text-muted);">ছাত্র প্রদান</span>
                <span style="font-weight:700; color:#16a34a;">৳{{ number_format($data['meal_student']) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); font-size:13px;">
                <span style="color:var(--text-muted);">প্রতিষ্ঠান প্রদান</span>
                <span style="font-weight:700; color:#1d4ed8;">৳{{ number_format($data['meal_institution']) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; font-size:13px;">
                <span style="color:var(--text-muted);">বকেয়া</span>
                <span style="font-weight:700; color:#dc2626;">৳{{ number_format($data['meal_due']) }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-heart-fill" style="color:#dc2626;"></i> গোরাবা ফান্ড</div></div>
        <div class="card-body">
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); font-size:13px;">
                <span style="color:var(--text-muted);">মোট সহায়তা</span>
                <span style="font-weight:700;">৳{{ number_format($data['welfare_total']) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); font-size:13px;">
                <span style="color:var(--text-muted);">প্রতিষ্ঠান প্রদান</span>
                <span style="font-weight:700; color:#1d4ed8;">৳{{ number_format($data['welfare_institution']) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; font-size:13px;">
                <span style="color:var(--text-muted);">দাতা প্রদান</span>
                <span style="font-weight:700; color:#7c3aed;">৳{{ number_format($data['welfare_donor']) }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-bar-chart-fill"></i> সারসংক্ষেপ</div></div>
        <div class="card-body">
            @php
            $totalIncome  = $data['fee_collected'] + $data['meal_student'];
            $totalExpense = $data['meal_institution'] + $data['welfare_institution'];
            @endphp
            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border); font-size:13px;">
                <span style="color:var(--text-muted);">মোট আয়</span>
                <span style="font-weight:700; color:#16a34a;">৳{{ number_format($totalIncome) }}</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; font-size:13px;">
                <span style="color:var(--text-muted);">প্রতিষ্ঠানের মোট ব্যয়</span>
                <span style="font-weight:700; color:#dc2626;">৳{{ number_format($totalExpense) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- মাসওয়ারি ফি সংগ্রহ -->
@if($monthlyFee->count())
<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-graph-up"></i> {{ $year }} সালের মাসওয়ারী ফি সংগ্রহ</div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>মাস</th><th style="text-align:right">সংগ্রহ</th></tr></thead>
            <tbody>
                @foreach($monthlyFee as $mf)
                <tr>
                    <td>{{ \Carbon\Carbon::create()->month($mf->month)->format('F') }}</td>
                    <td style="text-align:right; font-family:var(--font-en); font-weight:600; color:#16a34a;">৳{{ number_format($mf->total) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
