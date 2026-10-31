{{--
    Перемикач мови (app/I18n): та сама сторінка іншою мовою. Лише відкриті мови (config/i18n.php);
    одна мова — перемикача немає. variant: desktop (випадний список) | mobile (рядок кнопок).
--}}
@php
    $switcherLocales = \App\I18n\Locale::config()->available();
    $switcherCurrent = \App\I18n\Locale::current();
    $switcherPath = \App\I18n\Locale::path();
@endphp
@if (count($switcherLocales) > 1)
    @if (($variant ?? 'desktop') === 'mobile')
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2" aria-label="{{ t('language.label') }}">
            @foreach ($switcherLocales as $switcherLocale)
                <a href="{{ \App\I18n\LocalizedUrl::to($switcherPath, $switcherLocale) }}"
                   hreflang="{{ \App\I18n\Locale::config()->meta($switcherLocale, 'hreflang') }}"
                   lang="{{ $switcherLocale }}"
                   class="flex-1 text-center px-3 py-2 rounded-lg text-sm font-semibold transition-colors
                          {{ $switcherLocale === $switcherCurrent ? 'bg-blue-50 text-primary' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ \App\I18n\Locale::config()->meta($switcherLocale, 'short') }}
                </a>
            @endforeach
        </div>
    @else
        <div class="relative" x-data="{ langOpen: false }" @click.away="langOpen = false">
            <button type="button" @click="langOpen = !langOpen" aria-label="{{ t('language.label') }}"
                    class="flex items-center gap-1 px-2 py-2 text-sm font-semibold rounded-md transition-colors"
                    :class="{ 'text-slate-600 hover:text-slate-900': scrolled || !isDarkBg, 'text-white/80 hover:text-white': !scrolled && isDarkBg }">
                {{ \App\I18n\Locale::config()->meta($switcherCurrent, 'short') }}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
            </button>
            <div x-show="langOpen" x-transition style="display: none;"
                 class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-slate-100 py-1 z-[1001]">
                @foreach ($switcherLocales as $switcherLocale)
                    <a href="{{ \App\I18n\LocalizedUrl::to($switcherPath, $switcherLocale) }}"
                       hreflang="{{ \App\I18n\Locale::config()->meta($switcherLocale, 'hreflang') }}"
                       lang="{{ $switcherLocale }}"
                       class="block px-4 py-2 text-sm {{ $switcherLocale === $switcherCurrent ? 'text-primary font-semibold bg-blue-50' : 'text-slate-700 hover:bg-slate-50' }}">
                        {{ \App\I18n\Locale::config()->meta($switcherLocale, 'name') }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
@endif
