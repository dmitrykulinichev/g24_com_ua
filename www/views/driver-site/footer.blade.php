{{--
    САЙТ ВОДІЯ (app/DriverSite): підвал домену driver.g24.com.ua — без посилань на SaaS-сайт; політика конфіденційності
    (для cookie-банера) — на основному домені. Основний лендінг — views/partials/footer.blade.php.
--}}
<footer class="bg-slate-900 text-slate-300 py-8 border-t border-slate-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row gap-3 justify-between text-sm">
        <p>&copy; {{ date('Y') }} Garage24. {{ t('footer.rights') }}</p>
        <a href="{{ lurl('/privacy') }}" class="hover:text-white transition-colors">{{ t('footer.privacy') }}</a>
    </div>
</footer>
