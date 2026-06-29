/**
 * Juney Villa - Admin Dashboard JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebar = document.getElementById('adminSidebar');
    const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');

    if (openBtn) openBtn.addEventListener('click', () => sidebar.classList.add('show'));
    if (closeBtn) closeBtn.addEventListener('click', () => sidebar.classList.remove('show'));

    // Theme Toggle
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        const currentTheme = localStorage.getItem('admin_theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', currentTheme);
        themeToggle.innerHTML = currentTheme === 'dark' ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon"></i>';

        themeToggle.addEventListener('click', () => {
            const theme = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('admin_theme', theme);
            themeToggle.innerHTML = theme === 'dark' ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon"></i>';
        });
    }

    // Charts - Revenue
    const revenueChart = document.getElementById('revenueChart');
    if (revenueChart && typeof Chart !== 'undefined') {
        const monthlyData = JSON.parse(revenueChart.dataset.revenue || '[]');
        new Chart(revenueChart, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: monthlyData,
                    borderColor: '#C8A45C',
                    backgroundColor: 'rgba(200,164,92,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#C8A45C'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Occupancy Chart
    const occupancyChart = document.getElementById('occupancyChart');
    if (occupancyChart && typeof Chart !== 'undefined') {
        const occupancyData = JSON.parse(occupancyChart.dataset.occupancy || '{}');
        new Chart(occupancyChart, {
            type: 'doughnut',
            data: {
                labels: occupancyData.labels || [],
                datasets: [{
                    data: occupancyData.values || [],
                    backgroundColor: ['#C8A45C', '#4CAF50', '#2196F3', '#FF9800', '#9C27B0'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // AJAX with CSRF
    window.adminFetch = function(url, options = {}) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        options.headers = { ...options.headers, 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' };
        return fetch(url, options);
    };

    // Confirm Delete
    document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form') || document.getElementById(this.dataset.form);
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed && form) form.submit();
            });
        });
    });

    // Toast Notifications
    window.showToast = function(message, type = 'success') {
        Swal.fire({
            toast: true, position: 'top-end', icon: type,
            title: message, showConfirmButton: false, timer: 3000, timerProgressBar: true
        });
    };

    // DataTable-like search
    const tableSearch = document.getElementById('tableSearch');
    if (tableSearch) {
        tableSearch.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            document.querySelectorAll('.table-admin tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
            });
        });
    }
});
