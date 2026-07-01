@if (true)
<div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
    <div style="display:flex; align-items:center; gap:12px;">
        <div style="font-size:13px; color:var(--text-muted);">
            মোট <strong>{{ $paginator->total() }}</strong> | দেখাচ্ছে <strong>{{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}</strong>
        </div>
        <div style="display:flex; align-items:center; gap:6px; font-size:13px; color:var(--text-muted);">
            <span>প্রতি পাতায়:</span>
            <select onchange="window.location.href=window.location.pathname+'?perpage='+this.value" style="border:1px solid var(--border); border-radius:6px; padding:3px 8px; font-size:13px; font-family:var(--font-bn); color:var(--text); background:#fff; cursor:pointer;">
                @foreach([10,25,50,100] as $size)
                <option value="{{ $size }}" {{ $paginator->perPage()==$size?'selected':'' }}>{{ $size }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div style="display:flex; align-items:center; gap:4px;">

        @if ($paginator->onFirstPage())
        <span style="width:32px; height:32px; border-radius:6px; border:1px solid var(--border); color:var(--text-muted); font-size:12px; display:inline-flex; align-items:center; justify-content:center; cursor:not-allowed;"><i class="bi bi-chevron-left"></i></span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" style="width:32px; height:32px; border-radius:6px; border:1px solid var(--border); color:var(--text); font-size:12px; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; transition:all 0.15s;" onmouseover="this.style.background='var(--green-light)'; this.style.color='var(--green)'; this.style.borderColor='var(--green)'" onmouseout="this.style.background=''; this.style.color='var(--text)'; this.style.borderColor='var(--border)'"><i class="bi bi-chevron-left"></i></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
            <span style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; font-size:13px; color:var(--text-muted);">...</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <span style="width:32px; height:32px; border-radius:6px; background:var(--green); color:#fff; font-size:13px; font-weight:600; display:inline-flex; align-items:center; justify-content:center;">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" style="width:32px; height:32px; border-radius:6px; border:1px solid var(--border); color:var(--text); font-size:13px; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; transition:all 0.15s;" onmouseover="this.style.background='var(--green-light)'; this.style.color='var(--green)'; this.style.borderColor='var(--green)'" onmouseout="this.style.background=''; this.style.color='var(--text)'; this.style.borderColor='var(--border)'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="width:32px; height:32px; border-radius:6px; border:1px solid var(--border); color:var(--text); font-size:12px; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; transition:all 0.15s;" onmouseover="this.style.background='var(--green-light)'; this.style.color='var(--green)'; this.style.borderColor='var(--green)'" onmouseout="this.style.background=''; this.style.color='var(--text)'; this.style.borderColor='var(--border)'"><i class="bi bi-chevron-right"></i></a>
        @else
        <span style="width:32px; height:32px; border-radius:6px; border:1px solid var(--border); color:var(--text-muted); font-size:12px; display:inline-flex; align-items:center; justify-content:center; cursor:not-allowed;"><i class="bi bi-chevron-right"></i></span>
        @endif

    </div>
</div>
@endif
