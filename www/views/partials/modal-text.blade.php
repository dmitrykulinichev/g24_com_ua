<!-- Текстова модалка (Alpine.js) -->
<div x-data="{
    showModal: false,
    title: '',
    content: '',
    loading: false,

    init() {
        window.addEventListener('open-text-modal', (event) => {
            this.showModal = true;
            this.title = event.detail.title;
            this.loadContent(event.detail.slug);
        });
    },

    loadContent(slug) {
        this.loading = true;
        this.content = '';

        fetch('/api/page/' + slug)
            .then(response => response.json())
            .then(data => {
                this.content = data.content;
                this.loading = false;
            })
            .catch(() => {
                this.content = '<p>Помилка завантаження.</p>';
                this.loading = false;
            });
    }
}"
x-show="showModal"
style="display: none;"
class="modal-overlay">

    <div class="modal-backdrop" @click="showModal = false"></div>

    <div class="modal-content text-modal">
        <button class="modal-close" @click="showModal = false">&times;</button>

        <h2 class="modal-title" x-text="title"></h2>

        <div class="modal-body">
            <div x-show="loading" style="text-align: center; padding: 2rem;">Завантаження...</div>
            <div x-show="!loading" x-html="content" class="prose"></div>
        </div>

        <div class="modal-footer">
            <button @click="showModal = false" class="btn-primary" style="padding: 0.5rem 2rem;">Зрозуміло</button>
        </div>
    </div>
</div>

<style>
    .text-modal {
        max-width: 800px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        padding: 0;
        overflow: hidden;
    }

    .text-modal .modal-title {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        margin: 0;
        text-align: left;
    }

    .text-modal .modal-close {
        top: 1.5rem;
        right: 1.5rem;
    }

    .modal-body {
        padding: 2rem;
        overflow-y: auto;
        flex-grow: 1;
    }

    .modal-footer {
        padding: 1rem 2rem;
        border-top: 1px solid #e5e7eb;
        text-align: right;
        background: #f9fafb;
    }

    /* Стилі для тексту всередині модалки */
    .prose h1 { display: none; } /* Ховаємо заголовок H1, бо він вже є в шапці модалки */
    .prose h2 { font-size: 1.25rem; margin-top: 1.5rem; margin-bottom: 0.75rem; color: var(--secondary-color); }
    .prose p { margin-bottom: 1rem; line-height: 1.6; color: #374151; }
    .prose ul { padding-left: 1.5rem; margin-bottom: 1rem; }
    .prose li { margin-bottom: 0.5rem; }
</style>