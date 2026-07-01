@extends('layouts.app')
@section('page-title', 'পরীক্ষাসমূহ')
@section('breadcrumb', 'Home / Exams')
@section('content')
<div class="responsive-grid" style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">
    <div class="card">
        <div class="card-header" style="padding:16px 20px;">
            <div class="card-title"><i class="bi bi-pencil-square"></i> পরীক্ষার তালিকা</div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>পরীক্ষার নাম</th><th>শিক্ষাবর্ষ</th><th>শুরু</th><th>শেষ</th><th style="text-align:center">কার্যক্রম</th></tr></thead>
                <tbody>
                    @forelse($exams as $exam)
                    <tr>
                        <td style="font-weight:600;">{{ $exam->name }}</td>
                        <td><span class="badge badge-blue">{{ $exam->session->name??'—' }}</span></td>
                        <td style="font-size:12.5px; font-family:var(--font-en);">{{ $exam->start_date->format('d M Y') }}</td>
                        <td style="font-size:12.5px; font-family:var(--font-en);">{{ $exam->end_date->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex; gap:5px; justify-content:center;">
                                <a href="{{ route('results.index') }}?tab=entry&exam_id={{ $exam->id }}" class="btn btn-icon btn-outline btn-sm" title="নম্বর এন্ট্রি"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('exams.destroy',$exam) }}" onsubmit="return confirm('মুছবেন?')">
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

    <div class="card" style="position:sticky; top:84px;">
        <div class="card-header"><div class="card-title"><i class="bi bi-plus-circle-fill"></i> নতুন পরীক্ষা</div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('exams.store') }}">
                @csrf
                <div class="form-grid" style="gap:12px;">
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
                            <option value="{{ $session->id }}" {{ $session->is_current?'selected':'' }}>{{ $session->name }}</option>
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
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="bi bi-plus-lg"></i> পরীক্ষা তৈরি করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
