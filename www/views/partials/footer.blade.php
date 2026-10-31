<footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800" x-data>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="col-span-1 md:col-span-2">
                <h4 class="text-white font-bold text-lg mb-4">Garage24</h4>
                <p class="text-sm leading-relaxed max-w-xs">
                    {{ t('footer.tagline') }}
                </p>
            </div>
            <div>
                <h4 class="text-white font-bold text-lg mb-4">{{ t('footer.product') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ lurl('/features') }}" class="hover:text-white transition-colors">{{ t('nav.features') }}</a></li>
                    <li><a href="{{ lurl('/target') }}" class="hover:text-white transition-colors">{{ t('footer.target') }}</a></li>
                    <li><a href="{{ lurl('/pricing') }}" class="hover:text-white transition-colors">{{ t('nav.pricing') }}</a></li>
                    <li><a href="{{ lurl('/blog') }}" class="hover:text-white transition-colors">{{ t('nav.blog') }}</a></li>
                    <li><a href="{{ lurl('/docs') }}" class="hover:text-white transition-colors">{{ t('nav.docs') }}</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold text-lg mb-4">{{ t('footer.info') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ lurl('/contacts') }}" class="hover:text-white transition-colors">{{ t('footer.contact') }}</a></li>
                    <li><a href="{{ lurl('/privacy') }}" class="hover:text-white transition-colors">{{ t('footer.privacy') }}</a></li>
                    <li><a href="{{ lurl('/terms') }}" class="hover:text-white transition-colors">{{ t('footer.terms') }}</a></li>
                    <li><a href="{{ lurl('/offer') }}" class="hover:text-white transition-colors">{{ t('footer.offer') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-8 text-center text-xs text-slate-500">
            <p>
                &copy; {{ date('Y') }} Garage24. {{ t('footer.rights') }}
            </p>
        </div>
    </div>
</footer>
