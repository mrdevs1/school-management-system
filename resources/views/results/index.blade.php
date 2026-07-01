@extends('layouts.app')
@section('page-title', 'ফলাফল')
@section('breadcrumb', 'Home / Results')
@section('content')

<div class="tabs" style="margin-bottom:0;">
    <button class="tab-btn {{ request('tab','entry')==='entry'?'active':'' }}" onclick="switchTab('entry',this)">
        <i class="bi bi-pencil-square"></i> নম্বর এন্ট্রি
    </button>
    <button class="tab-btn {{ request('tab')==='view'?'active':'' }}" onclick="switchTab('view',this)">
        <i class="bi bi-eye"></i> ফলাফল দেখুন
    </button>
    <button class="tab-btn {{ request('tab')==='merit'?'active':'' }}" onclick="switchTab('merit',this)">
        <i class="bi bi-trophy"></i> মেধা তালিকা
    </button>
</div>

{{-- Entry tab section --}}
<div id="tab-entry" class="tab-content" style="display:{{ request('tab','entry')==='entry'?'block':'none' }}; margin-top:20px;">
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="padding:16px 20px;">
            <form method="GET" action="{{ route('results.index') }}">
                <input type="hidden" name="tab" value="entry">
                <div class="filter-bar">
                    <div>
                        <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">পরীক্ষা</label>
                        <select name="exam_id" class="form-select" onchange="this.form.submit()">
                            <option value="">পরীক্ষা বেছে নিন</option>
                            @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id')==$exam->id?'selected':'' }}>{{ $exam->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size:11.5px; margin-bottom:4px;">শ্রেণী</label>
                        <select name="class_id" class="form-select" onchange="this.form.submit()">
                            <option value="">শ্রেণী বেছে নিন</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id')==$class->id?'selected':'' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(request('exam_id') && request('class_id') && $students->count())
    <form method="POST" action="{{ route('results.store') }}">
        @csrf
        <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
        <div class="card">
            <div class="card-header" style="padding:14px 20px;">
                <div class="card-title"><i class="bi bi-pencil-square"></i> নম্বর এন্ট্রি</div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-save-fill"></i> সংরক্ষণ</button>
            </div>
            <div class="table-wrap" style="overflow-x:auto;">
                <table class="data-table" style="min-width:700px;">
                    <thead>
                        <tr>
                            <th style="position:sticky; left:0; background:#f0f4f2; z-index:1;">ছাত্র</th>
                            @foreach($subjects as $subject)
                            <th style="text-align:center; min-width:90px;">
                                {{ $subject->name }}
                                <div style="font-size:10px; color:var(--text-muted); font-weight:400;">/ {{ $subject->full_marks }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td style="position:sticky; left:0; background:#fff; z-index:1; min-width:150px;">
                                <div style="font-weight:600; font-size:13px;">{{ $student->name }}</div>
                                <div style="font-size:11.5px; color:var(--text-muted);">{{ $student->student_id }}</div>
                            </td>
                            @foreach($subjects as $subject)
                            @php $existing = $existingResults[$student->id][$subject->id] ?? null; @endphp
                            <td style="text-align:center; padding:6px;">
                                <input type="number"
                                    name="results[{{ $student->id }}][{{ $subject->id }}]"
                                    class="form-control marks-input"
                                    data-max="{{ $subject->full_marks }}"
                                    data-pass="{{ $subject->pass_marks }}"
                                    value="{{ $existing?->marks_obtained ?? '' }}"
                                    min="0" max="{{ $subject->full_marks }}" step="0.5"
                                    placeholder="—"
                                    style="text-align:center; padding:5px 6px; font-family:var(--font-en); {{ $existing && $existing->grade==='F'?'border-color:#dc2626; color:#dc2626;':'' }}">
                                @if($existing)
                                <div style="font-size:10.5px; font-weight:700; margin-top:2px; color:{{ $existing->grade==='A+'?'#16a34a':($existing->grade==='F'?'#dc2626':'var(--text-muted)') }}">
                                    {{ $existing->grade }}
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:14px 20px; border-top:1px solid var(--border); display:flex; justify-content:flex-end;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill"></i> সব নম্বর সংরক্ষণ</button>
            </div>
        </div>
    </form>
    @elseif(request('exam_id') && request('class_id'))
    <div class="card"><div class="empty-state" style="padding:50px;"><i class="bi bi-people"></i><p>কোনো ছাত্র নেই</p></div></div>
    @else
    <div class="card"><div class="empty-state" style="padding:50px;"><i class="bi bi-pencil-square" style="font-size:48px; color:var(--border);"></i><p style="margin-top:12px; font-size:15px;">পরীক্ষা ও শ্রেণী বেছে নিন</p></div></div>
    @endif
</div>

{{-- View results tab section --}}
<div id="tab-view" class="tab-content" style="display:{{ request('tab')==='view'?'block':'none' }}; margin-top:20px;">
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="padding:16px 20px;">
            <form method="GET" action="{{ route('results.index') }}">
                <input type="hidden" name="tab" value="view">
                <div class="filter-bar">
                    <select name="exam_id_view" class="form-select" onchange="this.form.submit()">
                        <option value="">পরীক্ষা বেছে নিন</option>
                        @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id_view')==$exam->id?'selected':'' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                    <select name="class_id_view" class="form-select" onchange="this.form.submit()">
                        <option value="">শ্রেণী বেছে নিন</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id_view')==$class->id?'selected':'' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
    @if(isset($viewResults) && $viewResults && $viewResults->count())
    <div class="card">
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>ছাত্র</th><th style="text-align:center">মোট নম্বর</th><th style="text-align:center">গড়</th><th style="text-align:center">GPA</th><th style="text-align:center">ফলাফল</th><th style="text-align:center">মার্কশিট</th></tr></thead>
                <tbody>
                    @foreach($viewResults as $r)
                    <tr>
                        <td>
                            <div style="font-weight:600; font-size:13.5px;">{{ $r->student->name }}</div>
                            <div style="font-size:11.5px; color:var(--text-muted);">{{ $r->student->student_id }}</div>
                        </td>
                        <td style="text-align:center; font-family:var(--font-en); font-weight:600;">{{ $r->total_marks }}</td>
                        <td style="text-align:center; font-family:var(--font-en);">{{ number_format($r->average,1) }}%</td>
                        <td style="text-align:center; font-family:var(--font-en); font-weight:700; color:var(--green);">{{ number_format($r->gpa,2) }}</td>
                        <td style="text-align:center;"><span class="badge {{ $r->passed?'badge-green':'badge-red' }}">{{ $r->passed?'✅ উত্তীর্ণ':'❌ অনুত্তীর্ণ' }}</span></td>
                        <td style="text-align:center;">
                            <a href="{{ route('results.marksheet',[$r->student_id,request('exam_id_view')]) }}" class="btn btn-icon btn-outline btn-sm" target="_blank"><i class="bi bi-file-earmark-pdf"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- Merit list tab section --}}
