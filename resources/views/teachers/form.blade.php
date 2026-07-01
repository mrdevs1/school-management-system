@extends('layouts.app')
@section('page-title', isset($teacher) ? 'শিক্ষক সম্পাদনা' : 'নতুন শিক্ষক')
@section('breadcrumb', isset($teacher) ? 'Teachers / Edit' : 'Teachers / New')

@section('content')
<form method="POST"
      action="{{ isset($teacher) ? route('teachers.update', $teacher) : route('teachers.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if(isset($teacher)) @method('PUT') @endif

    <div class="responsive-grid" style="display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:start;">

        <div style="display:flex; flex-direction:column; gap:20px;">

            <!-- Personal Info -->
            <div class="card">
                <div class="card-header"><div class="card-title"><i class="bi bi-person-fill"></i> ব্যক্তিগত তথ্য</div></div>
                <div class="card-body">
                    <div class="form-grid form-grid-2">
                        <div>
                            <label class="form-label">পুরো নাম (বাংলা) <span style="color:red">*</span></label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $teacher->name ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">নাম (English)</label>
                            <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $teacher->name_en ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">ইমেইল</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $teacher->email ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">ফোন <span style="color:red">*</span></label>
                            <input type="tel" name="phone" class="form-control" required value="{{ old('phone', $teacher->phone ?? '') }}" placeholder="01XXXXXXXXX">
                        </div>
                        <div>
                            <label class="form-label">জন্ম তারিখ</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', isset($teacher) ? $teacher->date_of_birth?->format('Y-m-d') : '') }}">
                        </div>
                        <div>
                            <label class="form-label">লিঙ্গ <span style="color:red">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="male"   {{ old('gender', $teacher->gender ?? '') === 'male'   ? 'selected' : '' }}>পুরুষ</option>
                                <option value="female" {{ old('gender', $teacher->gender ?? '') === 'female' ? 'selected' : '' }}>নারী</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">জাতীয় পরিচয় পত্র</label>
                            <input type="text" name="nid" class="form-control" value="{{ old('nid', $teacher->nid ?? '') }}" placeholder="NID নম্বর">
                        </div>
                        <div style="grid-column:span 2;">
                            <label class="form-label">ঠিকানা</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $teacher->address ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Info -->
            <div class="card">
                <div class="card-header"><div class="card-title"><i class="bi bi-briefcase-fill"></i> পেশাগত তথ্য</div></div>
                <div class="card-body">
                    <div class="form-grid form-grid-2">
                        <div>
                            <label class="form-label">শিক্ষাগত যোগ্যতা <span style="color:red">*</span></label>
                            <input type="text" name="qualification" class="form-control" required value="{{ old('qualification', $teacher->qualification ?? '') }}" placeholder="যেমন: এম.এ, বি.এড">
                        </div>
                        <div>
                            <label class="form-label">পদবি <span style="color:red">*</span></label>
                            <select name="designation" class="form-select" required>
                                <option value="">-- বেছে নিন --</option>
                                @foreach($designations as $d)
                                <option value="{{ $d->name }}" {{ old('designation', $teacher->designation ?? '') === $d->name ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">বিভাগ</label>
                            <input type="text" name="department" class="form-control" value="{{ old('department', $teacher->department ?? '') }}" placeholder="যেমন: বিজ্ঞান, হিফজ">
                        </div>
                        <div>
                            <label class="form-label">বিশেষ বিষয়</label>
                            <input type="text" name="subject_specialty" class="form-control" value="{{ old('subject_specialty', $teacher->subject_specialty ?? '') }}" placeholder="যেমন: গণিত, আরবি">
                        </div>
                        <div>
                            <label class="form-label">মূল বেতন (৳) <span style="color:red">*</span></label>
                            <input type="number" name="salary" class="form-control" required min="0" value="{{ old('salary', $teacher->salary ?? '') }}" placeholder="মাসিক বেতন">
                        </div>
                        <div>
                            <label class="form-label">যোগদানের তারিখ <span style="color:red">*</span></label>
                            <input type="date" name="joining_date" class="form-control" required value="{{ old('joining_date', isset($teacher) ? $teacher->joining_date->format('Y-m-d') : today()->format('Y-m-d')) }}">
                        </div>
                        <div>
                            <label class="form-label">অবস্থা</label>
                            <select name="status" class="form-select">
                                <option value="active"   {{ old('status', $teacher->status ?? 'active') === 'active'   ? 'selected' : '' }}>সক্রিয়</option>
                                <option value="inactive" {{ old('status', $teacher->status ?? '') === 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right -->
        <div style="display:flex; flex-direction:column; gap:20px;">
            <div class="card">
                <div class="card-header"><div class="card-title"><i class="bi bi-camera-fill"></i> ছবি</div></div>
                <div class="card-body" style="text-align:center;">
                    <div id="photoPreview" style="width:120px; height:140px; border-radius:10px; border:2px dashed var(--border); margin:0 auto 14px; display:flex; align-items:center; justify-content:center; overflow:hidden; cursor:pointer; background:var(--bg);"
                         onclick="document.getElementById('photoInput').click()">
                        @if(isset($teacher) && $teacher->photo)
                        <img src="{{ asset('storage/'.$teacher->photo) }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                        <div style="text-align:center; color:var(--text-muted);">
                            <i class="bi bi-person-bounding-box" style="font-size:36px; display:block; margin-bottom:8px;"></i>
                            <span style="font-size:12px;">ছবি আপলোড</span>
                        </div>
                        @endif
                    </div>
                    <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none">
                    <p style="font-size:11.5px; color:var(--text-muted);">JPG/PNG, সর্বোচ্চ ২ MB</p>
                </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:10px;">
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                    <i class="bi bi-check-lg"></i> {{ isset($teacher) ? 'আপডেট করুন' : 'যোগ করুন' }}
                </button>
                <a href="{{ route('teachers.index') }}" class="btn btn-outline" style="width:100%; justify-content:center;">
                    <i class="bi bi-arrow-left"></i> ফিরে যান
                </a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('photoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
        document.getElementById('photoPreview').innerHTML =
            `<img src="${ev.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
