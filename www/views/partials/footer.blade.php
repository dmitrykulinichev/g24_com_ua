<footer class="bg-slate-900 text-slate-300 py-12 border-t border-slate-800" x-data>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="col-span-1 md:col-span-2">
                <h4 class="text-white font-bold text-lg mb-4">Garage24</h4>
                <p class="text-sm leading-relaxed max-w-xs">
                    Сучасні рішення для управління транспортом. Автоматизуйте рутину та збільшуйте прибуток.
                </p>
            </div>
            <div>
                <h4 class="text-white font-bold text-lg mb-4">Продукт</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/features" class="hover:text-white transition-colors">Можливості</a></li>
                    <li><a href="/target" class="hover:text-white transition-colors">Кому підійде</a></li>
                    <li><a href="/pricing" class="hover:text-white transition-colors">Тарифи</a></li>
                    <li><a href="/blog" class="hover:text-white transition-colors">Блог</a></li>
                    <li><a href="/docs" class="hover:text-white transition-colors">Документація</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold text-lg mb-4">Інформація</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/contacts" class="hover:text-white transition-colors">Зв'язатися з нами</a></li>
                    <li><a href="/privacy" class="hover:text-white transition-colors">Політика конфіденційності</a></li>
                    <li><a href="/terms" class="hover:text-white transition-colors">Угода користувача</a></li>
                    <li><a href="/offer" class="hover:text-white transition-colors">Публічна оферта</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-800 pt-8 text-center text-xs text-slate-500">
            <p>
                &copy; {{ date('Y') }} Garage24. Всі права захищено.
            </p>
        </div>
    </div>
</footer>
