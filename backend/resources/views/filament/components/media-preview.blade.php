@if ($url)
    <div class="cms-media-preview">
        @if ($type === 'video')
            <video controls preload="metadata" playsinline aria-label="{{ $alt ?: 'Pratinjau video' }}">
                <source src="{{ $url }}" type="video/mp4">
            </video>
        @else
            <img src="{{ $url }}" alt="{{ $alt ?: 'Pratinjau gambar' }}" loading="lazy">
        @endif
    </div>
@endif
