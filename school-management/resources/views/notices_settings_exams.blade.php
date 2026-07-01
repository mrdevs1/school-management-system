{{-- ================================================================ --}}
{{-- resources/views/notices/index.blade.php --}}
{{-- ================================================================ --}}
@extends('layouts.app')
@section('page-title', 'নোটিশ বোর্ড')
@section('breadcrumb', 'Home / Notices')
@section('content')

<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-megaphone-fill"></i> নোটিশ তালিকা</div>
        <a href="{{ route('notices.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> নতুন নোটিশ</a>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>শিরোনাম</th>
                    <th>দর্শক</th>
                    <th>প্রকাশের তারিখ</th>
                    <th>মেয়াদ শেষ</th>
                    <th>প্রকাশক</th>
                    <th style="text-align:center">কার্যক্রম</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notices as $notice)
                <tr>
                    <td>
                        <div style="font-weight:600; font-size:13.5px;">{{ $notice->title }}</div>
                        <div style="font-size:12px; color:var(--text-muted); margin-top:3px;">{{ Str::limit($notice->content, 60) }}</div>
                    </td>
                    <td>
                        @php $audienceMap = ['all'=>['📢','সবাই','badge-blue'],'students'=>['🎓','ছাত্র','badge-green'],'teachers'=>['👩‍🏫','শিক্ষক','badge-purple'],'parents'=>['👪','অভিভাবক','badge-orange']]; @endphp
                        <span class="badge {{ $audienceMap[$notice->audience][2] ?? 'badge-gray' }}">
                            {{ $audienceMap[$notice->audience][0] }} {{ $audienceMap[$notice->audience][1] }}
                        </span>
                    </td>
                    <td style="font-size:12.5px; font-family:var(--font-en);">{{ $notice->publish_date->format('d M Y') }}</td>
                    <td style="font-size:12.5px; color:var(--text-muted); font-family:var(--font-en);">
                        {{ $notice->expire_date?->format('d M Y') ?? '—' }}
                    </td>
                    <td style="font-size:12.5px;">{{ $notice->creator->name ?? '—' }}</td>
                    <td>
                        <div style="display:flex; gap:5px; justify-content:center;">
                            <a href="{{ route('notices.edit', $notice) }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="সম্পাদনা"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('notices.destroy', $notice) }}" onsubmit="return confirm('মুছে ফেলবেন?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="bi bi-megaphone"></i><p>কোনো নোটিশ নেই</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notices->hasPages())
    <div class="pagination-wrap">{{ $notices->links() }}</div>
    @endif
</div>
@endsection


{{-- ================================================================ --}}
{{-- resources/views/notices/form.blade.php --}}
{{-- ================================================================ --}}
@extends('layouts.app')
@section('page-title', isset($notice) ? 'নোটিশ সম্পাদনা' : 'নতুন নোটিশ')
@section('content')
<div style="max-width:700px; margin:0 auto;">
<form method="POST" action="{{ isset($notice) ? route('notices.update', $notice) : route('notices.store') }}">
    @csrf @if(isset($notice)) @method('PUT') @endif
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-megaphone-fill"></i> নোটিশ তথ্য</div></div>
        <div class="card-body">
            <div class="form-grid" style="gap:16px;">
                <div>
                    <label class="form-label">শিরোনাম <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title', $notice->title ?? '') }}" placeholder="নোটিশের শিরোনাম">
                </div>
                <div class="form-grid form-grid-3">
                    <div>
                        <label class="form-label">দর্শক <span style="color:red">*</span></label>
                        <select name="audience" class="form-select" required>
                            <option value="all"      {{ old('audience', $notice->audience ?? '') === 'all'      ? 'selected' : '' }}>📢 সবাই</option>
                            <option value="students" {{ old('audience', $notice->audience ?? '') === 'students' ? 'selected' : '' }}>🎓 ছাত্র-ছাত্রী</option>
                            <option value="teachers" {{ old('audience', $notice->audience ?? '') === 'teachers' ? 'selected' : '' }}>👩‍🏫 শিক্ষকমণ্ডলী</option>
                            <option value="parents"  {{ old('audience', $notice->audience ?? '') === 'parents'  ? 'selected' : '' }}>👪 অভিভাবক</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">প্রকাশের তারিখ <span style="color:red">*</span></label>
                        <input type="date" name="publish_date" class="form-control" required value="{{ old('publish_date', isset($notice) ? $notice->publish_date->format('Y-m-d') : today()->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label class="form-label">মেয়াদ শেষ</label>
                        <input type="date" name="expire_date" class="form-control" value="{{ old('expire_date', isset($notice) ? $notice->expire_date?->format('Y-m-d') : '') }}">
                    </div>
                </div>
                <div>
                    <label class="form-label">বিষয়বস্তু <span style="color:red">*</span></label>
                    <textarea name="content" class="form-control" rows="6" required placeholder="নোটিশের বিস্তারিত লিখুন...">{{ old('content', $notice->content ?? '') }}</textarea>
                </div>
            </div>
        </div>
        <div style="padding:16px 22px; border-top:1px solid var(--border); display:flex; gap:10px; justify-content:flex-end;">
            <a href="{{ route('notices.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> বাতিল</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> {{ isset($notice) ? 'আপডেট' : 'প্রকাশ করুন' }}</button>
        </div>
    </div>
