{{-- Позначка статті документації (поле `badge` пункту content/docs2/menu.json, напр. «new» — нова документація). --}}
@if (!empty($badge))
    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700 {{ $class ?? '' }}">{{ t('docs.badge.' . $badge) }}</span>
@endif
