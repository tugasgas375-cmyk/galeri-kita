@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-col items-center gap-4">
        <div class="flex flex-wrap items-center justify-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-rose-100 bg-white/60 text-stone-300" aria-disabled="true">&lsaquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-rose-200 bg-white text-stone-500 transition hover:border-rose-400 hover:bg-rose-50 hover:text-rose-600">&lsaquo;</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Three Dots --}}
                @if (is_string($element))
                    <span class="inline-flex h-11 items-center px-3 text-stone-400">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-rose-500 to-rose-600 font-medium text-white shadow-lg shadow-rose-200">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-rose-200 bg-white text-stone-600 transition hover:border-rose-400 hover:bg-rose-50 hover:text-rose-600" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-rose-200 bg-white text-stone-500 transition hover:border-rose-400 hover:bg-rose-50 hover:text-rose-600">&rsaquo;</a>
            @else
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-rose-100 bg-white/60 text-stone-300" aria-disabled="true">&rsaquo;</span>
            @endif
        </div>

        <p class="text-xs text-stone-400">
            Menampilkan {{ $paginator->firstItem() ?? 0 }}&ndash;{{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} momen
        </p>
    </nav>
@endif