@props(['type'])
<div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
    <h4 class="alert-heading">✓ {{ $type }}!</h4>
    {{ $slot }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>