@extends('layouts.app')
@section('page-title', 'ছাত্র রিপোর্ট')
@section('breadcrumb', 'Reports / Students')
@section('content')

<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:22px;">
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px; text-align:center;">
        <div style="font-size:32px; font-weight:700; color:var(--green);">{{ $totalStudents }}</div>
        <div style="font-size:13px; color:var(--text-muted); margin-top:4px;">মোট ছাত্র-ছাত্রী</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px; text-align:center;">
        <div style="font-size:32px; font-weight:700; color:#1d4ed8;">{{ $totalMale }}</div>
        <div style="font-size:13px; color:var(--text-muted); margin-top:4px;">♂ ছাত্র</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px; text-align:center;">
        <div style="font-size:32px; font-weight:700; color:#db2777;">{{ $totalFemale }}</div>
        <div style="font-size:13px; color:var(--text-muted); margin-top:4px;">♀ ছাত্রী</div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-people-fill"></i> শ্রেণীওয়ারী ছাত্র সংখ্যা</div>
        <button onclick="window.print()" class="btn btn-outline btn-sm"><i class="bi bi-printer"></i> প্রিন্ট</button>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>শ্রেণী</th><th>ধরন</th><th style="text-align:center">মোট</th><th style="text-align:center">♂ ছাত্র</th><th style="text-align:center">♀ ছাত্রী</th><th style="text-align:center">হার</th></tr>
            </thead>
            <tbody>
                @foreach($classes as $class)
                @if($class->total > 0)
                <tr>
                    <td style="font-weight:600;">{{ $class->name }}</td>
                    <td><span class="badge {{ $class->type==='school'?'badge-blue':($class->type==='madrasa'?'badge-green':'badge-purple') }}">{{ ['school'=>'স্কুল','madrasa'=>'মাদ্রাসা','both'=>'উভয়'][$class->type] }}</span></td>
                    <td style="text-align:center; font-weight:700; font-family:var(--font-en);">{{ $class->total }}</td>
                    <td style="text-align:center; color:#1d4ed8; font-family:var(--font-en);">{{ $class->male }}</td>
                    <td style="text-align:center; color:#db2777; font-family:var(--font-en);">{{ $class->female }}</td>
                    <td style="text-align:center;">
                        @php $mPct = $class->total > 0 ? round(($class->male/$class->total)*100) : 0; @endphp
                        <div style="display:flex; align-items:center; gap:6px;">
                            <div class="progress-bar-wrap" style="flex:1; height:5px;">
                                <div class="progress-bar-fill" style="width:{{ $mPct }}%; background:#1d4ed8;"></div>
                            </div>
                            <span style="font-size:11.5px; color:var(--text-muted); min-width:30px; font-family:var(--font-en);">{{ $mPct }}%</span>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f0f4f2; font-weight:700;">
                    <td colspan="2">মোট</td>
                    <td style="text-align:center; font-family:var(--font-en);">{{ $totalStudents }}</td>
                    <td style="text-align:center; color:#1d4ed8; font-family:var(--font-en);">{{ $totalMale }}</td>
                    <td style="text-align:center; color:#db2777; font-family:var(--font-en);">{{ $totalFemale }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
