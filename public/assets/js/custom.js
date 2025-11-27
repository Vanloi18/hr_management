document.addEventListener('DOMContentLoaded', function () {

    // MODULE 1: AJAX DELETE
    document.body.addEventListener('submit', async function (e) {
        if (e.target.matches('.form-delete-ajax')) {
            e.preventDefault();
            const form = e.target;
            const message = form.getAttribute('onsubmit')?.replace("return confirm('", "").replace("');", "") || 'Bạn có chắc chắn muốn xóa mục này?';

            if (!confirm(message)) return;

            const url = form.action;
            const rowId = form.dataset.rowId;
            const formData = new FormData(form);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    document.getElementById(rowId).remove();
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể xóa.'));
                }
            } catch (error) {
                alert('Lỗi kết nối: ' + error.message);
            }
        }
    });

    // MODULE 2: AJAX CREATE DEPARTMENT
    const formAddDept = document.getElementById('form-add-department');
    if (formAddDept) {
        const submitButton = document.getElementById('btn-submit-ajax');
        const generalErrorDiv = document.getElementById('ajax-general-error');
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('error-name');

        formAddDept.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Reset errors
            generalErrorDiv.style.display = 'none';
            generalErrorDiv.textContent = '';
            nameInput.classList.remove('is-invalid');
            nameError.textContent = '';

            // Loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Đang xử lý...';

            try {
                const response = await fetch(formAddDept.action, {
                    method: 'POST',
                    body: new FormData(formAddDept),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (!response.ok) {
                    if (data.errors && data.errors.name) {
                        nameInput.classList.add('is-invalid');
                        nameError.textContent = data.errors.name;
                    } else {
                        generalErrorDiv.style.display = 'block';
                        generalErrorDiv.textContent = data.message || 'Đã xảy ra lỗi.';
                    }
                } else {
                    alert(data.message);
                    window.location.href = data.redirect_url;
                }
            } catch (error) {
                generalErrorDiv.style.display = 'block';
                generalErrorDiv.textContent = 'Lỗi kết nối: ' + error.message;
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Lưu';
            }
        });
    }

    // MODULE 3: AJAX NAVIGATION (PJAX)
    const mainContent = document.querySelector('main.content');

    // Load page with AJAX
    async function loadPage(url, title, pushState = true) {
        mainContent.style.opacity = '0.5';

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!response.ok) {
                throw new Error('Lỗi Server: ' + response.statusText);
            }

            const newHtml = await response.text();
            mainContent.innerHTML = newHtml;
            mainContent.style.opacity = '1';

            document.title = title;

            if (pushState) {
                history.pushState({ url: url, title: title }, title, url);
            }

            updateActiveLinks(url);
        } catch (error) {
            mainContent.innerHTML = `<div class="alert alert-danger">Lỗi tải trang: ${error.message}</div>`;
            mainContent.style.opacity = '1';
        }
    }

    // Update active links and parent menu
    function updateActiveLinks(currentFullUrl) {
        const currentPath = new URL(currentFullUrl).pathname;

        // Remove all active classes
        document.querySelectorAll('a.ajax-link').forEach(link => {
            link.classList.remove('active');
        });
        document.querySelectorAll('a[data-bs-toggle="collapse"]').forEach(link => {
            link.classList.remove('active');
        });

        let bestMatch = null;

        document.querySelectorAll('a.ajax-link').forEach(link => {
            const linkPath = new URL(link.dataset.url).pathname;

            if (currentPath.startsWith(linkPath)) {
                // Skip home "/" if current path is not exactly "/"
                if (linkPath.length <= 1 && currentPath.length > 1) {
                    return;
                }

                // Find longest matching path
                if (!bestMatch || linkPath.length > new URL(bestMatch.dataset.url).pathname.length) {
                    bestMatch = link;
                }
            }
        });

        if (bestMatch) {
            bestMatch.classList.add('active');

            // Highlight parent menu if exists
            const parentCollapse = bestMatch.closest('.collapse');
            if (parentCollapse) {
                const triggerLink = document.querySelector(`a[href="#${parentCollapse.id}"]`);
                if (triggerLink) {
                    triggerLink.classList.add('active');
                }
            }
        }
    }

    // Listen to link clicks
    document.body.addEventListener('click', function (e) {
        const link = e.target.closest('a.ajax-link');
        if (!link) return;

        e.preventDefault();
        const url = link.dataset.url;
        const title = link.dataset.title;

        loadPage(url, title, true);
    });

    // Listen to browser back/forward
    window.addEventListener('popstate', function (e) {
        if (e.state && e.state.url) {
            loadPage(e.state.url, e.state.title, false);
        } else {
            const firstLink = document.querySelector('a.ajax-link');
            if (firstLink) {
                loadPage(firstLink.dataset.url, firstLink.dataset.title, false);
            }
        }
    });

    // Save initial state
    const currentTitle = document.title;
    const currentUrl = window.location.href;
    history.replaceState({ url: currentUrl, title: currentTitle }, currentTitle, currentUrl);

    // Activate current page on load
    updateActiveLinks(window.location.href);

});