@extends('layouts.app')
@section('page-title', 'বেতন ও ফি')
@section('breadcrumb', 'Home / Fees')

@section('content')

<!-- Summary Cards -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px;">
    <div style="background: #fff; border-radius: var(--radius); border: 1px solid var(--border); padding: 18px 20px;">
        <div style="font-size: 11.5px; color: var(--text-muted); font-family: var(--font-en); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">এ মাসে সংগ্রহ</div>
        <div style="font-size: 24px; font-weight: 700; color: #16a34a;">৳{{ number_format($monthlyCollection) }}</div>
    </div>
    <div style="background: #fff; border-radius: var(--radius); border: 1px solid var(--border); padding: 18px 20px;">
        <div style="font-size: 11.5px; color: var(--text-muted); font-family: var(--font-en); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">আজকের সংগ্রহ</div>
        <div style="font-size: 24px; font-weight: 700; color: #1d4ed8;">৳{{ number_format($todayCollection) }}</div>
    </div>
    <div style="background: #fff; border-radius: var(--radius); border: 1px solid var(--border); padding: 18px 20px;">
        <div style="font-size: 11.5px; color: var(--text-muted); font-family: var(--font-en); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">মোট বকেয়া</div>
        <div style="font-size: 24px; font-weight: 700; color: #dc2626;">৳{{ number_format($totalDue) }}</div>
    </div>
    <div style="background: #fff; border-radius: var(--radius); border: 1px solid var(--border); padding: 18px 20px;">
        <div style="font-size: 11.5px; color: var(--text-muted); font-family: var(--font-en); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">বকেয়া ছাত্র</div>
        <div style="font-size: 24px; font-weight: 700; color: #d97706;">{{ number_format($dueStudentCount) }} জন</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 380px; gap: 20px; align-items: start;">

    <!-- Recent Collections Table -->
    <div class="card">
        <div class="card-header" style="padding: 16px 22px;">
            <div class="card-title"><i class="bi bi-cash-coin"></i> সাম্প্রতিক সংগ্রহ</div>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('fees.due') }}" class="btn btn-outline btn-sm">
                    <i class="bi bi-exclamation-triangle"></i> বকেয়া তালিকা
                </a>
                <a href="{{ route('fees.report') }}" class="btn btn-outline btn-sm">
                    <i class="bi bi-file-earmark-bar-graph"></i> রিপোর্ট
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div style="padding: 12px 22px; border-bottom: 1px solid var(--border);">
            <form method="GET" action="{{ route('fees.index') }}">
                <div class="filter-bar">
                    <input type="text" name="search" class="form-control" placeholder="ছাত্রের নাম/আইডি/রশিদ..."
                           value="{{ request('search') }}" style="min-width: 200px;">
                    <input type="month" name="month" class="form-control" value="{{ request('month', date('Y-m')) }}">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>রশিদ নং</th>
                        <th>ছাত্র</th>
                        <th>ফি ধরন</th>
                        <th>পরিমাণ</th>
                        <th>পরিশোধ</th>
                        <th>বকেয়া</th>
                        <th>তারিখ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $fee)
                    <tr>
                        <td>
                            <span style="font-family: var(--font-en); font-size: 12px; font-weight: 600; color: var(--green);">{{ $fee->receipt_no }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 13px;">{{ $fee->student->name ?? '—' }}</div>
                            <div style="font-size: 11.5px; color: var(--text-muted);">{{ $fee->student->studentClass->name ?? '' }}</div>
                        </td>
                        <td style="font-size: 13px;">{{ $fee->feeCategory->name ?? '—' }}</td>
                        <td style="font-family: var(--font-en); font-size: 13px;">৳{{ number_format($fee->amount) }}</td>
                        <td style="font-family: var(--font-en); font-size: 13px; color: #16a34a; font-weight: 600;">
                            ৳{{ number_format($fee->paid_amount) }}
                        </td>
                        <td>
                            @if($fee->due_amount > 0)
                            <span class="badge badge-red">৳{{ number_format($fee->due_amount) }}</span>
                            @else
                            <span class="badge badge-green">পরিশোধ</span>
                            @endif
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted); font-family: var(--font-en);">
                            {{ $fee->payment_date->format('d/m/Y') }}
                        </td>
                        <td>
                            <a href="{{ route('fees.receipt', $fee->receipt_no) }}" class="btn btn-icon btn-outline btn-sm" data-tooltip="রশিদ ডাউনলোড" target="_blank">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state"><i class="bi bi-cash"></i><p>কোনো রেকর্ড নেই</p></div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($collections->hasPages())
        <div class="pagination-wrap">{{ $collections->withQueryString()->links() }}</div>
        @endif
    </div>

    <!-- Collect Fee Form -->
    <div class="card" style="position: sticky; top: 84px;">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-plus-circle-fill"></i> ফি গ্রহণ করুন</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('fees.collect') }}" id="feeForm">
                @csrf

                <!-- Student Search -->
                <div style="margin-bottom: 14px;">
                    <label class="form-label">ছাত্র খুঁজুন <span style="color: red;">*</span></label>
                    <div style="position: relative;">
                        <input type="text" id="studentSearch" class="form-control"
                               placeholder="নাম বা আইডি টাইপ করুন..."
                               autocomplete="off">
                        <div id="studentDropdown" style="
                            position: absolute; top: 100%; left: 0; right: 0;
                            background: #fff; border: 1.5px solid var(--border);
                            border-radius: var(--radius-sm); max-height: 200px;
                            overflow-y: auto; z-index: 100; display: none;
                            box-shadow: var(--shadow);
                        "></div>
                    </div>
                    <input type="hidden" name="student_id" id="selectedStudentId" required>
                    <div id="selectedStudentInfo" style="margin-top: 8px; display: none;
                         padding: 10px 13px; background: var(--green-light); border-radius: var(--radius-sm);
                         border-left: 3px solid var(--green);">
                    </div>
                </div>

                <div style="margin-bottom: 14px;">
                    <label class="form-label">ফি ধরন <span style="color: red;">*</span></label>
                    <select name="fee_category_id" class="form-select" required id="feeCategory">
                        <option value="">বেছে নিন</option>
                        @foreach($feeCategories as $cat)
                        <option value="{{ $cat->id }}" data-amount="{{ $cat->amount }}">
                            {{ $cat->name }} (৳{{ number_format($cat->amount) }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label class="form-label">মাস/বছর</label>
                        <input type="month" name="month_year" class="form-control" value="{{ date('Y-m') }}">
                    </div>
                    <div>
                        <label class="form-label">মোট পরিমাণ</label>
                        <input type="number" name="amount" class="form-control" id="feeAmount"
                               placeholder="৳" step="0.01" readonly style="background: var(--bg);">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                    <div>
                        <label class="form-label">ছাড়</label>
                        <input type="number" name="discount" class="form-control" id="discount"
                               value="0" min="0" step="0.01">
                    </div>
                    <div>
                        <label class="form-label">পরিশোধিত পরিমাণ <span style="color: red;">*</span></label>
                        <input type="number" name="paid_amount" class="form-control" id="paidAmount"
                               required min="0" step="0.01" placeholder="৳">
                    </div>
                </div>

                <!-- Due Preview -->
                <div id="duePreview" style="
                    padding: 10px 13px; background: #fef3c7; border-radius: var(--radius-sm);
                    border-left: 3px solid #f59e0b; margin-bottom: 14px; display: none;
                ">
                    <span style="font-size: 13px; color: #b45309;">বকেয়া থাকবে: <strong id="dueAmount">৳0</strong></span>
                </div>

                <div style="margin-bottom: 14px;">
                    <label class="form-label">পেমেন্ট পদ্ধতি <span style="color: red;">*</span></label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash">💵 নগদ</option>
                        <option value="bkash">📱 bKash</option>
                        <option value="nagad">📱 Nagad</option>
                        <option value="bank">🏦 ব্যাংক</option>
                    </select>
                </div>

                <div style="margin-bottom: 18px;">
                    <label class="form-label">লেনদেন আইডি</label>
                    <input type="text" name="transaction_id" class="form-control"
                           placeholder="ঐচ্ছিক (bKash/Bank এর জন্য)">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                    <i class="bi bi-check-circle-fill"></i> ফি গ্রহণ করুন ও রশিদ প্রিন্ট
                </button>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Fee amount auto-fill
    document.getElementById('feeCategory').addEventListener('change', function() {
        const amount = this.options[this.selectedIndex]?.dataset?.amount || '';
        document.getElementById('feeAmount').value = amount;
        document.getElementById('paidAmount').value = amount;
        calculateDue();
    });

    function calculateDue() {
        const amount = parseFloat(document.getElementById('feeAmount').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
        const due = Math.max(0, amount - discount - paid);
        const preview = document.getElementById('duePreview');
        if (due > 0) {
            preview.style.display = 'block';
            document.getElementById('dueAmount').textContent = '৳' + due.toLocaleString('bn');
        } else {
            preview.style.display = 'none';
        }
    }

    ['discount','paidAmount'].forEach(id =>
        document.getElementById(id).addEventListener('input', calculateDue)
    );

    // Student Search
    let searchTimeout;
    document.getElementById('studentSearch').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { document.getElementById('studentDropdown').style.display = 'none'; return; }
        searchTimeout = setTimeout(() => {
            fetch(`/api/students/search?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    const dd = document.getElementById('studentDropdown');
                    if (!data.length) { dd.style.display = 'none'; return; }
                    dd.innerHTML = data.map(s => `
                        <div onclick="selectStudent(${s.id}, '${s.name}', '${s.student_id}', '${s.class_name || ''}')"
                             style="padding: 10px 14px; cursor: pointer; border-bottom: 1px solid var(--border); font-size: 13.5px;"
                             onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background='#fff'">
                            <div style="font-weight: 600;">${s.name}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">${s.student_id} · ${s.class_name || ''}</div>
                        </div>
                    `).join('');
                    dd.style.display = 'block';
                });
        }, 300);
    });

    function selectStudent(id, name, sid, cls) {
        document.getElementById('selectedStudentId').value = id;
        document.getElementById('studentSearch').value = name;
        document.getElementById('studentDropdown').style.display = 'none';
        const info = document.getElementById('selectedStudentInfo');
        info.style.display = 'block';
        info.innerHTML = `<div style="font-size: 13px; font-weight: 600; color: var(--green);">${name}</div>
            <div style="font-size: 12px; color: var(--text-muted);">${sid} · ${cls}</div>`;
    }

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#studentSearch') && !e.target.closest('#studentDropdown')) {
            document.getElementById('studentDropdown').style.display = 'none';
        }
    });
</script>
@endpush
