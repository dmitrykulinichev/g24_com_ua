{{--
    Скрін з реєстру (content/screens.json) мовою сторінки; не знято цією мовою — українська.
    @include('partials.screen', ['id' => 'cabinet-dashboard', 'class' => '…'])
    'eager' => true — для першого екрана (головна картинка сторінки): без lazy, з високим пріоритетом.
--}}
@php
    $screen = \App\Screens\ScreenRegistry::instance()->image($id);
    $screenEager = $eager ?? false;
@endphp
<img src="{{ $screen['src'] }}" alt="{{ t('screens.' . $id) }}"
     @if ($screen['width']) width="{{ $screen['width'] }}" height="{{ $screen['height'] }}" @endif
     @if ($screenEager) fetchpriority="high" @else loading="lazy" decoding="async" @endif
     class="{{ $class ?? '' }}">
