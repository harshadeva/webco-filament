@php
    $record = $getRecord();

    $backgroundColor = $record->productColor->color_code ?? '#6b7280';
@endphp

<div class="px-4 py-2 text-white text-center rounded" style="background-color: {{ $backgroundColor }};">
    Hello
</div>
