@extends('layouts.app')
@section('page-title', $student->name)
@section('breadcrumb', 'Students / Profile')

@section('content')

<div style="display:grid; grid-template-columns:320px 1fr; gap:20px; align-items:start;">

    <!-- Left: Profile Card -->
    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card">
            <div class="card-body" style="text-align:center; padding:28px 20px;">
                <div style="width:90px; height:90px; border-radius:50%; margin:0 auto 14px; overflow:hidden; border:3px solid var(--border);">
                    @if($student->photo)
                    <img src="{{ asset('storage/'.$student->photo) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                    <div style="width:100%; height:100%; background:var(--green-light); display:flex; align-items:center; justify-content:center; font-size:36px; font-weight:700; color:var(--green);">
                        {{ mb_substr($student->name, 0, 1) }}
                    </div>
                    @endif
                </div>
                <div style="font-size:18px; font-weight:700; margin-bottom:4px;">{{ $student->name }}</div>
                <div style="font-size:13px; color:var(--text-muted); font-family:var(--font-en); margin-bottom:10px;">{{ $student->student_id }}</div>
                <span class="badge {{ $student->status === 'active' ? 'badge-green' : 'badge-red' }}">
                    {{ $student->status === 'active' ? '● সক্রিয়' : '● নিষ্ক্রিয়' }}
                </span>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title"><i class="bi bi-info-circle"></i> তথ্যসমূহ</div></div>
            <div class="card-body" style="padding-top:12px;">
                @foreach([
                    ['শ্রেণী',       $student->studentClass->name ?? '—'],
                    ['বিভাগ',        $student->section->name ?? '—'],
                    ['শিক্ষাবর্ষ',  $student->session->name ?? '—'],
                    ['রোল',          $student->roll_number ?? '—'],
                    ['লিঙ্গ',        $student->gender === 'male' ? '♂ ছাত্র' : '♀ ছাত্রী'],
                    ['ধর্ম',         $student->religion],
                    ['রক্তের গ্রুপ', $student->blood_group ?? '—'],
                    ['জন্ম তারিখ',  $student->date_of_birth->format('d/m/Y')],
                ] as [$label, $value])
                <div style="display:flex; justify-content:space-between; align-items:center; padding:7px 0; border-bottom:1px solid var(--border); font-size:13px;">
                    <span style="color:var(--text-muted);">{{ $label }}</span>
                    <span style="font-weight:600;">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:8px;">
            <a href="{{ route('students.edit', $student) }}" class="btn btn-primary" style="width:100%; justify-content:center;">
                <i class="bi bi-pencil"></i> তথ্য সম্পাদনা
            </a>
            <a href="{{ route('students.id-card', $student) }}" class="btn btn-outline" style="width:100%; justify-content:center;" target="_blank">
                <i class="bi bi-credit-card"></i> আইডি কার্ড PDF
            </a>
            <a href="{{ route('fees.ledger', $student) }}" class="btn btn-outline" style="width:100%; justify-content:center;">
                <i class="bi bi-cash-coin"></i> ফি ইতিহাস
            </a>
        </div>
    </div>

    <!-- Right: Details -->
    <div style="display:flex; flex-direction:column; gap:16px;">

        <!-- Summary Stats -->
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px;">
            <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:16px 18px; text-align:center;">
                <div style="font-size:22px; font-weight:700; color:var(--green);">৳{{ number_format($totalPaid) }}</div>
                <div style="font-size:11.5px; color:var(--text-muted); margin-top:4px;">মোট পরিশোধ</div>
            </div>
            <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:16px 18px; text-align:center;">
                <div style="font-size:22px; font-weight:700; color:{{ $totalDue > 0 ? '#dc2626' : '#16a34a' }};">৳{{ number_format($totalDue) }}</div>
                <div style="font-size:11.5px; color:var(--text-muted); margin-top:4px;">বকেয়া</div>
            </div>
            <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:16px 18px; text-align:center;">
                <div style="font-size:22px; font-weight:700; color:#1d4ed8;">{{ $presentDays }}</div>
                <div style="font-size:11.5px; color:var(--text-muted); margin-top:4px;">উপস্থিত দিন</div>
            </div>
            <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:16px 18px; text-align:center;">
                @php $pct = $totalDays > 0 ? round(($presentDays/$totalDays)*100) : 0; @endphp
                <div style="font-size:22px; font-weight:700; color:{{ $pct >= 75 ? '#16a34a' : '#d97706' }};">{{ $pct }}%</div>
                <div style="font-size:11.5px; color:var(--text-muted); margin-top:4px;">উপস্থিতির হার</div>
            </div>
        </div>

        <!-- Guardian Info -->
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="bi bi-people-fill"></i> অভিভাবকের তথ্য</div></div>
            <div class="card-body">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    @foreach([
                        ['পিতার নাম', $student->father_name],
                        ['মাতার নাম', $student->mother_name],
                        ['ফোন', $student->guardian_phone],
                        ['ইমেইল', $student->guardian_email ?? '—'],
                        ['পেশা', $student->guardian_occupation ?? '—'],
                    ] as [$label, $value])
                    <div>
                        <div style="font-size:11.5px; color:var(--text-muted); margin-bottom:3px;">{{ $label }}</div>
                        <div style="font-size:13.5px; font-weight:600;">{{ $value }}</div>
                    </div>
                    @endforeach
                    <div style="grid-column:span 2;">
                        <div style="font-size:11.5px; color:var(--text-muted); margin-bottom:3px;">ঠিকানা</div>
                        <div style="font-size:13.5px; font-weight:600;">{{ $student->address }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Fee Payments -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-cash-coin"></i> সাম্প্রতিক ফি পেমেন্ট</div>
                <a href="{{ route('fees.ledger', $student) }}" style="font-size:13px; color:var(--green);">সব দেখুন →</a>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th>রশিদ</th><th>ফি ধরন</th><th>পরিশোধ</th><th>বকেয়া</th><th>তারিখ</th></tr></thead>
                    <tbody>
                        @forelse($student->feeCollections->take(5) as $fee)
                        <tr>
                            <td style="font-family:var(--font-en); font-size:12px; color:var(--green); font-weight:600;">{{ $fee->receipt_no }}</td>
                            <td style="font-size:13px;">{{ $fee->feeCategory->name ?? '—' }}</td>
                            <td style="font-family:var(--font-en); font-weight:600; color:#16a34a;">৳{{ number_format($fee->paid_amount) }}</td>
                            <td>
                                @if($fee->due_amount > 0)
                                <span class="badge badge-red">৳{{ number_format($fee->due_amount) }}</span>
                                @else
                                <span class="badge badge-green">পরিশোধ</span>
                                @endif
                            </td>
                            <td style="font-size:12px; color:var(--text-muted); font-family:var(--font-en);">{{ $fee->payment_date->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state" style="padding:20px;"><p>কোনো পেমেন্ট নেই</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
