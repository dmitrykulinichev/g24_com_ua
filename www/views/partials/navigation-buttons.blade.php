<div class="nav-container-wrapper">
    @if(!empty($prevLink))
        <a href="{{ $prevLink }}" class="nav-item nav-prev">
            <span class="nav-arrow">←</span>
            <span class="nav-title">{{ $prevTitle }}</span>
        </a>
    @endif

    @if(!empty($nextLink))
        <a href="{{ $nextLink }}" class="nav-item nav-next">
            <span class="nav-title">{{ $nextTitle }}</span>
            <span class="nav-arrow">→</span>
        </a>
    @endif
</div>

<link rel="stylesheet" href="/assets/css/navigation.css?v={{ time() }}">
<script src="/assets/js/navigation.js?v={{ time() }}"></script>