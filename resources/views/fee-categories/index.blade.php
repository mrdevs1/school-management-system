@extends('layouts.app')
@section('page-title', 'ফি ক্যাটাগরি')
@section('breadcrumb', 'Home / Fee Categories')
@section('content')
<div class="responsive-grid" style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">
    <div class="card">
        <div class="card-header" style="padding:16px 20px;">
            <div class="card-title"><i class="bi bi-tags-fill"></i> ফি ক্যাটাগরি তালিকা</div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>নাম</th><th style="text-align:right">পরিমাণ</th><th>ধরন</th><th>বিবরণ</th><th style="text-align:center">কার্যক্রম</th></tr></thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td style="font-weight:600;">{{ $cat->name }}</td>
                        <td style="text-align:right; font-family:var(--font-en); font-weight:600; color:var(--green);">৳{{ number_format($cat->amount) }}</td>
                        <td><span class="badge {{ ['monthly'=>'badge-blue','yearly'=>'badge-orange','once'=>'badge-purple'][$cat->frequency] }}">{{ ['monthly'=>'মাসিক','yearly'=>'বার্ষিক','once'=>'একবার'][$cat->frequency] }}</span></td>
                        <td style="font-size:12.5px; color:var(--text-muted);">{{ $cat->description??'—' }}</td>
                        <td>
                            <div style="display:flex; gap:5px; justify-content:center;">
                                <form method="POST" action="{{ route('fee-categories.destroy',$cat) }}" onsubmit="return confirm('মুছবেন?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-tags"></i><p>কোনো ক্যাটাগরি নেই</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="position:sticky; top:84px;">
        <div class="card-header"><div class="card-title"><i class="bi bi-plus-circle-fill"></i> নতুন ক্যাটাগরি</div></div>
        <div class="card-body">
            <form method="POST" action="{{ route('fee-categories.store') }}">
                @csrf
                <div class="form-grid" style="gap:12px;">
                    <div>
                        <label class="form-label">নাম <span style="color:red">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="যেমন: মাসিক বেতন">
                    </div>
                    <div>
                        <label class="form-label">পরিমাণ (৳) <span style="color:red">*</span></label>
                        <input type="number" name="amount" class="form-control" required min="0" placeholder="500">
                    </div>
                    <div>
                        <label class="form-label">ধরন <span style="color:red">*</span></label>
                        <select name="frequency" class="form-select" required>
                            <option value="monthly">মাসিক</option>
                            <option value="yearly">বার্ষিক</option>
                            <option value="once">একবার</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">বিবরণ</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="ঐচ্ছিক..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                        <i class="bi bi-plus-lg"></i> ক্যাটাগরি যোগ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
