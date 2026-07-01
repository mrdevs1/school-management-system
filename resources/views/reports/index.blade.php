@extends('layouts.app')
@section('page-title', 'রিপোর্ট')
@section('breadcrumb', 'Home / Reports')
@section('content')

<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">

    <a href="{{ route('reports.financial') }}" style="text-decoration:none;">
        <div class="card" style="padding:28px; text-align:center; transition:all 0.2s; cursor:pointer;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:48px; margin-bottom:14px;">💰</div>
            <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:6px;">আর্থিক রিপোর্ট</div>
            <div style="font-size:13px; color:var(--text-muted);">ফি সংগ্রহ, বকেয়া, মাসিক আয়-ব্যয়</div>
        </div>
    </a>

    <a href="{{ route('reports.students') }}" style="text-decoration:none;">
        <div class="card" style="padding:28px; text-align:center; transition:all 0.2s; cursor:pointer;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:48px; margin-bottom:14px;">🎓</div>
            <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:6px;">ছাত্র রিপোর্ট</div>
            <div style="font-size:13px; color:var(--text-muted);">শ্রেণীওয়ারী ছাত্র সংখ্যা, ছেলে-মেয়ে</div>
        </div>
    </a>

    <a href="{{ route('reports.attendance') }}" style="text-decoration:none;">
        <div class="card" style="padding:28px; text-align:center; transition:all 0.2s; cursor:pointer;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:48px; margin-bottom:14px;">📅</div>
            <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:6px;">উপস্থিতি রিপোর্ট</div>
            <div style="font-size:13px; color:var(--text-muted);">মাসিক উপস্থিতি, অনুপস্থিতির হার</div>
        </div>
    </a>

    <a href="{{ route('reports.meal') }}" style="text-decoration:none;">
        <div class="card" style="padding:28px; text-align:center; transition:all 0.2s; cursor:pointer;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:48px; margin-bottom:14px;">🍱</div>
            <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:6px;">খোরাকি রিপোর্ট</div>
            <div style="font-size:13px; color:var(--text-muted);">মাসিক খাবার খরচ, বকেয়া</div>
        </div>
    </a>

    <a href="{{ route('reports.welfare') }}" style="text-decoration:none;">
        <div class="card" style="padding:28px; text-align:center; transition:all 0.2s; cursor:pointer;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:48px; margin-bottom:14px;">❤️</div>
            <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:6px;">কল্যাণ রিপোর্ট</div>
            <div style="font-size:13px; color:var(--text-muted);">গোরাবা ফান্ড, সহায়তার বিবরণ</div>
        </div>
    </a>

    <a href="{{ route('students.import') }}" style="text-decoration:none;">
        <div class="card" style="padding:28px; text-align:center; transition:all 0.2s; cursor:pointer;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size:48px; margin-bottom:14px;">📥</div>
            <div style="font-size:17px; font-weight:700; color:var(--text); margin-bottom:6px;">ছাত্র Import</div>
            <div style="font-size:13px; color:var(--text-muted);">Excel/CSV থেকে ছাত্র ভর্তি</div>
        </div>
    </a>

</div>
@endsection
