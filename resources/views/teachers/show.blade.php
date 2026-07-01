@extends('layouts.app')
@section('page-title', $teacher->name)
@section('breadcrumb', 'Teachers / Profile')
@section('content')

<div style="display:grid; grid-template-columns:300px 1fr; gap:20px; align-items:start;">

    <!-- Left: Profile -->
    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card">
            <div class="card-body" style="text-align:center; padding:28px 20px;">
                <div style="width:90px; height:90px; border-radius:50%; margin:0 auto 14px; overflow:hidden; border:3px solid var(--border);">
                    @if($teacher->photo)
                    <img src="{{ asset('storage/'.$teacher->photo) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                    <div style="width:100%; height:100%; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-size:36px; font-weight:700; color:#1d4ed8;">
                        {{ mb_substr($teacher->name,0,1) }}
                    </div>
                    @endif
                </div>
                <div style="font-size:18px; font-weight:700; margin-bottom:4px;">{{ $teacher->name }}</div>
                <div style="font-size:13px; color:var(--text-muted); margin-bottom:4px;">{{ $teacher->designation }}</div>
                <div style="font-size:12px; color:var(--text-muted); font-family:var(--font-en); margin-bottom:10px;">{{ $teacher->teacher_id }}</div>
                <span class="badge {{ $teacher->status==='active'?'badge-green':'badge-red' }}">
                    {{ $teacher->status==='active'?'● সক্রিয়':'● নিষ্ক্রিয়' }}
                </span>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title"><i class="bi bi-info-circle"></i> তথ্যসমূহ</div></div>
            <div class="card-body" style="padding-top:12px;">
                @foreach([
                    ['ফোন', $teacher->phone],
                    ['ইমেইল', $teacher->email??'—'],
                    ['লিঙ্গ', $teacher->gender==='male'?'পুরুষ':'নারী'],
                    ['যোগ্যতা', $teacher->qualification],
                    ['বিভাগ', $teacher->department??'—'],
                    ['বিষয়', $teacher->subject_specialty??'—'],
                    ['যোগদান', $teacher->joining_date->format('d/m/Y')],
                    ['মূল বেতন', '৳'.number_format($teacher->salary)],
                ] as [$label, $value])
                <div style="display:flex; justify-content:space-between; padding:7px 0; border-bottom:1px solid var(--border); font-size:13px;">
                    <span style="color:var(--text-muted);">{{ $label }}</span>
                    <span style="font-weight:600;">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:8px;">
            <a href="{{ route('teachers.edit',$teacher) }}" class="btn btn-primary" style="width:100%; justify-content:center;">
                <i class="bi bi-pencil"></i> তথ্য সম্পাদনা
            </a>
            <a href="{{ route('salaries.index') }}" class="btn btn-outline" style="width:100%; justify-content:center;">
                <i class="bi bi-wallet2"></i> বেতন ইতিহাস
            </a>
        </div>
    </div>

    <!-- Right: Salary History -->
    <div class="card">
        <div class="card-header" style="padding:16px 20px;">
            <div class="card-title"><i class="bi bi-wallet2"></i> বেতন ইতিহাস</div>
            <div style="font-size:13px; color:var(--green); font-weight:600;">মোট: ৳{{ number_format($totalEarned) }}</div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>মাস</th>
                        <th style="text-align:right">মূল বেতন</th>
                        <th style="text-align:right">বোনাস</th>
                        <th style="text-align:right">কর্তন</th>
                        <th style="text-align:right">নিট বেতন</th>
                        <th>পদ্ধতি</th>
                        <th>তারিখ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaryHistory as $payment)
                    <tr>
                        <td style="font-weight:600; font-family:var(--font-en);">{{ $payment->month_year }}</td>
                        <td style="text-align:right; font-family:var(--font-en);">৳{{ number_format($payment->basic_salary) }}</td>
                        <td style="text-align:right; font-family:var(--font-en); color:#16a34a;">
                            {{ $payment->bonus > 0 ? '+৳'.number_format($payment->bonus) : '—' }}
                        </td>
                        <td style="text-align:right; font-family:var(--font-en); color:#dc2626;">
                            {{ $payment->deduction > 0 ? '-৳'.number_format($payment->deduction) : '—' }}
                        </td>
                        <td style="text-align:right; font-family:var(--font-en); font-weight:700; color:var(--green);">৳{{ number_format($payment->net_salary) }}</td>
                        <td style="font-size:12.5px;">{{ ['cash'=>'নগদ','bank'=>'ব্যাংক','bkash'=>'bKash'][$payment->payment_method]??'—' }}</td>
                        <td style="font-size:12px; color:var(--text-muted); font-family:var(--font-en);">{{ $payment->payment_date->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('salaries.slip',$payment->id) }}" class="btn btn-icon btn-outline btn-sm" target="_blank" title="স্লিপ">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state" style="padding:40px;">
                                <i class="bi bi-wallet2"></i>
                                <p>কোনো বেতন পরিশোধের রেকর্ড নেই</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
