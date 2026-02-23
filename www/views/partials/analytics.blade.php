@php
    $googleAnalyticsId = $_ENV['GOOGLE_ANALYTICS_ID'] ?? null;
@endphp

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