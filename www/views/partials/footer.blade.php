<footer x-data>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Garage24</h4>
                <p>Сучасні рішення для управління транспортом.</p>
            </div>
            <div class="footer-col">
                <h4>Продукт</h4>
                <ul>
                    <li><a href="/features">Можливості</a></li>
                    <li><a href="/target">Кому підійде</a></li>
                    <li><a href="/pricing">Тарифи</a></li>
                    <li><a href="/blog">Блог</a></li>
                    <li><a href="/docs">Документація</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Інформація</h4>
                <ul>
                    <li><a href="/contacts">Зв'язатися з нами</a></li>
                    <li><a href="#" @click.prevent="$dispatch('open-text-modal', { title: 'Політика конфіденційності', slug: 'privacy' })">Політика конфіденційності</a></li>
                    <li><a href="#" @click.prevent="$dispatch('open-text-modal', { title: 'Угода користувача', slug: 'terms' })">Угода користувача</a></li>
                    <li><a href="#" @click.prevent="$dispatch('open-text-modal', { title: 'Публічна оферта', slug: 'offer' })">Публічна оферта</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>
                &copy; {{ date('Y') }} Garage24. Всі права захищено.
            </p>
        </div>
    </div>
</footer>