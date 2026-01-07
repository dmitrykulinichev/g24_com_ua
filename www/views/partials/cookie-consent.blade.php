<!-- Cookie Consent (Alpine.js) -->
<div x-data="{
    showCookie: false,
    accept() {
        localStorage.setItem('cookie_accepted', 'true');
        this.showCookie = false;
    }
}"
x-init="
    setTimeout(() => {
        if (!localStorage.getItem('cookie_accepted')) {
            showCookie = true;
        }
    }, 1000);
"
x-show="showCookie"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0 translate-y-full"
x-transition:enter-end="opacity-100 translate-y-0"
x-transition:leave="transition ease-in duration-300"
x-transition:leave-start="opacity-100 translate-y-0"
x-transition:leave-end="opacity-0 translate-y-full"
style="display: none;"
class="cookie-banner">

    <div class="cookie-content">
        <div class="cookie-text">
            <p>🍪 Ми використовуємо файли cookie для покращення роботи сайту. Продовжуючи перегляд, ви погоджуєтесь з нашою <a href="#" @click.prevent="$dispatch('open-text-modal', { title: 'Політика конфіденційності', slug: 'privacy' })">політикою конфіденційності</a>.</p>
        </div>
        <div class="cookie-actions">
            <button @click="accept()" class="btn-primary btn-sm">Зрозуміло</button>
        </div>
    </div>

</div>

<style>
    .cookie-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: var(--white);
        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 900;
        border-top: 1px solid var(--border-color);
        padding: 1rem;
    }

    .cookie-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
    }

    .cookie-text p {
        margin: 0;
        font-size: 0.95rem;
        color: var(--text-color);
    }

    .cookie-text a {
        color: var(--primary-color);
        text-decoration: underline;
    }

    .btn-sm {
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .cookie-content {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
    }
</style>