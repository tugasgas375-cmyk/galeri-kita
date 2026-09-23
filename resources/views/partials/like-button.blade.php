@php($liked = $liked ?? false)
@php($compact = $compact ?? false)
<button
    type="button"
    class="like-btn inline-flex shrink-0 items-center gap-2 rounded-full border {{ $compact ? 'border-rose-200 bg-white/90 px-4 py-2' : 'border-rose-200 bg-white px-6 py-2.5' }} text-sm shadow-sm backdrop-blur transition hover:-translate-y-0.5 hover:bg-rose-50 {{ $liked ? 'liked' : '' }}"
    data-endpoint="{{ route('moments.like', $moment) }}"
    data-liked="{{ $liked ? '1' : '0' }}"
    aria-label="Suka momen ini"
    title="Suka momen ini"
>
    <span class="like-icon text-lg leading-none">&#10084;</span>
    <span class="like-count font-semibold text-rose-600">{{ number_format($moment->likes, 0, ',', '.') }}</span>
    @unless ($compact)
        <span class="like-label font-medium text-stone-500">Suka</span>
    @endunless
</button>