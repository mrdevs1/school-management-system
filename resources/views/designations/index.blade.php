@extends('layouts.app')
@section('page-title', 'পদবি ব্যবস্থাপনা')
@section('breadcrumb', 'Home / Designations')
@section('content')

<div style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;" class="responsive-grid">

    <div class="card">
        <div class="card-header" style="padding:16px 20px;">
            <div class="card-title"><i class="bi bi-award"></i> পদবি তালিকা</div>
            <span style="font-size:12.5px; color:var(--text-muted);">মোট: {{ $designations->count() }} টি</span>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">ক্রম</th>
                        <th>পদবির নাম</th>
                        <th style="text-align:center">শিক্ষক সংখ্যা</th>
                        <th style="text-align:center">অবস্থা</th>
                        <th style="text-align:center">কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($designations as $designation)
                    <tr>
                        <td style="text-align:center; color:var(--text-muted); font-family:var(--font-en);">{{ $designation->order }}</td>
                        <td style="font-weight:600; font-size:13.5px;">{{ $designation->name }}</td>
                        <td style="text-align:center;">
                            <span class="badge badge-blue">{{ $designation->teachers()->count() }} জন</span>
                        </td>
                        <td style="text-align:center;">
                            <form method="POST" action="{{ route('designations.toggle', $designation) }}">
                                @csrf
                                <button type="submit" class="badge {{ $designation->is_active ? 'badge-green' : 'badge-red' }}" style="border:none; cursor:pointer; font-family:var(--font-bn);">
                                    {{ $designation->is_active ? '● সক্রিয়' : '● নিষ্ক্রিয়' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div style="display:flex; gap:5px; justify-content:center;">
                                <button onclick="openEdit({{ $designation->id }}, '{{ $designation->name }}')" class="btn btn-icon btn-outline btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('designations.destroy', $designation) }}" onsubmit="return confirm('Delete this designation?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-award"></i><p>কোনো পদবি নেই</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="position:sticky; top:84px;">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="bi bi-plus-circle-fill"></i> নতুন পদবি</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('designations.store') }}">
                    @csrf
                    <div class="form-grid" style="gap:12px;">
                        <div>
                            <label class="form-label">পদবির নাম <span style="color:red">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="যেমন: সহকারী শিক্ষক">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                            <i class="bi bi-plus-lg"></i> যোগ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card" style="margin-top:16px;">
            <div class="card-body" style="padding:16px;">
                <div style="font-size:13px; color:var(--text-muted); line-height:1.8;">
                    <div style="font-weight:600; color:var(--text); margin-bottom:8px;"><i class="bi bi-info-circle" style="color:var(--green);"></i> নির্দেশনা</div>
                    <p>• নিষ্ক্রিয় পদবি শিক্ষক form এ দেখাবে না</p>
                    <p>• শিক্ষক assigned থাকলে delete হবে না</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box" style="max-width:380px;">
        <div class="modal-header">
            <h5 style="font-weight:700; font-size:16px;"><i class="bi bi-pencil" style="color:var(--green)"></i> পদবি সম্পাদনা</h5>
            <button onclick="document.getElementById('editModal').classList.remove('open')" style="background:none; border:none; font-size:22px; cursor:pointer;">×</button>
        </div>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            <div class="modal-body">
                <label class="form-label">পদবির নাম <span style="color:red">*</span></label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('editModal').classList.remove('open')" class="btn btn-outline">বাতিল</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> আপডেট</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEdit(id, name) {
    document.getElementById('editName').value = name;
    document.getElementById('editForm').action = '/designations/' + id;
    document.getElementById('editModal').classList.add('open');
}
</script>
@endpush
