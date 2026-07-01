@extends('layouts.app')
@section('page-title', 'গোরাবা ফান্ড সহায়তা')
@section('breadcrumb', 'Home / Welfare')
@section('content')

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px;">
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">মোট সহায়তা</div>
        <div style="font-size:22px; font-weight:700; color:var(--text);">৳{{ number_format($stats['total']) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">প্রতিষ্ঠান প্রদান</div>
        <div style="font-size:22px; font-weight:700; color:#1d4ed8;">৳{{ number_format($stats['institution']) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">ছাত্র প্রদান</div>
        <div style="font-size:22px; font-weight:700; color:#16a34a;">৳{{ number_format($stats['student']) }}</div>
    </div>
    <div style="background:#fff; border-radius:var(--radius); border:1px solid var(--border); padding:18px 20px;">
        <div style="font-size:11.5px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">দাতা প্রদান</div>
        <div style="font-size:22px; font-weight:700; color:#7c3aed;">৳{{ number_format($stats['donor']) }}</div>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('welfare.index') }}">
            <div class="filter-bar">
                <div style="flex:1; min-width:180px; position:relative;">
                    <i class="bi bi-search" style="position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--text-muted);"></i>
                    <input type="text" name="search" class="form-control" placeholder="ছাত্রের নাম..." value="{{ request('search') }}" style="padding-left:34px;">
                </div>
                <select name="type" class="form-select" style="min-width:140px;">
                    <option value="">সব ধরন</option>
                    <option value="scholarship" {{ request('type')==='scholarship'?'selected':'' }}>বৃত্তি</option>
                    <option value="book"        {{ request('type')==='book'       ?'selected':'' }}>বই সহায়তা</option>
                    <option value="food"        {{ request('type')==='food'       ?'selected':'' }}>খাদ্য সহায়তা</option>
                    <option value="clothing"    {{ request('type')==='clothing'   ?'selected':'' }}>পোশাক সহায়তা</option>
                    <option value="medical"     {{ request('type')==='medical'    ?'selected':'' }}>চিকিৎসা সহায়তা</option>
                    <option value="other"       {{ request('type')==='other'      ?'selected':'' }}>অন্যান্য</option>
                </select>
                <select name="class_id" class="form-select" style="min-width:140px;">
                    <option value="">সব শ্রেণী</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id')==$class->id?'selected':'' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
                <input type="month" name="month" class="form-control" value="{{ request('month') }}" style="min-width:150px;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i></button>
                <a href="{{ route('welfare.index') }}" class="btn btn-outline"><i class="bi bi-x"></i></a>
                <a href="{{ route('welfare.create') }}" class="btn btn-primary" style="margin-left:auto;">
                    <i class="bi bi-plus-lg"></i> নতুন সহায়তা
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-heart-fill" style="color:#dc2626;"></i> সহায়তার তালিকা</div>
        <span style="font-size:12.5px; color:var(--text-muted);">মোট: {{ $welfare->total() }} টি</span>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ছাত্র</th>
                    <th>শিরোনাম</th>
                    <th>ধরন</th>
                    <th style="text-align:right">মোট</th>
                    <th style="text-align:right">ছাত্র</th>
                    <th style="text-align:right">প্রতিষ্ঠান</th>
                    <th style="text-align:right">দাতা</th>
                    <th>তারিখ</th>
                    <th style="text-align:center">কার্যক্রম</th>
                </tr>
            </thead>
            <tbody>
                @php
                $typeLabels = ['scholarship'=>'🎓 বৃত্তি','book'=>'📚 বই','food'=>'🍱 খাদ্য','clothing'=>'👕 পোশাক','medical'=>'🏥 চিকিৎসা','other'=>'📌 অন্যান্য'];
                $typeBadges = ['scholarship'=>'badge-purple','book'=>'badge-blue','food'=>'badge-orange','clothing'=>'badge-green','medical'=>'badge-red','other'=>'badge-gray'];
                @endphp
                @forelse($welfare as $item)
                <tr>
                    <td>
                        <div style="font-weight:600; font-size:13px;">{{ $item->student->name??'—' }}</div>
                        <div style="font-size:11.5px; color:var(--text-muted);">{{ $item->student->studentClass->name??'' }}</div>
                    </td>
                    <td style="font-size:13px; font-weight:500;">{{ $item->title }}</td>
                    <td><span class="badge {{ $typeBadges[$item->type]??'badge-gray' }}">{{ $typeLabels[$item->type]??$item->type }}</span></td>
                    <td style="text-align:right; font-family:var(--font-en); font-weight:700;">৳{{ number_format($item->total_amount) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#16a34a;">৳{{ number_format($item->student_contribution) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#1d4ed8;">৳{{ number_format($item->institution_contribution) }}</td>
                    <td style="text-align:right; font-family:var(--font-en); color:#7c3aed;">৳{{ number_format($item->donor_contribution) }}</td>
                    <td style="font-size:12px; color:var(--text-muted); font-family:var(--font-en);">{{ $item->date->format('d/m/Y') }}</td>
                    <td>
                        <div style="display:flex; gap:5px; justify-content:center;">
                            <a href="{{ route('welfare.edit',$item) }}" class="btn btn-icon btn-outline btn-sm"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('welfare.destroy',$item) }}" onsubmit="return confirm('মুছবেন?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9"><div class="empty-state"><i class="bi bi-heart"></i><p>কোনো সহায়তার রেকর্ড নেই</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($welfare instanceof \Illuminate\Pagination\LengthAwarePaginator && $welfare->hasPages())
    <div class="pagination-wrap">{{ $welfare->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
