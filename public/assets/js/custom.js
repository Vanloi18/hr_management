document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // 0. HÀM HỖ TRỢ CHUNG
    // ============================================================
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // ============================================================
    // 1. MODULE: XÓA USER (MỚI - SỬ DỤNG SỰ KIỆN CLICK)
    // ============================================================
    document.body.addEventListener('click', async function (e) {
        // Tìm nút xóa (hoặc icon bên trong nó)
        const button = e.target.closest('.btn-delete-user');
        if (!button) return;

        e.preventDefault(); // Ngăn chặn hành vi mặc định (quan trọng)

        // Xác nhận
        if (!confirm('CẢNH BÁO: Hành động này không thể hoàn tác.\nBạn có chắc chắn muốn xóa tài khoản này không?')) {
            return;
        }

        const id = button.getAttribute('data-id');
        const url = button.getAttribute('data-url');
        const row = document.getElementById('row-user-' + id);
        const csrfToken = getCsrfToken();

        if (!url) {
            alert('Lỗi: Thiếu đường dẫn xử lý (data-url)!');
            return;
        }

        // Hiệu ứng mờ dòng
        if (row) row.style.opacity = '0.5';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'id=' + encodeURIComponent(id) + '&csrf_token=' + encodeURIComponent(csrfToken)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                if (row) row.remove();
                // alert('Đã xóa thành công!');
            } else {
                if (row) row.style.opacity = '1';
                alert('Lỗi: ' + (data.message || 'Không thể xóa.'));
            }
        } catch (error) {
            if (row) row.style.opacity = '1';
            alert('Lỗi kết nối: ' + error.message);
        }
    });

    // ============================================================
    // 2. MODULE: XÓA PHÒNG BAN (GIỮ NGUYÊN CODE CŨ - SỰ KIỆN SUBMIT)
    // ============================================================
    document.body.addEventListener('submit', async function (e) {
        if (e.target.matches('.form-delete-ajax')) {
            e.preventDefault();
            const form = e.target;
            // Lấy câu thông báo
            const message = form.getAttribute('data-confirm') ||
                form.getAttribute('onsubmit')?.replace("return confirm('", "").replace("');", "") ||
                'Bạn có chắc chắn muốn xóa mục này?';

            if (!confirm(message)) return;

            const url = form.action;
            const rowId = form.dataset.rowId || form.getAttribute('data-row-id');
            const formData = new FormData(form);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    if (rowId && document.getElementById(rowId)) {
                        document.getElementById(rowId).remove();
                    } else {
                        // Nếu không tìm thấy dòng để xóa thì load lại trang
                        window.location.reload();
                    }
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể xóa.'));
                }
            } catch (error) {
                alert('Lỗi kết nối: ' + error.message);
            }
        }
    });

    // ============================================================
    // 3. MODULE: TẠO PHÒNG BAN (GIỮ NGUYÊN CODE CŨ)
    // ============================================================
    const formAddDept = document.getElementById('form-add-department');
    if (formAddDept) {
        const submitButton = document.getElementById('btn-submit-ajax');
        const generalErrorDiv = document.getElementById('ajax-general-error');
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('error-name');

        formAddDept.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Reset trạng thái lỗi
            if (generalErrorDiv) {
                generalErrorDiv.style.display = 'none';
                generalErrorDiv.textContent = '';
            }
            if (nameInput) nameInput.classList.remove('is-invalid');
            if (nameError) nameError.textContent = '';

            // Loading state
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Đang xử lý...';
            }

            try {
                const response = await fetch(formAddDept.action, {
                    method: 'POST',
                    body: new FormData(formAddDept),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (!response.ok) {
                    if (data.errors && data.errors.name && nameInput && nameError) {
                        nameInput.classList.add('is-invalid');
                        nameError.textContent = data.errors.name;
                    } else if (generalErrorDiv) {
                        generalErrorDiv.style.display = 'block';
                        generalErrorDiv.textContent = data.message || 'Đã xảy ra lỗi.';
                    }
                } else {
                    alert(data.message);
                    if (data.redirect_url) window.location.href = data.redirect_url;
                }
            } catch (error) {
                if (generalErrorDiv) {
                    generalErrorDiv.style.display = 'block';
                    generalErrorDiv.textContent = 'Lỗi kết nối: ' + error.message;
                }
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu';
                }
            }
        });
    }

    // ============================================================
    // 4. MODULE: AJAX NAVIGATION / PJAX (GIỮ NGUYÊN CODE CŨ)
    // ============================================================
    const mainContent = document.querySelector('main.content');

    // Chỉ chạy PJAX nếu có thẻ main.content
    if (mainContent) {
        async function loadPage(url, title, pushState = true) {
            mainContent.style.opacity = '0.5';

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (!response.ok) throw new Error('Lỗi Server: ' + response.statusText);

                const newHtml = await response.text();
                mainContent.innerHTML = newHtml;
                mainContent.style.opacity = '1';

                if (title) document.title = title;

                if (pushState) {
                    history.pushState({ url: url, title: title }, title, url);
                }

                updateActiveLinks(url);

                // [Quan trọng] Re-init các script cần thiết sau khi load AJAX (nếu có)
                // Ví dụ: Tooltip bootstrap
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

            } catch (error) {
                mainContent.innerHTML = `<div class="alert alert-danger">Lỗi tải trang: ${error.message}</div>`;
                mainContent.style.opacity = '1';
            }
        }

        function updateActiveLinks(currentFullUrl) {
            const currentPath = new URL(currentFullUrl).pathname;

            document.querySelectorAll('a.ajax-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('a[data-bs-toggle="collapse"]').forEach(link => link.classList.remove('active'));

            let bestMatch = null;
            document.querySelectorAll('a.ajax-link').forEach(link => {
                if (!link.dataset.url) return;
                const linkPath = new URL(link.dataset.url).pathname;
                if (currentPath.startsWith(linkPath)) {
                    if (linkPath.length <= 1 && currentPath.length > 1) return;
                    if (!bestMatch || linkPath.length > new URL(bestMatch.dataset.url).pathname.length) {
                        bestMatch = link;
                    }
                }
            });

            if (bestMatch) {
                bestMatch.classList.add('active');
                const parentCollapse = bestMatch.closest('.collapse');
                if (parentCollapse) {
                    const triggerLink = document.querySelector(`a[href="#${parentCollapse.id}"]`);
                    if (triggerLink) triggerLink.classList.add('active');
                }
            }
        }

        // Lắng nghe click vào link Menu
        document.body.addEventListener('click', function (e) {
            const link = e.target.closest('a.ajax-link');
            if (!link) return;

            e.preventDefault();
            const url = link.dataset.url;
            const title = link.dataset.title;
            loadPage(url, title, true);
        });

        // Lắng nghe nút Back/Forward trình duyệt
        window.addEventListener('popstate', function (e) {
            if (e.state && e.state.url) {
                loadPage(e.state.url, e.state.title, false);
            } else {
                window.location.reload(); // Fallback an toàn
            }
        });

        // Lưu state ban đầu
        const currentTitle = document.title;
        const currentUrl = window.location.href;
        history.replaceState({ url: currentUrl, title: currentTitle }, currentTitle, currentUrl);
        updateActiveLinks(window.location.href);
    }

    // ============================================================
    // 5. MODULE: THEME TOGGLE (GIỮ NGUYÊN CODE CŨ)
    // ============================================================
    const toggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const html = document.documentElement;

    // Kiểm tra LocalStorage khi tải trang
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        html.setAttribute('data-theme', 'dark');
        if (themeIcon) {
            themeIcon.classList.replace('bi-moon-stars', 'bi-sun-fill');
            themeIcon.classList.add('text-warning');
        }
    }

    // Sự kiện Click nút đổi theme
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const isDark = html.getAttribute('data-theme') === 'dark';

            if (isDark) {
                html.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                if (themeIcon) {
                    themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars');
                    themeIcon.classList.remove('text-warning');
                }
            } else {
                html.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                if (themeIcon) {
                    themeIcon.classList.replace('bi-moon-stars', 'bi-sun-fill');
                    themeIcon.classList.add('text-warning');
                }
            }
        });
    }
});