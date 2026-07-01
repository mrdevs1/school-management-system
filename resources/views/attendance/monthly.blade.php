@extends('layouts.app')
@section('page-title', 'মাসিক উপস্থিতি রিপোর্ট')
@section('breadcrumb', 'Attendance / Monthly')
@section('content')
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('attendance.monthly') }}">
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
                @if($class_id && $data->count())
                <button type="button" onclick="window.print()" class="btn btn-outline" style="align-self:flex-end;"><i class="bi bi-printer"></i> প্রিন্ট</button>
                @endif
            </div>
        </form>
    </div>
</div>

@if($data->count())
<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-calendar-month"></i> {{ \Carbon\Carbon::createFromFormat('Y-m',$month)->format('F Y') }} — উপস্থিতি রিপোর্ট</div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>ছাত্র</th><th style="text-align:center; background:#dcfce7; color:#15803d;">উপস্থিত</th><th style="text-align:center; background:#fee2e2; color:#dc2626;">অনুপস্থিত</th><th style="text-align:center; background:#fef3c7; color:#d97706;">দেরিতে</th><th style="text-align:center;">মোট দিন</th><th style="text-align:center;">হার</th></tr></thead>
            <tbody>
                @foreach($data as $row)
                @php $pct = $row['total']>0 ? round(($row['present']/$row['total'])*100) : 0; @endphp
                <tr>
                    <td>
                        <div style="font-weight:600; font-size:13px;">{{ $row['student']->name }}</div>
                        <div style="font-size:11.5px; color:var(--text-muted);">{{ $row['student']->student_id }}</div>
                    </td>
                    <td style="text-align:center; font-weight:700; color:#16a34a;">{{ $row['present'] }}</td>
                    <td style="text-align:center; font-weight:700; color:#dc2626;">{{ $row['absent'] }}</td>
                    <td style="text-align:center; font-weight:700; color:#d97706;">{{ $row['late'] }}</td>
                    <td style="text-align:center;">{{ $row['total'] }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div class="progress-bar-wrap" style="flex:1;">
                                <div class="progress-bar-fill" style="width:{{ $pct }}%; background:{{ $pct>=75?'var(--green)':'#f59e0b' }};"></div>
                            </div>
                            <span style="font-size:12px; font-weight:600; color:{{ $pct>=75?'var(--green)':'#d97706' }}; min-width:35px;">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card"><div class="empty-state" style="padding:60px;"><i class="bi bi-calendar-month" style="font-size:50px; color:var(--border);"></i><p style="margin-top:12px; font-size:15px; font-weight:600;">মাস ও শ্রেণী বেছে নিন</p></div></div>
@endif
@endsection
