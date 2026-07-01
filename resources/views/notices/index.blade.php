@extends('layouts.app')
@section('page-title', 'নোটিশ বোর্ড')
@section('breadcrumb', 'Home / Notices')
@section('content')
<div class="card">
    <div class="card-header" style="padding:16px 20px;">
        <div class="card-title"><i class="bi bi-megaphone-fill"></i> নোটিশ তালিকা</div>
        <a href="{{ route('notices.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> নতুন নোটিশ</a>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>শিরোনাম</th><th>দর্শক</th><th>প্রকাশের তারিখ</th><th>প্রকাশক</th><th style="text-align:center">কার্যক্রম</th></tr></thead>
            <tbody>
                @forelse($notices as $notice)
                <tr>
                    <td>
                        <div style="font-weight:600; font-size:13.5px;">{{ $notice->title }}</div>
                        <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">{{ Str::limit($notice->content,60) }}</div>
                    </td>
                    <td>
                        <span class="badge {{ ['all'=>'badge-blue','students'=>'badge-green','teachers'=>'badge-purple','parents'=>'badge-orange'][$notice->audience]??'badge-gray' }}">
                            {{ ['all'=>'📢 সবাই','students'=>'🎓 ছাত্র','teachers'=>'👩‍🏫 শিক্ষক','parents'=>'👪 অভিভাবক'][$notice->audience] }}
                        </span>
                    </td>
                    <td style="font-size:12.5px; font-family:var(--font-en);">{{ $notice->publish_date->format('d M Y') }}</td>
                    <td style="font-size:12.5px;">{{ $notice->creator->name??'—' }}</td>
                    <td>
                        <div style="display:flex; gap:5px; justify-content:center;">
                            <a href="{{ route('notices.edit',$notice) }}" class="btn btn-icon btn-outline btn-sm"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('notices.destroy',$notice) }}" onsubmit="return confirm('মুছবেন?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="bi bi-megaphone"></i><p>কোনো নোটিশ নেই</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notices->hasPages())
    <div class="pagination-wrap">{{ $notices->links() }}</div>
    @endif
</div>
@endsection
