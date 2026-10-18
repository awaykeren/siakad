<!-- resources/views/components/alert.blade.php -->
<div class="alert alert-{{ $type ?? 'info' }}">
    {{ $slot }}
</div>