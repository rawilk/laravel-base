@props([
    'action',
    'stack' => 'js',
    'separator' => ' | ',
    'selector' => '.page-title',
])

@push($stack)
<script>
    @this.on('{{ $action }}', () => {
        @if ($slot->isEmpty())
            const newTitle = `Edit ${@this.state['name']}`;
        @else
            {{ $slot }}
        @endif

        updatePageTitle({ newTitle, separator: '{{ $separator }}', selector: '{{ $selector }}' });
    });
</script>
@endpush
