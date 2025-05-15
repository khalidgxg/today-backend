@props(['color'])

<div class="flex items-center gap-2">
    <div 
        class="w-6 h-6 rounded border"
        style="background-color: {{ $color }};"
    ></div>
    <span>{{ $color }}</span>
</div> 