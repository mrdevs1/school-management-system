@extends('layouts.app')
@section('page-title', 'বিষয়সমূহ')
@section('breadcrumb', 'Home / Subjects')
@section('content')
<div class="responsive-grid" style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">
    <div class="card">
        <div class="card-header" style="padding:16px 20px;">
            <div class="card-title"><i class="bi bi-journal-bookmark-fill"></i> বিষয় তালিকা</div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>বিষয়ের নাম</th><th>কোড</th><th>শ্রেণী</th><th>পূর্ণমান</th><th>পাস মার্ক</th><th>ধরন</th><th style="text-align:center">কার্যক্রম</th></tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td style="font-weight:600;">{{ $subject->name }}</td>
                        <td style="font-family:var(--font-en); font-size:12px;">{{ $subject->code }}</td>
                        <td><span class="badge badge-blue">{{ $subject->studentClass->name ?? '—' }}</span></td>
                        <td style="font-family:var(--font-en); text-align:center;">{{ $subject->full_marks }}</td>
                        <td style="font-family:var(--font-en); text-align:center;">{{ $subject->pass_marks }}</td>
                        <td><span class="badge badge-gray">{{ ['theory'=>'তাত্ত্বিক','practical'=>'ব্যবহারিক','viva'=>'মৌখিক'][$subject->type] }}</span></td>
                        <td style="text-align:center;">
                            <form method="POST" action="{{ route('subjects.destroy', $subject) }}" onsubmit="return confirm('মুছবেন?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7"><div class="empty-state"><i class="bi bi-journal-bookmark"></i><p>কোনো বিষয় নেই</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="position:sticky; top:84px;">
        <div class="card-header"><div class="card-title"><i class="bi bi-plus-circle-fill"></i> নতুন বিষয়</div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('subjects.store') }}">
                @csrf
                <div class="form-grid" style="gap:12px;">
                    <div>
                        <label class="form-label">বিষয়ের নাম <span style="color:red">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="যেমন: গণিত">
                    </div>
                    <div>
                        <label class="form-label">বিষয় কোড <span style="color:red">*</span></label>
                        <input type="text" name="code" class="form-control" required placeholder="যেমন: MATH-6">
                    </div>
                    <div>
                        <label class="form-label">শ্রেণী <span style="color:red">*</span></label>
                        <select name="class_id" class="form-select" required>
                            <option value="">বেছে নিন</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="responsive-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                        <div>
                            <label class="form-label">পূর্ণমান</label>
                            <input type="number" name="full_marks" class="form-control" value="100" min="1">
                        </div>
                        <div>
                            <label class="form-label">পাস মার্ক</label>
                            <input type="number" name="pass_marks" class="form-control" value="33" min="1">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">ধরন</label>
                        <select name="type" class="form-select">
                            <option value="theory">তাত্ত্বিক</option>
                            <option value="practical">ব্যবহারিক</option>
                            <option value="viva">মৌখিক</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="bi bi-plus-lg"></i> বিষয় যোগ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
