@php
$toastStatusMessage = session('status');
if ($toastStatusMessage === 'profile-updated') {
$toastStatusMessage = 'Hồ sơ đã được cập nhật.';
} elseif ($toastStatusMessage === 'password-updated') {
$toastStatusMessage = 'Mật khẩu đã được cập nhật.';
} elseif ($toastStatusMessage === 'verification-link-sent') {
$toastStatusMessage = 'Đã gửi lại email xác minh.';
}

$toastItems = [];
if (session('success')) {
$toastItems[] = ['icon' => 'success', 'title' => (string) session('success')];
}
if (session('error')) {
$toastItems[] = ['icon' => 'error', 'title' => (string) session('error')];
}
if (session('info')) {
$toastItems[] = ['icon' => 'info', 'title' => (string) session('info')];
}
if (session('warning')) {
$toastItems[] = ['icon' => 'warning', 'title' => (string) session('warning')];
}
if (!empty($toastStatusMessage)) {
$toastItems[] = ['icon' => 'success', 'title' => (string) $toastStatusMessage];
}
if ($errors->any()) {
$toastItems[] = ['icon' => 'error', 'title' => (string) $errors->first()];
}
@endphp

@if (!empty($toastItems))
<div id="global-toast-data" data-items='@json($toastItems)' style="display:none;"></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toastDataEl = document.getElementById('global-toast-data');
        let toastItems = [];

        if (toastDataEl) {
            try {
                toastItems = JSON.parse(toastDataEl.dataset.items || '[]');
            } catch (e) {
                toastItems = [];
            }
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
        });

        toastItems.forEach((item) => Toast.fire(item));
    });
</script>
@endif