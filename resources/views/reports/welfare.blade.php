@extends('layouts.app')
@section('page-title', 'কল্যাণ রিপোর্ট')
@section('breadcrumb', 'Reports / Welfare')
@section('content')

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('reports.welfare') }}">
            <div class="filter-bar">
                <div>
                    <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">বছর</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button onclick="window.print()" type="button" class="btn btn-outline" style="align-self:flex-end;"><i class="bi bi-printer"></i> প্রিন্ট</button>
            </div>
        </form>
    </div>
</div>

@php
$typeLabels = ['scholarship'=>'🎓 বৃত্তি','book'=>'📚 বই','food'=>'🍱 খাদ্য','clothing'=>'👕 পোশাক','medical'=>'🏥 চিকিৎসা','other'=>'📌 অন্যান্য'];
@endphp

<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-heart-fill" style="color:#dc2626;"></i> ধরনওয়ারী সহায়তা — {{ $year }}</div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>সহায়তার ধরন</th><th style="text-align:center">সংখ্যা</th><th style="text-align:right">মোট</th><th style="text-align:right">প্রতিষ্ঠান</th><th style="text-align:right">ছাত্র</th><th style="text-align:right">দাতা</th></tr>
            </thead>
            <tbody>
                @forelse($byType as $item)
                <tr>
                    <td style="font-weight:600;">{{ $typeLabels[$item->type]??$item->type }}</td>
                    <td style="text-align:center; font-family:var(--font-en);">{{ $item->count }}</td>
                    <td style="text-align:right; font-family:var(--font-en); font-weight:700;">৳{{ number_format($item->total) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#1d4ed8;">৳{{ number_format($item->institution) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#16a34a;">৳{{ number_format($item->student) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#7c3aed;">৳{{ number_format($item->donor) }}</td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="bi bi-heart"></i><p>কোনো তথ্য নেই</p></div></td></tr>
                @endforelse
            </tbody>
            @if($byType->count())
            <tfoot>
                <tr style="background:#f0f4f2; font-weight:700;">
                    <td>মোট</td>
                    <td style="text-align:center; font-family:var(--font-en);">{{ $byType->sum('count') }}</td>
                    <td style="text-align:right; font-family:var(--font-en);">৳{{ number_format($byType->sum('total')) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#1d4ed8;">৳{{ number_format($byType->sum('institution')) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#16a34a;">৳{{ number_format($byType->sum('student')) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#7c3aed;">৳{{ number_format($byType->sum('donor')) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
