@extends('layouts.app')
@section('page-title', 'শিক্ষক ও কর্মচারী')
@section('breadcrumb', 'Home / Teachers')

@section('content')

<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 22px;">
        <form method="GET" action="{{ route('teachers.index') }}">
            <div class="filter-bar">
                <div style="flex: 1; min-width: 200px; position: relative;">
                    <i class="bi bi-search" style="position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--text-muted);"></i>
                    <input type="text" name="search" class="form-control" placeholder="নাম বা আইডি দিয়ে খুঁজুন..."
                           value="{{ request('search') }}" style="padding-left: 34px;">
                </div>
                <select name="gender" class="form-select" style="min-width:110px;">
                    <option value="">লিঙ্গ</option>
                    <option value="male"   {{ request('gender')=='male'   ? 'selected' : '' }}>পুরুষ</option>
                    <option value="female" {{ request('gender')=='female' ? 'selected' : '' }}>নারী</option>
                </select>
                <select name="status" class="form-select" style="min-width:110px;">
                    <option value="">সকল</option>
                    <option value="active"   {{ request('status')=='active'   ? 'selected' : '' }}>সক্রিয়</option>
                    <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                </select>
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> ফিল্টার</button>
                <a href="{{ route('teachers.index') }}" class="btn btn-outline"><i class="bi bi-x"></i></a>
                <a href="{{ route('teachers.create') }}" class="btn btn-primary" style="margin-left:auto;">
                    <i class="bi bi-plus-lg"></i> নতুন শিক্ষক
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding: 16px 22px;">
        <div class="card-title"><i class="bi bi-person-badge-fill"></i> শিক্ষক তালিকা</div>
        <span style="font-size:12.5px; color:var(--text-muted);">মোট: {{ $teachers->total() }} জন</span>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>শিক্ষক</th>
                    <th>পদবি</th>
                    <th>যোগ্যতা</th>
                    <th>ফোন</th>
                    <th style="text-align:right">বেতন</th>
                    <th>যোগদান</th>
                    <th>অবস্থা</th>
                    <th style="text-align:center">কার্যক্রম</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            @if($teacher->photo)
                            <img src="{{ asset('storage/'.$teacher->photo) }}" class="student-avatar">
                            @else
                            <div class="avatar-placeholder" style="background:#dbeafe; color:#1d4ed8;">
                                {{ mb_substr($teacher->name, 0, 1) }}
                            </div>
                            @endif
                            <div>
                                <div style="font-weight:600; font-size:13.5px;">{{ $teacher->name }}</div>
                                <div style="font-size:11.5px; color:var(--text-muted); font-family:var(--font-en);">{{ $teacher->teacher_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $teacher->designation }}</td>
                    <td style="font-size:12.5px; color:var(--text-muted);">{{ $teacher->qualification }}</td>
                    <td style="font-size:13px; font-family:var(--font-en);">{{ $teacher->phone }}</td>
                    <td style="text-align:right; font-family:var(--font-en); font-weight:600; color:var(--green);">
                        ৳{{ number_format($teacher->salary) }}
                    </td>
                    <td style="font-size:12px; color:var(--text-muted); font-family:var(--font-en);">
                        {{ $teacher->joining_date->format('d/m/Y') }}
                    </td>
                    <td>
                        <span class="badge {{ $teacher->status === 'active' ? 'badge-green' : 'badge-red' }}">
                            {{ $teacher->status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:5px; justify-content:center;">
                            <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="বিবরণ">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="সম্পাদনা">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('salaries.index') }}?teacher_id={{ $teacher->id }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="বেতন">
                                <i class="bi bi-wallet2"></i>
                            </a>
                            <form method="POST" action="{{ route('teachers.destroy', $teacher) }}"
                                  onsubmit="return confirm('{{ $teacher->name }} কে মুছে ফেলবেন?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-sm" data-tooltip="মুছুন">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state"><i class="bi bi-person-badge"></i><p>কোনো শিক্ষক পাওয়া যায়নি</p></div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($teachers->hasPages())
    <div class="pagination-wrap">{{ $teachers->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
