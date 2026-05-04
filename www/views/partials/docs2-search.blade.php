<div class="search-container {{ $class ?? '' }}">
    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
    </svg>
    <input type="text" id="searchInput" onkeyup="liveSearch()" class="search-input" placeholder="Пошук...">

    <!-- Результати пошуку -->
    <div id="searchResults" class="search-results"></div>
</div>

<style>
    .search-container {
        position: relative;
        width: 100%;
    }
    .search-input {
        width: 100%;
        padding: 0.75rem 1rem;
        padding-left: 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        outline: none;
        background: #fff;
    }
    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        width: 18px;
        height: 18px;
    }

    /* Search Results Dropdown */
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        margin-top: 0.5rem;
        z-index: 100;
        max-height: 300px;
        overflow-y: auto;
        display: none;
    }

    .search-result-item {
        display: block;
        padding: 0.75rem 1rem;
        text-decoration: none;
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.2s;
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .search-result-item:hover {
        background-color: #f9fafb;
    }
    .result-title {
        font-weight: 600;
        color: var(--secondary-color);
        font-size: 0.9rem;
        margin-bottom: 0.1rem;
    }
    .result-snippet {
        font-size: 0.8rem;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<script>
    let debounceTimer;

    function liveSearch() {
        clearTimeout(debounceTimer);
        let query = document.getElementById('searchInput').value;
        let resultsContainer = document.getElementById('searchResults');

        if (query.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch('/api/docs2/search?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(item => {
                            let link = document.createElement('a');
                            link.href = '/docs2/' + item.slug + '?highlight=' + encodeURIComponent(query);
                            link.className = 'search-result-item';
                            link.innerHTML = `
                                <div class="result-title">${item.title}</div>
                                <div class="result-snippet">${item.snippet}</div>
                            `;
                            resultsContainer.appendChild(link);
                        });
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div class="search-result-item" style="color: #6b7280;">Нічого не знайдено</div>';
                        resultsContainer.style.display = 'block';
                    }
                });
        }, 300);
    }

    document.addEventListener('click', function(event) {
        let container = document.querySelector('.search-container');
        if (!container.contains(event.target)) {
            document.getElementById('searchResults').style.display = 'none';
        }
    });
</script>