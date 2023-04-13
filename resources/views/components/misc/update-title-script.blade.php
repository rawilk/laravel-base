@props([
    'action',
    'separator' => ' | ',
    'selector' => '.page-title',
])

@once
<script nonce="{{ cspNonce() }}">
    document.addEventListener('livewire:load', function() {
        @this.on('{{ $action }}', () => {
            @if ($slot->isEmpty())
                const newTitle = `Edit ${@this.state['name']}`;
            @else
                {{ $slot }}
            @endif

            updatePageTitle({ newTitle, separator: '{{ $separator }}', selector: '{{ $selector }}' });
        });
    });
</script>
@endonce
