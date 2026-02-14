@if (session('success'))
    <div id="success-alert"
         class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded transition-opacity duration-500">
        <div class="flex items-center">
            <span class="text-xl mr-2">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    </div>

    <script>
        setTimeout(function () {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000); // Tự động ẩn sau 5 giây
    </script>
@endif
