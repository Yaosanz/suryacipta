@php
    $record = $getRecord();
    $url = \Illuminate\Support\Facades\Storage::disk($record->disk)->url($record->file_path);
    $isVideo = str_starts_with($record->mime_type, 'video/');
@endphp

<div class="cms-table-media">
    @if ($isVideo)
        <video muted preload="metadata" aria-label="{{ $record->alt_text }}">
            <source src="{{ $url }}" type="{{ $record->mime_type }}">
        </video>
        <span>Video</span>
    @else
        <img src="{{ $url }}" alt="{{ $record->alt_text }}" loading="lazy">
    @endif
</div>