</form>
</div>
@endsection


{{-- ================================================================ --}}
{{-- resources/views/settings/index.blade.php --}}
{{-- ================================================================ --}}
@extends('layouts.app')
@section('page-title', 'সেটিংস')
@section('breadcrumb', 'Home / Settings')
@section('content')

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    <!-- School Info -->
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-building"></i> প্রতিষ্ঠানের তথ্য</div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">প্রতিষ্ঠানের নাম</label>
                        <input type="text" name="school_name" class="form-control" value="{{ config('school.name') }}">
                    </div>
                    <div>
                        <label class="form-label">ঠিকানা</label>
                        <textarea name="school_address" class="form-control" rows="2">{{ config('school.address') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">ফোন</label>
                        <input type="text" name="school_phone" class="form-control" value="{{ config('school.phone') }}">
                    </div>
                    <div>
                        <label class="form-label">ইমেইল</label>
                        <input type="email" name="school_email" class="form-control" value="{{ config('school.email') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sessions -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-calendar-range"></i> শিক্ষাবর্ষ</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('settings.store-session') }}">
                @csrf
                <div class="form-grid form-grid-2" style="gap:12px; margin-bottom:16px;">
                    <div style="grid-column:span 2;">
                        <label class="form-label">নতুন শিক্ষাবর্ষ</label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: 2025-2026">
                    </div>
                    <div>
                        <label class="form-label">শুরু</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">শেষ</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                    <div style="grid-column:span 2;">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> যোগ করুন</button>
                    </div>
                </div>
            </form>
            <div style="border-top:1px solid var(--border); padding-top:14px;">
                @foreach($sessions as $session)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid var(--border);">
                    <div>
                        <span style="font-weight:600; font-size:13.5px;">{{ $session->name }}</span>
                        @if($session->is_current)
                        <span class="badge badge-green" style="margin-left:8px;">চলমান</span>
                        @endif
                    </div>
                    @if(!$session->is_current)
                    <form method="POST" action="{{ route('settings.set-current-session', $session) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">চলমান করুন</button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection


{{-- ================================================================ --}}
{{-- resources/views/exams/index.blade.php --}}
{{-- ================================================================ --}}
@extends('layouts.app')
@section('page-title', 'পরীক্ষাসমূহ')
@section('breadcrumb', 'Home / Exams')
@section('content')

<div style="display:grid; grid-template-columns:1fr 360px; gap:20px; align-items:start;">
    <div class="card">
        <div class="card-header" style="padding:16px 22px;">
            <div class="card-title"><i class="bi bi-pencil-square"></i> পরীক্ষার তালিকা</div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>পরীক্ষার নাম</th><th>শিক্ষাবর্ষ</th><th>শুরু</th><th>শেষ</th><th style="text-align:center">কার্যক্রম</th></tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                    <tr>
                        <td style="font-weight:600; font-size:13.5px;">{{ $exam->name }}</td>
                        <td><span class="badge badge-blue">{{ $exam->session->name ?? '—' }}</span></td>
                        <td style="font-size:12.5px; font-family:var(--font-en);">{{ $exam->start_date->format('d M Y') }}</td>
                        <td style="font-size:12.5px; font-family:var(--font-en);">{{ $exam->end_date->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex; gap:5px; justify-content:center;">
                                <a href="{{ route('results.index') }}?tab=entry&exam_id={{ $exam->id }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="নম্বর এন্ট্রি"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('exams.edit', $exam) }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="সম্পাদনা"><i class="bi bi-pen"></i></a>
                                <form method="POST" action="{{ route('exams.destroy', $exam) }}" onsubmit="return confirm('মুছবেন?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-pencil-square"></i><p>কোনো পরীক্ষা নেই</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick add form -->
    <div class="card" style="position:sticky; top:84px;">
        <div class="card-header"><div class="card-title"><i class="bi bi-plus-circle-fill"></i> নতুন পরীক্ষা</div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('exams.store') }}">
                @csrf
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">পরীক্ষার নাম <span style="color:red">*</span></label>
                        <select name="name" class="form-select" required>
                            <option value="">বেছে নিন</option>
                            @foreach(['প্রথম সাময়িক পরীক্ষা','দ্বিতীয় সাময়িক পরীক্ষা','তৃতীয় সাময়িক পরীক্ষা','বার্ষিক পরীক্ষা','নির্বাচনি পরীক্ষা','টেস্ট পরীক্ষা'] as $n)
                            <option value="{{ $n }}">{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">শিক্ষাবর্ষ <span style="color:red">*</span></label>
                        <select name="session_id" class="form-select" required>
                            @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ $session->is_current ? 'selected' : '' }}>{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">শুরুর তারিখ <span style="color:red">*</span></label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">শেষের তারিখ <span style="color:red">*</span></label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">বিবরণ</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="ঐচ্ছিক..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="bi bi-plus-lg"></i> পরীক্ষা তৈরি করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
