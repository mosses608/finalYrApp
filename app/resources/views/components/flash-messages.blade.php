@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show p-2" role="alert">
        <span class="text-success me-1"><i class="fas fa-check"></i></span>
        <small>{{ session('success') }}</small>
    </div>
@elseif(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show p-1" role="alert">
        <span class="text-danger me-1"><i class="fas fa-exclamation-triangle"></i></span>
        <small>{{ session('error') }}</small>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            document.querySelector('.alert').style.display = 'none';
        }, 5000);
    });
</script>
