/**
 * Button press ripple: the ink expands from the exact pointer position.
 */
document.addEventListener('pointerdown', (event) => {
    const button = event.target.closest('.btn-primary, .btn-secondary');

    if (!button || button.getAttribute('aria-busy') === 'true') {
        return;
    }

    const rect = button.getBoundingClientRect();
    button.style.setProperty('--rx', `${event.clientX - rect.left}px`);
    button.style.setProperty('--ry', `${event.clientY - rect.top}px`);

    button.classList.remove('is-rippling');
    void button.offsetWidth;
    button.classList.add('is-rippling');
});

document.addEventListener('animationend', (event) => {
    if (event.animationName === 'ink') {
        event.target.classList.remove('is-rippling');
    }
});

/**
 * Submit feedback: show a spinner on the submit button and block double submits.
 */
document.addEventListener('submit', (event) => {
    const form = event.target;

    if (form.hasAttribute('data-feed-filter')) {
        return;
    }

    if (form.dataset.submitting === 'true') {
        event.preventDefault();

        return;
    }

    const button = form.querySelector('button[type="submit"]');

    if (!button) {
        return;
    }

    form.dataset.submitting = 'true';
    button.setAttribute('aria-busy', 'true');
});

window.addEventListener('pageshow', () => {
    document.querySelectorAll('form[data-submitting]').forEach((form) => {
        delete form.dataset.submitting;
        form.querySelector('button[type="submit"]')?.removeAttribute('aria-busy');
    });
});

/**
 * Landing page one-page Login / Register tab switcher.
 */
const authTabsContainer = document.querySelector('[data-auth-tabs]');

if (authTabsContainer) {
    const tabs = authTabsContainer.querySelectorAll('[data-auth-tab]');
    const panels = authTabsContainer.querySelectorAll('[data-auth-panel]');

    const activateTab = (targetTab, shouldFocus = false) => {
        tabs.forEach((tab) => {
            const isSelected = tab.dataset.authTab === targetTab;
            tab.setAttribute('aria-selected', isSelected ? 'true' : 'false');
            tab.classList.toggle('bg-white', isSelected);
            tab.classList.toggle('text-navy-900', isSelected);
            tab.classList.toggle('shadow-sm', isSelected);
            tab.classList.toggle('text-slate-600', !isSelected);
        });

        panels.forEach((panel) => {
            const isActive = panel.dataset.authPanel === targetTab;
            panel.hidden = !isActive;

            if (isActive && shouldFocus) {
                panel.querySelector('input:not([type="hidden"])')?.focus();
            }
        });
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => activateTab(tab.dataset.authTab, true));
    });

    document.querySelectorAll('[data-auth-switch]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            activateTab(trigger.dataset.authSwitch, true);
            authTabsContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });
}

/**
 * Homepage open-team feed: shimmer skeleton on initial load and filter changes.
 */
const feed = document.querySelector('[data-feed-url]');

if (feed) {
    const skeleton = feed.querySelector('[data-feed-skeleton]');
    const target = feed.querySelector('[data-feed-target]');
    const countLabel = document.querySelector('[data-feed-count]');
    const filterForm = document.querySelector('[data-feed-filter]');

    const updateCount = () => {
        if (!countLabel) {
            return;
        }
        const total = target.querySelectorAll('[data-post-card]').length;
        countLabel.textContent = `${total} postingan aktif`;
    };

    const showError = () => {
        target.innerHTML = `
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center">
                <p class="font-semibold text-navy-900">Postingan belum bisa dimuat</p>
                <p class="mt-1 text-sm text-slate-600">Periksa koneksi internet kamu, lalu coba lagi.</p>
                <button type="button" class="btn-secondary mt-5" data-feed-retry>Coba lagi</button>
            </div>`;
        target.querySelector('[data-feed-retry]')?.addEventListener('click', () => loadFeed());
    };

    async function loadFeed(params = '') {
        feed.setAttribute('aria-busy', 'true');
        skeleton.hidden = false;
        target.hidden = true;

        const baseUrl = feed.dataset.feedUrl;
        const url = params ? `${baseUrl}?${params}` : baseUrl;

        try {
            const response = await fetch(url, {
                headers: { Accept: 'text/html', 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) {
                throw new Error(`Feed request failed with ${response.status}`);
            }

            target.innerHTML = await response.text();
            updateCount();
        } catch {
            showError();
        } finally {
            skeleton.hidden = true;
            target.hidden = false;
            feed.setAttribute('aria-busy', 'false');
        }
    }

    if (filterForm) {
        const categorySelect = filterForm.querySelector('#filter-category');
        const categoryPills = filterForm.querySelectorAll('[data-category-pill]');

        const syncPills = (activeValue) => {
            categoryPills.forEach((pill) => {
                const isSelected = pill.dataset.categoryPill === String(activeValue ?? '');
                pill.classList.toggle('bg-navy-900', isSelected);
                pill.classList.toggle('text-white', isSelected);
                pill.classList.toggle('bg-slate-100', !isSelected);
                pill.classList.toggle('text-slate-700', !isSelected);
            });
        };

        filterForm.addEventListener('submit', (event) => {
            event.preventDefault();
            syncPills(categorySelect?.value ?? '');
            const params = new URLSearchParams(new FormData(filterForm)).toString();
            loadFeed(params);
        });

        categoryPills.forEach((pill) => {
            pill.addEventListener('click', () => {
                if (categorySelect) {
                    categorySelect.value = pill.dataset.categoryPill;
                }
                syncPills(pill.dataset.categoryPill);
                const params = new URLSearchParams(new FormData(filterForm)).toString();
                loadFeed(params);
            });
        });

        filterForm.querySelector('[data-feed-reset]')?.addEventListener('click', () => {
            setTimeout(() => {
                syncPills('');
                loadFeed();
            }, 0);
        });
    }

    if (target.children.length > 0) {
        setTimeout(() => {
            updateCount();
            skeleton.hidden = true;
            target.hidden = false;
            feed.setAttribute('aria-busy', 'false');
        }, 180);
    } else {
        loadFeed();
    }
}

/**
 * Post detail modal on the homepage.
 */
const postModal = document.querySelector('[data-post-modal]');

if (postModal) {
    const closeModal = () => {
        postModal.hidden = true;
    };

    document.addEventListener('click', (event) => {
        const card = event.target.closest('[data-post-card]');

        if (card && !event.target.closest('a')) {
            postModal.querySelector('[data-modal-category]').textContent = card.dataset.postCategory || 'Umum';
            postModal.querySelector('[data-modal-title]').textContent = card.dataset.postTitle || '';
            postModal.querySelector('[data-modal-meta]').textContent =
                `Diposting oleh ${card.dataset.postAuthor} (${card.dataset.postUniversity}) · ${card.dataset.postTime}`;
            postModal.querySelector('[data-modal-description]').textContent = card.dataset.postDescription || '';
            postModal.querySelector('[data-modal-criteria]').textContent = card.dataset.postCriteria || '';
            postModal.querySelector('[data-modal-report]').setAttribute('href', card.dataset.postReportUrl || '#');
            postModal.hidden = false;

            return;
        }

        if (event.target.closest('[data-modal-close]') || event.target === postModal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !postModal.hidden) {
            closeModal();
        }
    });
}
