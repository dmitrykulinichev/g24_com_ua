@php
    // Спробуємо отримати з ENV, якщо немає - використовуємо хардкод (тимчасово, для гарантії роботи)
    $googleAnalyticsId = getenv('GOOGLE_ANALYTICS_ID') ?: 'G-QEZ3ZYHQMP';
@endphp

<!-- Analytics Debug: ID is '{{ $googleAnalyticsId }}' -->

@if($googleAnalyticsId)
<script>
    // Define dataLayer and the gtag function.
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}

    // Google Consent Mode v2: Default state (Denied)
    gtag('consent', 'default', {
        'ad_storage': 'denied',
        'ad_user_data': 'denied',
        'ad_personalization': 'denied',
        'analytics_storage': 'denied'
    });
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>

<script>
    gtag('js', new Date());
    gtag('config', '{{ $googleAnalyticsId }}');
</script>
@endif