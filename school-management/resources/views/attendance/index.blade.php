@extends('layouts.app')
@section('page-title', 'উপস্থিতি')
@section('breadcrumb', 'Home / Attendance')

@section('content')

<!-- Class/Date Selector -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body" style="padding: 18px 22px;">
        <form method="GET" action="{{ route('attendance.index') }}" id="filterForm">
            <div class="filter-bar">
                <div>
                    <label class="form-label" style="margin-bottom: 4px; font-size: 11.5px;">তারিখ</label>
                    <input type="date" name="date" class="form-control"
                           value="{{ $date }}" onchange="this.form.submit()">
                </div>
                <div>
                    <label class="form-label" style="margin-bottom: 4px; font-size: 11.5px;">শ্রেণী</label>
                    <select name="class_id" class="form-select" onchange="this.form.submit()">
                        <option value="">শ্রেণী বেছে নিন</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label" style="margin-bottom: 4px; font-size: 11.5px;">বিভাগ</label>
                    <select name="section_id" class="form-select" onchange="this.form.submit()">
                        <option value="">সব বিভাগ</option>
                        @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-left: auto; display: flex; gap: 8px; align-items: flex-end;">
                    <a href="{{ route('attendance.monthly') }}?class_id={{ request('class_id') }}" class="btn btn-outline">
                        <i class="bi bi-calendar-month"></i> মাসিক রিপোর্ট
                    </a>
                    <a href="{{ route('attendance.teacher') }}" class="btn btn-outline">
                        <i class="bi bi-person-badge"></i> শিক্ষকদের হাজিরা
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@if(request('class_id') && $students->count())
<!-- Attendance Entry Form -->
<form method="POST" action="{{ route('attendance.store') }}">
    @csrf
    <input type="hidden" name="date" value="{{ $date }}">
    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
    <input type="hidden" name="section_id" value="{{ request('section_id') }}">

    <div class="card">
        <div class="card-header" style="padding: 16px 22px;">
            <div class="card-title">
                <i class="bi bi-calendar-check-fill"></i>
                হাজিরা — {{ \Carbon\Carbon::parse($date)->format('d F, Y') }}
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="button" class="btn btn-outline btn-sm" onclick="markAll('present')">
                    ✅ সবাই উপস্থিত
                </button>
                <button type="button" class="btn btn-outline btn-sm" onclick="markAll('absent')">
                    ❌ সবাই অনুপস্থিত
                </button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-save"></i> সংরক্ষণ করুন
                </button>
            </div>
        </div>

        <!-- Quick stats counter -->
        <div style="padding: 12px 22px; background: var(--bg); border-bottom: 1px solid var(--border); display: flex; gap: 20px;">
            <span style="font-size: 13px; color: #16a34a; font-weight: 600;">
                <i class="bi bi-check-circle-fill"></i> উপস্থিত: <span id="countPresent">0</span>
            </span>
            <span style="font-size: 13px; color: #dc2626; font-weight: 600;">
                <i class="bi bi-x-circle-fill"></i> অনুপস্থিত: <span id="countAbsent">0</span>
            </span>
            <span style="font-size: 13px; color: #d97706; font-weight: 600;">
                <i class="bi bi-clock-fill"></i> দেরিতে: <span id="countLate">0</span>
            </span>
            <span style="font-size: 13px; color: #3b82f6; font-weight: 600;">
                <i class="bi bi-calendar2-x-fill"></i> ছুটি: <span id="countLeave">0</span>
            </span>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">রোল</th>
                        <th>ছাত্রের নাম</th>
                        <th style="text-align: center; width: 340px;">উপস্থিতি</th>
                        <th>মন্তব্য</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    @php
                        $attendance = $student->attendances->first();
                        $currentStatus = $attendance?->status ?? 'present';
                    @endphp
                    <tr>
                        <td style="font-size: 13px; color: var(--text-muted); font-family: var(--font-en); text-align: center;">
                            {{ $student->roll_number ?? '—' }}
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="avatar-placeholder">{{ mb_substr($student->name, 0, 1) }}</div>
                                <div>
                                    <div style="font-weight: 600; font-size: 13.5px;">{{ $student->name }}</div>
                                    <div style="font-size: 11.5px; color: var(--text-muted); font-family: var(--font-en);">{{ $student->student_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="att-group" style="display: flex; gap: 6px; justify-content: center;">
                                @foreach([
                                    'present' => ['✅', 'উপস্থিত', '#dcfce7', '#16a34a'],
                                    'absent'  => ['❌', 'অনুপস্থিত', '#fee2e2', '#dc2626'],
                                    'late'    => ['⏰', 'দেরিতে', '#fef3c7', '#d97706'],
                                    'leave'   => ['📋', 'ছুটি', '#dbeafe', '#1d4ed8'],
                                ] as $val => [$emoji, $label, $bg, $color])
                                <label style="cursor: pointer;">
                                    <input type="radio" name="attendances[{{ $student->id }}]"
                                           value="{{ $val }}"
                                           class="att-radio"
                                           data-status="{{ $val }}"
                                           {{ $currentStatus === $val ? 'checked' : '' }}
                                           style="display: none;">
                                    <span class="att-label {{ $currentStatus === $val ? 'selected' : '' }}"
                                          data-status="{{ $val }}"
                                          style="
                                            display: inline-flex; align-items: center; gap: 4px;
                                            padding: 5px 11px; border-radius: 20px; font-size: 12.5px; font-weight: 500;
                                            border: 1.5px solid {{ $currentStatus === $val ? $color : 'var(--border)' }};
                                            background: {{ $currentStatus === $val ? $bg : 'transparent' }};
                                            color: {{ $currentStatus === $val ? $color : 'var(--text-muted)' }};
                                            transition: all 0.15s;
                                          ">
                                        {{ $emoji }} {{ $label }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <input type="text" name="remarks[{{ $student->id }}]" class="form-control"
                                   value="{{ $attendance?->remarks }}"
                                   placeholder="ঐচ্ছিক মন্তব্য..."
                                   style="font-size: 12.5px; padding: 6px 10px;">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 16px 22px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px;">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save-fill"></i> হাজিরা সংরক্ষণ করুন
            </button>
        </div>
    </div>
</form>

@elseif(request('class_id'))
<div class="card">
    <div class="empty-state" style="padding: 60px;">
        <i class="bi bi-people"></i>
        <p style="margin-top: 12px;">এই শ্রেণীতে কোনো সক্রিয় ছাত্র নেই</p>
    </div>
</div>
@else
<div class="card">
    <div class="empty-state" style="padding: 60px;">
        <i class="bi bi-calendar-check" style="font-size: 52px; color: var(--border);"></i>
        <p style="margin-top: 14px; font-size: 16px; font-weight: 600; color: var(--text-muted);">হাজিরা নিতে শ্রেণী বেছে নিন</p>
        <p style="font-size: 13px; margin-top: 6px; color: var(--text-muted);">উপরের ফর্ম থেকে তারিখ ও শ্রেণী নির্বাচন করুন</p>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    // Style radio buttons
    document.querySelectorAll('.att-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const group = this.closest('.att-group');
            group.querySelectorAll('.att-label').forEach(lbl => {
                const colors = {
                    present: ['#dcfce7','#16a34a'],
                    absent:  ['#fee2e2','#dc2626'],
                    late:    ['#fef3c7','#d97706'],
                    leave:   ['#dbeafe','#1d4ed8'],
                };
                const s = lbl.dataset.status;
                if (lbl.dataset.status === this.value) {
                    lbl.style.background = colors[s][0];
                    lbl.style.borderColor = colors[s][1];
                    lbl.style.color = colors[s][1];
                } else {
                    lbl.style.background = 'transparent';
                    lbl.style.borderColor = 'var(--border)';
                    lbl.style.color = 'var(--text-muted)';
                }
            });
            updateCounts();
        });
    });

    function updateCounts() {
        const counts = {present: 0, absent: 0, late: 0, leave: 0};
        document.querySelectorAll('.att-radio:checked').forEach(r => {
            counts[r.value] = (counts[r.value] || 0) + 1;
        });
        Object.keys(counts).forEach(k => {
            const el = document.getElementById('count' + k.charAt(0).toUpperCase() + k.slice(1));
            if (el) el.textContent = counts[k];
        });
    }

    function markAll(status) {
        document.querySelectorAll(`.att-radio[value="${status}"]`).forEach(r => {
            r.checked = true;
            r.dispatchEvent(new Event('change'));
        });
    }

    updateCounts();
</script>
@endpush
