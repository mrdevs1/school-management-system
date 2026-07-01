@extends('layouts.app')
@section('page-title', 'ড্যাশবোর্ড')
@section('breadcrumb', 'Home / Dashboard')
@section('content')

<div style="display:grid; grid-template-columns:repeat(2,1fr); gap:16px; margin-bottom:22px;">
    <div class="stat-card stat-green">
        <div class="icon">🎓</div>
        <div class="value">{{ number_format($totalStudents) }}</div>
        <div class="label">মোট ছাত্র-ছাত্রী</div>
    </div>
    <div class="stat-card stat-blue">
        <div class="icon">👨‍🏫</div>
        <div class="value">{{ number_format($totalTeachers) }}</div>
        <div class="label">শিক্ষক ও কর্মচারী</div>
    </div>
    <div class="stat-card stat-orange">
        <div class="icon">💰</div>
        <div class="value">৳{{ number_format($monthlyCollection) }}</div>
        <div class="label">এ মাসে আয়</div>
    </div>
    <div class="stat-card stat-rose">
        <div class="icon">⚠️</div>
        <div class="value">৳{{ number_format($totalDue) }}</div>
        <div class="label">মোট বকেয়া</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;" class="responsive-grid">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-calendar-check-fill"></i> আজকের উপস্থিতি</div>
            <span style="font-size:12px; color:var(--text-muted);">{{ today()->format('d M Y') }}</span>
        </div>
        <div class="card-body">
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:16px;">
                <div style="text-align:center; padding:12px; background:#dcfce7; border-radius:10px;">
                    <div style="font-size:22px; font-weight:700; color:#16a34a;">{{ $todayPresent }}</div>
                    <div style="font-size:12px; color:#15803d;">উপস্থিত</div>
                </div>
                <div style="text-align:center; padding:12px; background:#fee2e2; border-radius:10px;">
                    <div style="font-size:22px; font-weight:700; color:#dc2626;">{{ $todayAbsent }}</div>
                    <div style="font-size:12px; color:#b91c1c;">অনুপস্থিত</div>
                </div>
                <div style="text-align:center; padding:12px; background:#fef3c7; border-radius:10px;">
                    <div style="font-size:22px; font-weight:700; color:#d97706;">{{ $todayLate }}</div>
                    <div style="font-size:12px; color:#b45309;">দেরিতে</div>
                </div>
            </div>
            @php $pct = $totalStudents > 0 ? round(($todayPresent/max($totalStudents,1))*100) : 0; @endphp
            <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:5px;">
                <span style="color:var(--text-muted);">উপস্থিতির হার</span>
                <span style="font-weight:700; color:var(--green);">{{ $pct }}%</span>
            </div>
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill" style="width:{{ $pct }}%"></div>
            </div>
            <div style="margin-top:12px; text-align:right;">
                <a href="{{ route('attendance.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> হাজিরা নিন
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-megaphone-fill"></i> সর্বশেষ নোটিশ</div>
            <a href="{{ route('notices.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> নতুন</a>
        </div>
        <div class="card-body" style="padding-top:12px;">
            @forelse($latestNotices as $notice)
            <div style="padding:10px 0; border-bottom:1px solid var(--border); display:flex; gap:10px; align-items:flex-start;">
                <div style="width:34px; height:34px; border-radius:8px; flex-shrink:0; background:#dbeafe; display:flex; align-items:center; justify-content:center; font-size:16px;">
                    {{ ['all'=>'📢','students'=>'🎓','teachers'=>'👩‍🏫','parents'=>'👪'][$notice->audience] ?? '📌' }}
                </div>
                <div>
                    <div style="font-size:13px; font-weight:600;">{{ $notice->title }}</div>
                    <div style="font-size:11.5px; color:var(--text-muted);">{{ $notice->publish_date->format('d M Y') }}</div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:24px 0;">
                <i class="bi bi-megaphone" style="font-size:28px;"></i>
                <p style="margin-top:8px; font-size:13px;">কোনো নোটিশ নেই</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1.5fr 1fr; gap:20px;" class="responsive-grid">
    <div class="card">
        <div class="card-header" style="padding:16px 20px;">
            <div class="card-title"><i class="bi bi-person-plus-fill"></i> সাম্প্রতিক ভর্তি</div>
            <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> নতুন ভর্তি</a>
        </div>
        <div class="table-wrap" style="margin-top:12px;">
            <table class="data-table">
                <thead><tr><th>ছাত্র</th><th>শ্রেণী</th><th>ফোন</th><th>তারিখ</th></tr></thead>
                <tbody>
                    @forelse($recentStudents as $s)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:9px;">
                                <div class="avatar-placeholder">{{ mb_substr($s->name,0,1) }}</div>
                                <div>
                                    <div style="font-weight:600; font-size:13px;">{{ $s->name }}</div>
                                    <div style="font-size:11.5px; color:var(--text-muted); font-family:var(--font-en);">{{ $s->student_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-blue">{{ $s->studentClass->name ?? '—' }}</span></td>
                        <td style="font-size:12.5px; font-family:var(--font-en);">{{ $s->guardian_phone }}</td>
                        <td style="font-size:12px; color:var(--text-muted); font-family:var(--font-en);">{{ $s->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4"><div class="empty-state" style="padding:24px;"><p>কোনো ছাত্র নেই</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 20px; border-top:1px solid var(--border);">
            <a href="{{ route('students.index') }}" style="font-size:13px; color:var(--green); font-weight:500;">সব ছাত্র দেখুন →</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-exclamation-circle-fill" style="color:#f59e0b;"></i> বকেয়া বেতন</div>
            <a href="{{ route('fees.due') }}" class="btn btn-outline btn-sm">সব দেখুন</a>
        </div>
        <div class="card-body" style="padding-top:12px;">
            @forelse($dueFees as $fee)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border);">
                <div style="display:flex; align-items:center; gap:8px;">
                    <div class="avatar-placeholder" style="width:30px; height:30px; font-size:12px;">{{ mb_substr($fee->student->name??'X',0,1) }}</div>
                    <div>
                        <div style="font-size:13px; font-weight:600;">{{ $fee->student->name ?? '—' }}</div>
                        <div style="font-size:11.5px; color:var(--text-muted);">{{ $fee->student->studentClass->name ?? '—' }}</div>
                    </div>
                </div>
                <span class="badge badge-red">৳{{ number_format($fee->total_due) }}</span>
            </div>
            @empty
            <div class="empty-state" style="padding:22px 0;">
                <i class="bi bi-check-circle" style="font-size:28px; color:#16a34a;"></i>
                <p style="margin-top:8px; font-size:13px; color:#15803d;">সব বেতন পরিশোধ!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
