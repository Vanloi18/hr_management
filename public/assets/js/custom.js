/**
 * ============================================================
 * CUSTOM.JS - TÁI CẤU TRÚC HOÀN CHỈNH
 * ============================================================
 * Tất cả các module và chức năng được đóng gói trong DOMContentLoaded
 * Các hàm cần thiết được expose ra window object để có thể gọi từ HTML
 * ============================================================
 */

document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // 0. HÀM HỖ TRỢ CHUNG
    // ============================================================

    /**
     * Lấy CSRF Token từ meta tag
     * @returns {string} CSRF token
     */
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    /**
     * Khởi tạo Bootstrap Tooltips
     */
    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            // Hủy tooltip cũ nếu có
            const oldTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
            if (oldTooltip) {
                oldTooltip.dispose();
            }
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Khởi tạo tooltips ban đầu
    initTooltips();

    // ============================================================
    // 1. MODULE: XEM CHI TIẾT TIN TUYỂN DỤNG (MODAL)
    // ============================================================

    /**
     * Format text thành HTML có cấu trúc
     * @param {string} text - Text cần format
     * @returns {string} HTML đã được format
     */
    function formatText(text) {
        if (!text) {
            return '<p class="text-muted mb-0">Chưa có thông tin</p>';
        }

        // Chuyển đổi line breaks thành <br>
        text = text.replace(/\n/g, '<br>');

        // Nếu text có dạng list (bắt đầu bằng -, *, • hoặc số)
        if (text.match(/^[\s]*[-*•]\s/m) || text.match(/^\d+\.\s/m)) {
            const lines = text.split('<br>');
            let formatted = '<ul class="mb-0 ps-3">';
            lines.forEach(line => {
                line = line.trim();
                if (line) {
                    // Loại bỏ ký tự đầu dòng (-, *, •, số)
                    line = line.replace(/^[-*•]\s*/, '').replace(/^\d+\.\s*/, '');
                    formatted += '<li class="mb-1">' + line + '</li>';
                }
            });
            formatted += '</ul>';
            return formatted;
        }

        return '<p class="mb-0">' + text + '</p>';
    }

    /**
     * Hiển thị modal chi tiết tin tuyển dụng
     * @param {Object} position - Object chứa thông tin position
     */
    function showDetailModal(position) {
        // Kiểm tra các element tồn tại
        const elements = {
            id: document.getElementById('detail-id'),
            title: document.getElementById('detail-title'),
            company: document.getElementById('detail-company'),
            field: document.getElementById('detail-field'),
            salary: document.getElementById('detail-salary'),
            location: document.getElementById('detail-location'),
            deadline: document.getElementById('detail-deadline'),
            created: document.getElementById('detail-created'),
            description: document.getElementById('detail-description'),
            requirements: document.getElementById('detail-requirements'),
            benefits: document.getElementById('detail-benefits'),
            statusBadge: document.getElementById('detail-status-badge')
        };

        // Cập nhật thông tin cơ bản
        if (elements.id) elements.id.textContent = '#' + position.id;
        if (elements.title) elements.title.textContent = position.title || 'N/A';
        if (elements.company) elements.company.textContent = position.company_name || 'N/A';
        if (elements.field) elements.field.textContent = position.field_name || 'N/A';
        if (elements.salary) elements.salary.textContent = position.salary_range || 'Thỏa thuận';
        if (elements.location) elements.location.textContent = position.location || 'Toàn quốc';
        if (elements.deadline) elements.deadline.textContent = position.deadline || 'Không giới hạn';
        if (elements.created) elements.created.textContent = position.created_at || 'N/A';

        // Xử lý description, requirements, benefits
        if (elements.description) elements.description.innerHTML = formatText(position.description);
        if (elements.requirements) elements.requirements.innerHTML = formatText(position.requirements);
        if (elements.benefits) elements.benefits.innerHTML = formatText(position.benefits);

        // Xử lý trạng thái
        if (elements.statusBadge) {
            if (position.status === 'open') {
                elements.statusBadge.innerHTML = '<i class="bi bi-check-circle me-1"></i>Đang tuyển';
                elements.statusBadge.className = 'badge bg-success-subtle text-success px-3 py-2 rounded-pill border border-success border-opacity-10';
            } else {
                elements.statusBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i>Đã đóng';
                elements.statusBadge.className = 'badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill border border-secondary border-opacity-10';
            }
        }

        // Hiển thị modal
        const modalElement = document.getElementById('detailModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }

    // Expose hàm showDetailModal ra window object để có thể gọi từ HTML
    window.showDetailModal = showDetailModal;

    // ============================================================
    // 2. MODULE: XÓA USER (SỬ DỤNG SỰ KIỆN CLICK)
    // ============================================================

    document.body.addEventListener('click', async function (e) {
        // Tìm nút xóa (hoặc icon bên trong nó)
        const button = e.target.closest('.btn-delete-user');
        if (!button) return;

        e.preventDefault(); // Ngăn chặn hành vi mặc định

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
                // Xóa dòng với animation
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.transform = 'translateX(100%)';
                    setTimeout(() => row.remove(), 300);
                }
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
    // 3. MODULE: XÓA PHÒNG BAN / FORM AJAX DELETE
    // ============================================================

    document.body.addEventListener('submit', async function (e) {
        if (e.target.matches('.form-delete-ajax')) {
            e.preventDefault();
            const form = e.target;

            // Lấy câu thông báo xác nhận
            const message = form.getAttribute('data-confirm') ||
                form.getAttribute('onsubmit')?.replace("return confirm('", "").replace("');", "") ||
                'Bạn có chắc chắn muốn xóa mục này?';

            if (!confirm(message)) return;

            const url = form.action;
            const rowId = form.dataset.rowId || form.getAttribute('data-row-id');
            const formData = new FormData(form);
            const row = rowId ? document.getElementById(rowId) : null;

            // Hiệu ứng mờ dòng
            if (row) row.style.opacity = '0.5';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    if (row) {
                        // Xóa dòng với animation
                        row.style.transition = 'all 0.3s ease';
                        row.style.transform = 'translateX(100%)';
                        setTimeout(() => row.remove(), 300);
                    } else {
                        // Nếu không tìm thấy dòng để xóa thì load lại trang
                        window.location.reload();
                    }
                } else {
                    if (row) row.style.opacity = '1';
                    alert('Lỗi: ' + (data.message || 'Không thể xóa.'));
                }
            } catch (error) {
                if (row) row.style.opacity = '1';
                alert('Lỗi kết nối: ' + error.message);
            }
        }
    });

    // ============================================================
    // 4. MODULE: TẠO PHÒNG BAN (AJAX ADD DEPARTMENT)
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
            const originalButtonText = submitButton ? submitButton.textContent : 'Lưu';
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
            }

            try {
                const response = await fetch(formAddDept.action, {
                    method: 'POST',
                    body: new FormData(formAddDept),
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (!response.ok) {
                    // Xử lý lỗi validation
                    if (data.errors && data.errors.name && nameInput && nameError) {
                        nameInput.classList.add('is-invalid');
                        nameError.textContent = data.errors.name;
                    } else if (generalErrorDiv) {
                        generalErrorDiv.style.display = 'block';
                        generalErrorDiv.textContent = data.message || 'Đã xảy ra lỗi.';
                    }
                } else {
                    // Thành công
                    alert(data.message || 'Thao tác thành công!');
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                }
            } catch (error) {
                if (generalErrorDiv) {
                    generalErrorDiv.style.display = 'block';
                    generalErrorDiv.textContent = 'Lỗi kết nối: ' + error.message;
                }
            } finally {
                // Reset button state
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                }
            }
        });
    }

    // ============================================================
    // 5. MODULE: AJAX NAVIGATION / PJAX
    // ============================================================

    const mainContent = document.querySelector('main.content');

    // Chỉ chạy PJAX nếu có thẻ main.content
    if (mainContent) {

        /**
         * Load trang mới qua AJAX
         * @param {string} url - URL cần load
         * @param {string} title - Tiêu đề trang
         * @param {boolean} pushState - Có push state vào history không
         */
        async function loadPage(url, title, pushState = true) {
            mainContent.style.opacity = '0.5';
            mainContent.style.transition = 'opacity 0.2s ease';

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

                // Cập nhật title
                if (title) document.title = title;

                // Push state vào history
                if (pushState) {
                    history.pushState({ url: url, title: title }, title, url);
                }

                // Cập nhật active links
                updateActiveLinks(url);

                // Re-init các script cần thiết sau khi load AJAX
                initTooltips();

                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });

            } catch (error) {
                mainContent.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Lỗi tải trang:</strong> ${error.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                mainContent.style.opacity = '1';
            }
        }

        /**
         * Cập nhật trạng thái active của các link trong menu
         * @param {string} currentFullUrl - URL hiện tại
         */
        function updateActiveLinks(currentFullUrl) {
            const currentPath = new URL(currentFullUrl).pathname;

            // Reset tất cả active states
            document.querySelectorAll('a.ajax-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('a[data-bs-toggle="collapse"]').forEach(link => link.classList.remove('active'));

            // Tìm link phù hợp nhất
            let bestMatch = null;
            document.querySelectorAll('a.ajax-link').forEach(link => {
                if (!link.dataset.url) return;
                const linkPath = new URL(link.dataset.url).pathname;

                if (currentPath.startsWith(linkPath)) {
                    // Bỏ qua root path nếu không phải exact match
                    if (linkPath.length <= 1 && currentPath.length > 1) return;

                    // Chọn link có path dài nhất (match chính xác nhất)
                    if (!bestMatch || linkPath.length > new URL(bestMatch.dataset.url).pathname.length) {
                        bestMatch = link;
                    }
                }
            });

            // Active link phù hợp nhất
            if (bestMatch) {
                bestMatch.classList.add('active');

                // Active parent collapse nếu có
                const parentCollapse = bestMatch.closest('.collapse');
                if (parentCollapse) {
                    const triggerLink = document.querySelector(`a[href="#${parentCollapse.id}"]`);
                    if (triggerLink) triggerLink.classList.add('active');

                    // Mở collapse
                    const bsCollapse = new bootstrap.Collapse(parentCollapse, { toggle: false });
                    bsCollapse.show();
                }
            }
        }

        // Lắng nghe click vào link Menu
        document.body.addEventListener('click', function (e) {
            const link = e.target.closest('a.ajax-link');
            if (!link) return;

            e.preventDefault();
            const url = link.dataset.url;
            const title = link.dataset.title || document.title;

            // Không load lại nếu đang ở trang đó
            if (url === window.location.href) return;

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
    // 6. MODULE: THEME TOGGLE (DARK/LIGHT MODE)
    // ============================================================

    const toggleBtn = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const html = document.documentElement;

    /**
     * Áp dụng theme
     * @param {string} theme - 'dark' hoặc 'light'
     */
    function applyTheme(theme) {
        if (theme === 'dark') {
            html.setAttribute('data-theme', 'dark');
            if (themeIcon) {
                themeIcon.classList.remove('bi-moon-stars');
                themeIcon.classList.add('bi-sun-fill', 'text-warning');
            }
        } else {
            html.removeAttribute('data-theme');
            if (themeIcon) {
                themeIcon.classList.remove('bi-sun-fill', 'text-warning');
                themeIcon.classList.add('bi-moon-stars');
            }
        }
        localStorage.setItem('theme', theme);
    }

    // Kiểm tra LocalStorage khi tải trang
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        applyTheme(savedTheme);
    }

    // Sự kiện Click nút đổi theme
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const isDark = html.getAttribute('data-theme') === 'dark';
            applyTheme(isDark ? 'light' : 'dark');
        });
    }

    // ============================================================
    // 7. CÁC TIỆN ÍCH BỔ SUNG
    // ============================================================

    /**
     * Xử lý auto-dismiss cho alerts
     */
    const autoDismissAlerts = document.querySelectorAll('.alert[data-auto-dismiss]');
    autoDismissAlerts.forEach(alert => {
        const delay = parseInt(alert.getAttribute('data-auto-dismiss')) || 5000;
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, delay);
    });

    /**
     * Confirm trước khi submit form có class confirm-submit
     */
    document.body.addEventListener('submit', function (e) {
        const form = e.target;
        if (form.classList.contains('confirm-submit')) {
            const message = form.getAttribute('data-confirm-message') || 'Bạn có chắc chắn muốn thực hiện hành động này?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        }
    });

    /**
     * Auto focus vào input đầu tiên có class auto-focus
     */
    const autoFocusInput = document.querySelector('.auto-focus');
    if (autoFocusInput) {
        autoFocusInput.focus();
    }

    // ============================================================
    // LOG KHỞI TẠO THÀNH CÔNG
    // ============================================================
    console.log('✅ Custom.js đã được khởi tạo thành công');
    console.log('📦 Các module đã load:', [
        'Tooltips',
        'Position Detail Modal',
        'AJAX Delete User',
        'AJAX Delete Form',
        'AJAX Add Department',
        'PJAX Navigation',
        'Theme Toggle',
        'Utilities'
    ]);

});