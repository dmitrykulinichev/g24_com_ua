<footer x-data>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>G24.com.ua</h4>
                <p>Сучасні рішення для управління транспортом.</p>
            </div>
            <div class="footer-col">
                <h4>Продукт</h4>
                <ul>
                    <li><a href="/features">Можливості</a></li>
                    <li><a href="/pricing">Тарифи</a></li>
                    <li><a href="/blog">Блог</a></li>
                    <li><a href="/docs">Документація</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Контакти</h4>
                <ul>
                    <li><a href="/contacts">Зв'язатися з нами</a></li>
                    <li>Email: info@g24.com.ua</li>
                    <li>Тел: +380 00 000 0000</li>
                    <li>Київ, Україна</li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>
                &copy; {{ date('Y') }} G24.com.ua. Всі права захищено.
                <br>
                <a href="#" @click.prevent="$dispatch('open-text-modal', { title: 'Політика конфіденційності', slug: 'privacy' })">Політика конфіденційності</a> |
                <a href="#" @click.prevent="$dispatch('open-text-modal', { title: 'Угода користувача', slug: 'terms' })">Угода користувача</a>
            </p>
        </div>
    </div>
</footer>

<!-- Підключаємо модальні вікна -->
@include('partials.modal-form')
@include('partials.modal-text')
@include('partials.cookie-consent')