@props(['items', 'label' => 'mahasiswa'])
@php
    $total = collect($items)->sum('count');
    $start = 0;
    $segments = [];
    foreach ($items as $item) {
        $end = $total > 0 ? $start + $item['count'] / $total * 100 : $start;
        $segments[] = $item['color'].' '.$start.'% '.$end.'%';
        $start = $end;
    }
    $fill = $total > 0 ? 'conic-gradient('.implode(', ', $segments).')' : '#e9ede4';
@endphp
<div class="dash-distribution">
    <div class="dash-donut" style="--donut-fill: {{ $fill }}" role="img" aria-label="Total {{ $total }} {{ $label }} dalam distribusi kegiatan"><div><strong>{{ number_format($total, 0, ',', '.') }}</strong><span>{{ $label }}</span></div></div>
    <div class="dash-distribution-legend">
        @foreach($items as $item)
        @php $percentage = $total > 0 ? round($item['count'] / $total * 100) : 0; @endphp
        <div class="dash-legend-row" style="--item-color: {{ $item['color'] }}"><span class="dash-legend-dot" aria-hidden="true"></span><span>{{ $item['name'] }}</span><strong>{{ number_format($item['count'], 0, ',', '.') }}</strong><small>{{ $percentage }}%</small></div>
        @endforeach
        @if($total === 0)<p class="dash-empty-note">Belum ada data kegiatan untuk ditampilkan.</p>@endif
    </div>
</div>
