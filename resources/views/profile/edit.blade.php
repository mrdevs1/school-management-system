@extends('layouts.app')
@section('page-title', 'প্রোফাইল')
@section('breadcrumb', 'Home / Profile')
@section('content')

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; max-width:900px;">

    <!-- Profile Info -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-person-fill"></i> প্রোফাইল সম্পাদনা</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">নাম <span style="color:red">*</span></label>
                        <input type="text" name="name" class="form-control" required value="{{ auth()->user()->name }}">
                    </div>
                    <div>
                        <label class="form-label">ইমেইল <span style="color:red">*</span></label>
                        <input type="email" name="email" class="form-control" required value="{{ auth()->user()->email }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> আপডেট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Change -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-shield-lock-fill"></i> পাসওয়ার্ড পরিবর্তন</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf @method('PATCH')
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">বর্তমান পাসওয়ার্ড <span style="color:red">*</span></label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">নতুন পাসওয়ার্ড <span style="color:red">*</span></label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div>
                        <label class="form-label">পাসওয়ার্ড নিশ্চিত করুন <span style="color:red">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-key"></i> পাসওয়ার্ড পরিবর্তন করুন
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
