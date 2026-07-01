@extends('layouts.app')
@section('page-title', isset($welfare)?'সহায়তা সম্পাদনা':'নতুন সহায়তা')
@section('breadcrumb', 'Welfare / Form')
@section('content')
<div style="max-width:700px; margin:0 auto;">
<form method="POST" action="{{ isset($welfare)?route('welfare.update',$welfare):route('welfare.store') }}">
    @csrf @if(isset($welfare)) @method('PUT') @endif
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="bi bi-heart-fill" style="color:#dc2626;"></i> সহায়তার তথ্য</div></div>
        <div class="card-body">
            <div class="form-grid form-grid-2" style="gap:14px;">
                <div style="grid-column:span 2;">
                    <label class="form-label">ছাত্র <span style="color:red">*</span></label>
                    <select name="student_id" class="form-select" required>
                        <option value="">বেছে নিন</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id',$welfare->student_id??'')==$student->id?'selected':'' }}>
                            {{ $student->name }} — {{ $student->studentClass->name??'' }} ({{ $student->student_id }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="grid-column:span 2;">
                    <label class="form-label">শিরোনাম <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title',$welfare->title??'') }}" placeholder="যেমন: বার্ষিক বৃত্তি, বই কিনে দেওয়া">
                </div>
                <div>
                    <label class="form-label">সহায়তার ধরন <span style="color:red">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="scholarship" {{ old('type',$welfare->type??'')==='scholarship'?'selected':'' }}>🎓 বৃত্তি</option>
                        <option value="book"        {{ old('type',$welfare->type??'')==='book'       ?'selected':'' }}>📚 বই সহায়তা</option>
                        <option value="food"        {{ old('type',$welfare->type??'')==='food'       ?'selected':'' }}>🍱 খাদ্য সহায়তা</option>
                        <option value="clothing"    {{ old('type',$welfare->type??'')==='clothing'   ?'selected':'' }}>👕 পোশাক সহায়তা</option>
                        <option value="medical"     {{ old('type',$welfare->type??'')==='medical'    ?'selected':'' }}>🏥 চিকিৎসা সহায়তা</option>
                        <option value="other"       {{ old('type',$welfare->type??'')==='other'      ?'selected':'' }}>📌 অন্যান্য</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">তারিখ <span style="color:red">*</span></label>
                    <input type="date" name="date" class="form-control" required value="{{ old('date',isset($welfare)?$welfare->date->format('Y-m-d'):today()->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="form-label">মাস</label>
                    <input type="month" name="month_year" class="form-control" value="{{ old('month_year',$welfare->month_year??now()->format('Y-m')) }}">
                </div>
                <div>
                    <label class="form-label">মোট পরিমাণ (৳) <span style="color:red">*</span></label>
                    <input type="number" name="total_amount" class="form-control" required min="0" step="0.01" value="{{ old('total_amount',$welfare->total_amount??'') }}" id="totalAmount" onchange="calcContributions()">
                </div>
                <div>
                    <label class="form-label">ছাত্র প্রদান (৳)</label>
                    <input type="number" name="student_contribution" class="form-control" min="0" step="0.01" value="{{ old('student_contribution',$welfare->student_contribution??0) }}" id="studentContrib" onchange="calcContributions()">
                </div>
                <div>
                    <label class="form-label">প্রতিষ্ঠান প্রদান (৳)</label>
                    <input type="number" name="institution_contribution" class="form-control" min="0" step="0.01" value="{{ old('institution_contribution',$welfare->institution_contribution??0) }}" id="instContrib" onchange="calcContributions()">
                </div>
                <div>
                    <label class="form-label">দাতা প্রদান (৳)</label>
                    <input type="number" name="donor_contribution" class="form-control" min="0" step="0.01" value="{{ old('donor_contribution',$welfare->donor_contribution??0) }}" id="donorContrib" onchange="calcContributions()">
                </div>
                <div>
                    <label class="form-label">দাতার নাম</label>
                    <input type="text" name="donor_name" class="form-control" value="{{ old('donor_name',$welfare->donor_name??'') }}" placeholder="ঐচ্ছিক">
                </div>

                <!-- Summary -->
                <div id="contribSummary" style="grid-column:span 2; padding:12px 16px; background:var(--green-light); border-radius:var(--radius-sm); border-left:3px solid var(--green); display:none;">
                    <div style="font-size:13px; font-weight:600; color:var(--green); margin-bottom:4px;">বিতরণ সারসংক্ষেপ:</div>
                    <div id="summaryText" style="font-size:12.5px; color:var(--text-muted);"></div>
                </div>

                <div style="grid-column:span 2;">
                    <label class="form-label">মন্তব্য</label>
                    <textarea name="note" class="form-control" rows="2" placeholder="ঐচ্ছিক...">{{ old('note',$welfare->note??'') }}</textarea>
                </div>
            </div>
        </div>
        <div style="padding:14px 20px; border-top:1px solid var(--border); display:flex; gap:10px; justify-content:flex-end;">
            <a href="{{ route('welfare.index') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> বাতিল</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> {{ isset($welfare)?'আপডেট':'সংরক্ষণ' }}</button>
        </div>
    </div>
</form>
</div>
@endsection

@push('scripts')
<script>
function calcContributions() {
    const total   = parseFloat(document.getElementById('totalAmount').value) || 0;
    const student = parseFloat(document.getElementById('studentContrib').value) || 0;
    const inst    = parseFloat(document.getElementById('instContrib').value) || 0;
    const donor   = parseFloat(document.getElementById('donorContrib').value) || 0;
    const sum     = student + inst + donor;
    const summary = document.getElementById('contribSummary');
    const text    = document.getElementById('summaryText');

    if (total > 0) {
        summary.style.display = 'block';
        const remaining = total - sum;
        text.innerHTML = `মোট: ৳${total.toLocaleString()} | ছাত্র: ৳${student.toLocaleString()} | প্রতিষ্ঠান: ৳${inst.toLocaleString()} | দাতা: ৳${donor.toLocaleString()} ${remaining > 0 ? '<span style="color:#dc2626; font-weight:600;">| বাকি: ৳' + remaining.toLocaleString() + '</span>' : '<span style="color:#16a34a; font-weight:600;">✓ পূর্ণ বিতরণ</span>'}`;
    }
}
</script>
@endpush
