@extends('layouts.app')
@section('page-title', isset($student) ? 'ছাত্র সম্পাদনা' : 'নতুন ভর্তি')
@section('breadcrumb', isset($student) ? 'Students / Edit' : 'Students / New Admission')

@section('content')

<form method="POST"
      action="{{ isset($student) ? route('students.update', $student) : route('students.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if(isset($student)) @method('PUT') @endif

    <div style="display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start;">

        <!-- Left -->
        <div style="display: flex; flex-direction: column; gap: 20px;">

            <!-- Personal Info -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-person-fill"></i> ব্যক্তিগত তথ্য</div>
                </div>
                <div class="card-body">
                    <div class="form-grid form-grid-2">
                        <div>
                            <label class="form-label">পুরো নাম (বাংলা) <span style="color: red;">*</span></label>
                            <input type="text" name="name" class="form-control" required
                                   value="{{ old('name', $student->name ?? '') }}" placeholder="যেমন: মোহাম্মদ রাফি">
                        </div>
                        <div>
                            <label class="form-label">পুরো নাম (English)</label>
                            <input type="text" name="name_en" class="form-control"
                                   value="{{ old('name_en', $student->name_en ?? '') }}" placeholder="e.g. Mohammad Rafi">
                        </div>
                        <div>
                            <label class="form-label">জন্ম তারিখ <span style="color: red;">*</span></label>
                            <input type="date" name="date_of_birth" class="form-control" required
                                   value="{{ old('date_of_birth', isset($student) ? $student->date_of_birth->format('Y-m-d') : '') }}">
                        </div>
                        <div>
                            <label class="form-label">লিঙ্গ <span style="color: red;">*</span></label>
                            <select name="gender" class="form-select" required>
                                <option value="">বেছে নিন</option>
                                <option value="male" {{ old('gender', $student->gender ?? '') === 'male' ? 'selected' : '' }}>ছাত্র (পুরুষ)</option>
                                <option value="female" {{ old('gender', $student->gender ?? '') === 'female' ? 'selected' : '' }}>ছাত্রী (নারী)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">ধর্ম</label>
                            <select name="religion" class="form-select">
                                <option value="Islam" {{ old('religion', $student->religion ?? 'Islam') === 'Islam' ? 'selected' : '' }}>ইসলাম</option>
                                <option value="Hindu" {{ old('religion', $student->religion ?? '') === 'Hindu' ? 'selected' : '' }}>হিন্দু</option>
                                <option value="Christian" {{ old('religion', $student->religion ?? '') === 'Christian' ? 'selected' : '' }}>খ্রিস্টান</option>
                                <option value="Buddhist" {{ old('religion', $student->religion ?? '') === 'Buddhist' ? 'selected' : '' }}>বৌদ্ধ</option>
                                <option value="Other" {{ old('religion', $student->religion ?? '') === 'Other' ? 'selected' : '' }}>অন্যান্য</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">রক্তের গ্রুপ</label>
                            <select name="blood_group" class="form-select">
                                <option value="">জানা নেই</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group ?? '') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="grid-column: span 2;">
                            <label class="form-label">বর্তমান ঠিকানা <span style="color: red;">*</span></label>
                            <textarea name="address" class="form-control" rows="2" required
                                      placeholder="গ্রাম, উপজেলা, জেলা">{{ old('address', $student->address ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guardian Info -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-people-fill"></i> অভিভাবকের তথ্য</div>
                </div>
                <div class="card-body">
                    <div class="form-grid form-grid-2">
                        <div>
                            <label class="form-label">পিতার নাম <span style="color: red;">*</span></label>
                            <input type="text" name="father_name" class="form-control" required
                                   value="{{ old('father_name', $student->father_name ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">মাতার নাম <span style="color: red;">*</span></label>
                            <input type="text" name="mother_name" class="form-control" required
                                   value="{{ old('mother_name', $student->mother_name ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">অভিভাবকের ফোন <span style="color: red;">*</span></label>
                            <input type="tel" name="guardian_phone" class="form-control" required
                                   value="{{ old('guardian_phone', $student->guardian_phone ?? '') }}"
                                   placeholder="01XXXXXXXXX">
                        </div>
                        <div>
                            <label class="form-label">অভিভাবকের পেশা</label>
                            <input type="text" name="guardian_occupation" class="form-control"
                                   value="{{ old('guardian_occupation', $student->guardian_occupation ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">জাতীয় পরিচয় পত্র (পিতার)</label>
                            <input type="text" name="father_nid" class="form-control"
                                   value="{{ old('father_nid', $student->father_nid ?? '') }}"
                                   placeholder="NID নম্বর">
                        </div>
                        <div>
                            <label class="form-label">ইমেইল (অভিভাবক)</label>
                            <input type="email" name="guardian_email" class="form-control"
                                   value="{{ old('guardian_email', $student->guardian_email ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Previous Education -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-mortarboard-fill"></i> পূর্ববর্তী শিক্ষা</div>
                </div>
                <div class="card-body">
                    <div class="form-grid form-grid-2">
                        <div>
                            <label class="form-label">পূর্বের প্রতিষ্ঠান</label>
                            <input type="text" name="previous_school" class="form-control"
                                   value="{{ old('previous_school', $student->previous_school ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">পূর্বের শ্রেণী</label>
                            <input type="text" name="previous_class" class="form-control"
                                   value="{{ old('previous_class', $student->previous_class ?? '') }}">
                        </div>
                        <div>
                            <label class="form-label">টিসি নম্বর</label>
                            <input type="text" name="tc_number" class="form-control"
                                   value="{{ old('tc_number', $student->tc_number ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div style="display: flex; flex-direction: column; gap: 20px;">

            <!-- Photo -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-camera-fill"></i> ছবি</div>
                </div>
                <div class="card-body" style="text-align: center;">
                    <div id="photoPreview" style="
                        width: 120px; height: 140px; border-radius: 10px;
                        border: 2px dashed var(--border); margin: 0 auto 14px;
                        display: flex; align-items: center; justify-content: center;
                        overflow: hidden; cursor: pointer; background: var(--bg);
                    " onclick="document.getElementById('photoInput').click()">
                        @if(isset($student) && $student->photo)
                        <img src="{{ asset('storage/'.$student->photo) }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                        <div style="text-align:center; color: var(--text-muted);">
                            <i class="bi bi-person-bounding-box" style="font-size: 36px; display:block; margin-bottom: 8px;"></i>
                            <span style="font-size: 12px;">ছবি আপলোড করুন</span>
                        </div>
                        @endif
                    </div>
                    <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none">
                    <p style="font-size: 11.5px; color: var(--text-muted);">JPG/PNG, সর্বোচ্চ ২ MB</p>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-building"></i> একাডেমিক তথ্য</div>
                </div>
                <div class="card-body">
                    <div class="form-grid" style="gap: 14px;">
                        <div>
                            <label class="form-label">শ্রেণী <span style="color: red;">*</span></label>
                            <select name="class_id" class="form-select" required id="classSelect">
                                <option value="">বেছে নিন</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $student->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">বিভাগ <span style="color: red;">*</span></label>
                            <select name="section_id" class="form-select" required id="sectionSelect">
                                <option value="">প্রথমে শ্রেণী বেছে নিন</option>
                                @foreach($sections as $sec)
                                <option value="{{ $sec->id }}" {{ old('section_id', $student->section_id ?? '') == $sec->id ? 'selected' : '' }}>
                                    {{ $sec->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">শিক্ষাবর্ষ <span style="color: red;">*</span></label>
                            <select name="session_id" class="form-select" required>
                                <option value="">বেছে নিন</option>
                                @foreach($sessions as $session)
                                <option value="{{ $session->id }}"
                                    {{ old('session_id', $student->session_id ?? $currentSession->id ?? '') == $session->id ? 'selected' : '' }}>
                                    {{ $session->name }} {{ $session->is_current ? '(চলমান)' : '' }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">রোল নম্বর</label>
                            <input type="number" name="roll_number" class="form-control"
                                   value="{{ old('roll_number', $student->roll_number ?? '') }}" min="1">
                        </div>
                        <div>
                            <label class="form-label">ভর্তির তারিখ <span style="color: red;">*</span></label>
                            <input type="date" name="admission_date" class="form-control" required
                                   value="{{ old('admission_date', isset($student) ? $student->admission_date?->format('Y-m-d') : today()->format('Y-m-d')) }}">
                        </div>
                        <div>
                            <label class="form-label">অবস্থা</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status', $student->status ?? 'active') === 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                <option value="inactive" {{ old('status', $student->status ?? '') === 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                                <option value="transferred" {{ old('status', $student->status ?? '') === 'transferred' ? 'selected' : '' }}>বদলি</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    <i class="bi bi-check-lg"></i>
                    {{ isset($student) ? 'আপডেট করুন' : 'ভর্তি করুন' }}
                </button>
                <a href="{{ route('students.index') }}" class="btn btn-outline" style="width: 100%; justify-content: center;">
                    <i class="bi bi-arrow-left"></i> ফিরে যান
                </a>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
    // Photo preview
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            const preview = document.getElementById('photoPreview');
            preview.innerHTML = `<img src="${ev.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
        };
        reader.readAsDataURL(file);
    });

    // Dynamic sections based on class
    document.getElementById('classSelect').addEventListener('change', function() {
        const classId = this.value;
        const sectionSelect = document.getElementById('sectionSelect');
        sectionSelect.innerHTML = '<option value="">লোড হচ্ছে...</option>';

        if (!classId) {
            sectionSelect.innerHTML = '<option value="">প্রথমে শ্রেণী বেছে নিন</option>';
            return;
        }

        fetch(`/api/sections?class_id=${classId}`)
            .then(r => r.json())
            .then(data => {
                sectionSelect.innerHTML = '<option value="">বিভাগ বেছে নিন</option>';
                data.forEach(s => {
                    sectionSelect.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                });
            });
    });
</script>
@endpush
