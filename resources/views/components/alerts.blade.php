@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', () => {

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        width: '380px',
        background: '#ffffff',
        color: '#111827',
        customClass: {
            popup: 'fleet-toast',
            title: 'fleet-toast-title',
            htmlContainer: 'fleet-toast-text'
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
    });

});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', () => {

    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: "{{ session('error') }}",
        confirmButtonText: 'OK',
        confirmButtonColor: '#2563eb',
        backdrop: 'rgba(15,23,42,.45)',
        customClass: {
            popup: 'fleet-popup',
            title: 'fleet-title',
            htmlContainer: 'fleet-text',
            confirmButton: 'fleet-btn'
        }
    });

});
</script>
@endif

<style>

.fleet-toast{
    border-radius:16px !important;
    padding:16px 18px !important;
    box-shadow:0 20px 60px rgba(15,23,42,.15) !important;
    border:none !important;
}

.fleet-toast-title{
    font-size:15px !important;
    font-weight:600 !important;
}

.fleet-toast-text{
    font-size:13px !important;
    color:#6b7280 !important;
}

.swal2-toast .swal2-success{
    border-color:#22c55e !important;
}

.swal2-timer-progress-bar{
    background:#22c55e !important;
}

.fleet-popup{
    border-radius:22px !important;
    padding:32px !important;
    width:430px !important;
    box-shadow:0 25px 70px rgba(15,23,42,.18) !important;
}

.fleet-title{
    font-size:28px !important;
    font-weight:700 !important;
}

.fleet-text{
    font-size:15px !important;
    color:#6b7280 !important;
    line-height:1.7;
}

.fleet-btn{
    border-radius:12px !important;
    padding:10px 28px !important;
    font-weight:600 !important;
}

</style>
