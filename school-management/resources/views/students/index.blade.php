@extends('layouts.app')
@section('page-title', 'ছাত্র-ছাত্রী')
@section('breadcrumb', 'Home / Students')

@section('content')

<!-- Filter Bar -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 16px 22px;">
        <form method="GET" action="{{ route('students.index') }}">
            <div class="filter-bar">
                <div style="flex: 1; min-width: 200px;">
                    <div style="position: relative;">
                        <i class="bi bi-search" style="position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="text" name="search" class="form-control" placeholder="নাম বা আইডি দিয়ে খুঁজুন..." value="{{ request('search') }}"
                               style="padding-left: 34px;">
                    </div>
                </div>

                <select name="class_id" class="form-select" style="min-width: 140px;">
                    <option value="">সব শ্রেণী</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                    @endforeach
                </select>

                <select name="section_id" class="form-select" style="min-width: 120px;">
                    <option value="">সব বিভাগ</option>
                    @foreach($sections as $sec)
                    <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                    @endforeach
                </select>

                <select name="gender" class="form-select" style="min-width: 110px;">
                    <option value="">লিঙ্গ</option>
                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ছাত্র</option>
                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>ছাত্রী</option>
                </select>

                <select name="status" class="form-select" style="min-width: 110px;">
                    <option value="">সকল</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                    <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>বদলি</option>
                </select>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel"></i> ফিল্টার
                </button>

                <a href="{{ route('students.index') }}" class="btn btn-outline">
                    <i class="bi bi-x"></i> রিসেট
                </a>

                <a href="{{ route('students.create') }}" class="btn btn-primary" style="margin-left: auto;">
                    <i class="bi bi-plus-lg"></i> নতুন ভর্তি
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header" style="padding: 18px 22px 16px;">
        <div class="card-title"><i class="bi bi-people-fill"></i> ছাত্র তালিকা</div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <span style="font-size: 12.5px; color: var(--text-muted);">মোট: {{ $students->total() }} জন</span>
            <a href="{{ route('students.export') }}" class="btn btn-outline btn-sm">
                <i class="bi bi-download"></i> Excel
            </a>
        </div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>ছাত্র</th>
                    <th>পিতার নাম</th>
                    <th>শ্রেণী / বিভাগ</th>
                    <th>ফোন</th>
                    <th>লিঙ্গ</th>
                    <th>অবস্থা</th>
                    <th style="text-align:center">কার্যক্রম</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                <tr>
                    <td style="font-size: 12px; color: var(--text-muted); font-family: var(--font-en);">
                        {{ $students->firstItem() + $index }}
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            @if($student->photo)
                            <img src="{{ asset('storage/'.$student->photo) }}" class="student-avatar">
                            @else
                            <div class="avatar-placeholder">{{ mb_substr($student->name, 0, 1) }}</div>
                            @endif
                            <div>
                                <div style="font-weight: 600; font-size: 13.5px;">{{ $student->name }}</div>
                                <div style="font-size: 11.5px; color: var(--text-muted); font-family: var(--font-en);">{{ $student->student_id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size: 13px;">{{ $student->father_name }}</td>
                    <td>
                        <span class="badge badge-blue">{{ $student->studentClass->name ?? '—' }}</span>
                        @if($student->section)
                        <span class="badge badge-gray" style="margin-left: 4px;">{{ $student->section->name }}</span>
                        @endif
                    </td>
                    <td style="font-size: 13px; font-family: var(--font-en);">{{ $student->guardian_phone }}</td>
                    <td>
                        <span class="badge {{ $student->gender === 'male' ? 'badge-blue' : 'badge-purple' }}">
                            {{ $student->gender === 'male' ? '♂ ছাত্র' : '♀ ছাত্রী' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $student->status === 'active' ? 'badge-green' : ($student->status === 'transferred' ? 'badge-orange' : 'badge-red') }}">
                            {{ ['active' => 'সক্রিয়', 'inactive' => 'নিষ্ক্রিয়', 'transferred' => 'বদলি'][$student->status] }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="বিবরণ">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="সম্পাদনা">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('students.id-card', $student) }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="আইডি কার্ড" target="_blank">
                                <i class="bi bi-credit-card"></i>
                            </a>
                            <form method="POST" action="{{ route('students.destroy', $student) }}"
                                  onsubmit="return confirm('{{ $student->name }} কে মুছে ফেলবেন?')">
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
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <p>কোনো ছাত্র পাওয়া যায়নি</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
    <div class="pagination-wrap">
        {{ $students->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
