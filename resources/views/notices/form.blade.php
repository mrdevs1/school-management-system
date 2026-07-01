@extends('layouts.app')
@section('page-title', isset($notice)?'নোটিশ সম্পাদনা':'নতুন নোটিশ')
@section('content')
<div style="max-width:700px; margin:0 auto;">
<form method="POST" action="{{ isset($notice)?route('notices.update',$notice):route('notices.store') }}">
    @csrf @if(isset($notice)) @method('PUT') @endif
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-megaphone-fill"></i> নোটিশ তথ্য</div></div>
        <div class="card-body">
            <div class="form-grid" style="gap:14px;">
                <div>
                    <label class="form-label">শিরোনাম <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title',$notice->title??'') }}" placeholder="নোটিশের শিরোনাম">
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px;">
                    <div>
                        <label class="form-label">দর্শক <span style="color:red">*</span></label>
                        <select name="audience" class="form-select" required>
                            <option value="all" {{ old('audience',$notice->audience??'')==='all'?'selected':'' }}>📢 সবাই</option>
                            <option value="students" {{ old('audience',$notice->audience??'')==='students'?'selected':'' }}>🎓 ছাত্র</option>
                            <option value="teachers" {{ old('audience',$notice->audience??'')==='teachers'?'selected':'' }}>👩‍🏫 শিক্ষক</option>
                            <option value="parents" {{ old('audience',$notice->audience??'')==='parents'?'selected':'' }}>👪 অভিভাবক</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">প্রকাশের তারিখ <span style="color:red">*</span></label>
                        <input type="date" name="publish_date" class="form-control" required value="{{ old('publish_date',isset($notice)?$notice->publish_date->format('Y-m-d'):today()->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label class="form-label">মেয়াদ শেষ</label>
                        <input type="date" name="expire_date" class="form-control" value="{{ old('expire_date',isset($notice)?$notice->expire_date?->format('Y-m-d'):'') }}">
                    </div>
                </div>
                <div>
                    <label class="form-label">বিষয়বস্তু <span style="color:red">*</span></label>
                    <textarea name="content" class="form-control" rows="5" required placeholder="নোটিশের বিস্তারিত লিখুন...">{{ old('content',$notice->content??'') }}</textarea>
                </div>
            </div>
        </div>
        <div style="padding:14px 20px; border-top:1px solid var(--border); display:flex; gap:10px; justify-content:flex-end;">
            <a href="{{ route('notices.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> বাতিল</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> {{ isset($notice)?'আপডেট':'প্রকাশ করুন' }}</button>
        </div>
    </div>
</form>
</div>
@endsection
