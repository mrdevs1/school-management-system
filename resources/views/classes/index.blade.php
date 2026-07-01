@extends('layouts.app')
@section('page-title', 'শ্রেণী ও বিভাগ')
@section('breadcrumb', 'Home / Classes')
@section('content')
<div class="responsive-grid" style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">

    <div class="card">
        <div class="card-header" style="padding:16px 20px;">
            <div class="card-title"><i class="bi bi-building"></i> শ্রেণী তালিকা</div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>শ্রেণীর নাম</th><th>ধরন</th><th>বিভাগ</th><th>ছাত্র সংখ্যা</th><th style="text-align:center">কার্যক্রম</th></tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                    <tr>
                        <td style="font-weight:600;">{{ $class->name }}</td>
                        <td>@if($class->type)<span class="badge {{ $class->type === 'school' ? 'badge-blue' : ($class->type === 'madrasa' ? 'badge-green' : 'badge-purple') }}">{{ ['school'=>'স্কুল','madrasa'=>'মাদ্রাসা','both'=>'উভয়'][$class->type] }}</span>@else<span style="color:var(--text-muted); font-size:13px;">—</span>@endif</td>
                        <td>
                            @foreach($class->sections as $sec)
                            <span class="badge badge-gray" style="margin:2px; cursor:pointer;" onclick="openSectionEdit({{ $sec->id }}, '{{ $sec->name }}', {{ $sec->teacher_id ?? 'null' }})">
                                {{ $sec->name }}
                                @if($sec->teacher)<small style="color:#16a34a;"> · {{ $sec->teacher->name }}</small>@endif
                                <i class="bi bi-pencil" style="font-size:10px;"></i>
                            </span>
                            @endforeach
                        </td>
                        <td style="font-family:var(--font-en); font-weight:600;">{{ $class->students_count }}</td>
                        <td style="text-align:center;">
                            <div style="display:flex; gap:5px; justify-content:center;">
                            <button onclick="openEditModal({{ $class->id }}, '{{ $class->name }}', '{{ $class->name_en }}', {{ $class->numeric_name ?? 0 }}, '{{ $class->type }}')" class="btn btn-icon btn-outline btn-sm" title="সম্পাদনা"><i class="bi bi-pencil"></i></button>
                            <form method="POST" action="{{ route('classes.destroy', $class) }}" onsubmit="return confirm('মুছবেন?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="bi bi-building"></i><p>কোনো শ্রেণী নেই</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="display:flex; flex-direction:column; gap:16px; position:sticky; top:84px;">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="bi bi-plus-circle-fill"></i> নতুন শ্রেণী</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('classes.store') }}">
                    @csrf
                    <div class="form-grid" style="gap:12px;">
                        <div>
                            <label class="form-label">শ্রেণীর নাম <span style="color:red">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="যেমন: ষষ্ঠ শ্রেণী">
                        </div>
                        <div>
                            <label class="form-label">English নাম</label>
                            <input type="text" name="name_en" class="form-control" placeholder="e.g. Class 6">
                        </div>
                        <div>
                            <label class="form-label">ক্রম নম্বর</label>
                            <input type="number" name="numeric_name" class="form-control" placeholder="যেমন: 6">
                        </div>
                        <div>
                            <label class="form-label">ধরন</label>
                            <select name="type" class="form-select">
                                <option value="">-- বেছে নিন --</option>
                                <option value="school">স্কুল</option>
                                <option value="madrasa">মাদ্রাসা</option>
                                <option value="both">উভয়</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                            <i class="bi bi-plus-lg"></i> শ্রেণী যোগ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title"><i class="bi bi-diagram-3-fill"></i> নতুন বিভাগ</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('sections.store') }}">
                    @csrf
                    <div class="form-grid" style="gap:12px;">
                        <div>
                            <label class="form-label">শ্রেণী <span style="color:red">*</span></label>
                            <select name="class_id" class="form-select" required>
                                <option value="">বেছে নিন</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">বিভাগের নাম <span style="color:red">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="যেমন: ক, খ, A, B">
                        </div>
                        <div>
                            <label class="form-label">শ্রেণী শিক্ষক</label>
                            <select name="teacher_id" class="form-select">
                                <option value="">বেছে নিন</option>
                                @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                            <i class="bi bi-plus-lg"></i> বিভাগ যোগ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box" style="max-width:420px;">
        <div class="modal-header">
            <h5 style="font-weight:700; font-size:16px;"><i class="bi bi-pencil" style="color:var(--green)"></i> শ্রেণী সম্পাদনা</h5>
            <button onclick="closeEditModal()" style="background:none; border:none; font-size:22px; cursor:pointer; color:var(--text-muted);">×</button>
        </div>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">শ্রেণীর নাম (বাংলা) <span style="color:red">*</span></label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">নাম (English)</label>
                        <input type="text" name="name_en" id="editNameEn" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">ক্রম নম্বর</label>
                        <input type="number" name="numeric_name" id="editNumeric" class="form-control">
                    </div>
                    <div>
                        <label class="form-label">ধরন</label>
                        <select name="type" id="editType" class="form-select">
                            <option value="school">স্কুল</option>
                            <option value="madrasa">মাদ্রাসা</option>
                            <option value="both">উভয়</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeEditModal()" class="btn btn-outline">বাতিল</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> আপডেট করুন</button>
            </div>
        </form>
    </div>
</div>

<!-- Section Edit Modal -->
<div class="modal-overlay" id="sectionEditModal">
    <div class="modal-box" style="max-width:400px;">
        <div class="modal-header">
            <h5 style="font-weight:700; font-size:16px;"><i class="bi bi-diagram-3" style="color:var(--green)"></i> বিভাগ সম্পাদনা</h5>
            <button onclick="document.getElementById('sectionEditModal').classList.remove('open')" style="background:none; border:none; font-size:22px; cursor:pointer; color:var(--text-muted);">×</button>
        </div>
        <form method="POST" id="sectionEditForm">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-grid" style="gap:14px;">
                    <div>
                        <label class="form-label">বিভাগের নাম <span style="color:red">*</span></label>
                        <input type="text" name="name" id="sectionEditName" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">শ্রেণী শিক্ষক</label>
                        <select name="teacher_id" id="sectionEditTeacher" class="form-select">
                            <option value="">-- বেছে নিন --</option>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }} — {{ $teacher->designation }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="document.getElementById('sectionEditModal').classList.remove('open')" class="btn btn-outline">বাতিল</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> আপডেট</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openSectionEdit(id, name, teacherId) {
    document.getElementById('sectionEditName').value = name;
    document.getElementById('sectionEditTeacher').value = teacherId || '';
    document.getElementById('sectionEditForm').action = '/sections/' + id;
    document.getElementById('sectionEditModal').classList.add('open');
}

function openEditModal(id, name, nameEn, numeric, type) {
    document.getElementById('editName').value = name;
    document.getElementById('editNameEn').value = nameEn;
    document.getElementById('editNumeric').value = numeric;
    document.getElementById('editType').value = type;
    document.getElementById('editForm').action = '/classes/' + id;
    document.getElementById('editModal').classList.add('open');
}
function closeEditModal() {
    document.getElementById('editModal').classList.remove('open');
}
</script>
@endpush