<div id="tab-merit" class="tab-content" style="display:{{ request('tab')==='merit'?'block':'none' }}; margin-top:20px;">
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="padding:16px 20px;">
            <form method="GET" action="{{ route('results.index') }}">
                <input type="hidden" name="tab" value="merit">
                <div class="filter-bar">
                    <select name="exam_id_merit" class="form-select" onchange="this.form.submit()">
                        <option value="">পরীক্ষা বেছে নিন</option>
                        @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id_merit')==$exam->id?'selected':'' }}>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                    <select name="class_id_merit" class="form-select" onchange="this.form.submit()">
                        <option value="">শ্রেণী বেছে নিন</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id_merit')==$class->id?'selected':'' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @if(request('exam_id_merit') && request('class_id_merit'))
                    <a href="{{ route('results.merit-pdf',[request('exam_id_merit'),request('class_id_merit')]) }}" class="btn btn-outline btn-sm" target="_blank"><i class="bi bi-printer"></i> PDF</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @if(isset($meritList) && $meritList && $meritList->count())
    <div class="card">
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th style="text-align:center">স্থান</th><th>ছাত্র</th><th style="text-align:center">মোট</th><th style="text-align:center">GPA</th><th style="text-align:center">গ্রেড</th><th style="text-align:center">মার্কশিট</th></tr></thead>
                <tbody>
                    @foreach($meritList as $i => $r)
                    <tr>
                        <td style="text-align:center; font-size:20px;">
                            @if($i===0)🥇@elseif($i===1)🥈@elseif($i===2)🥉@else<span style="font-size:14px; font-family:var(--font-en);">{{ $i+1 }}</span>@endif
                        </td>
                        <td>
                            <div style="font-weight:600; font-size:13.5px;">{{ $r->student->name }}</div>
                            <div style="font-size:11.5px; color:var(--text-muted);">{{ $r->student->student_id }}</div>
                        </td>
                        <td style="text-align:center; font-family:var(--font-en); font-weight:600;">{{ $r->total_marks }}</td>
                        <td style="text-align:center; font-family:var(--font-en); font-weight:700; color:var(--green);">{{ number_format($r->gpa,2) }}</td>
                        <td style="text-align:center;"><span class="badge badge-green">{{ $r->overall_grade }}</span></td>
                        <td style="text-align:center;">
                            <a href="{{ route('results.marksheet',[$r->student_id,request('exam_id_merit')]) }}" class="btn btn-icon btn-outline btn-sm" target="_blank"><i class="bi bi-file-earmark-pdf"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function switchTab(tab, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.style.display='none');
    document.getElementById('tab-'+tab).style.display='block';
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}
document.querySelectorAll('.marks-input').forEach(input => {
    input.addEventListener('input', function() {
        const pass = parseFloat(this.dataset.pass);
        const val  = parseFloat(this.value);
        if (!isNaN(val)) {
            this.style.borderColor = val < pass ? '#dc2626' : 'var(--border)';
            this.style.color = val < pass ? '#dc2626' : 'var(--text)';
        }
    });
});
</script>
@endpush
