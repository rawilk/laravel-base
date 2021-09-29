<div x-data="otp" class="grid {{ $gridCols() }} gap-4" x-on:focus-otp.window="reFocus">
    <template x-for="(input, index) in length" :key="index">
        <input
            type="tel"
            maxlength="1"
            data-otp="1"
            x-bind:id="`otp-{{ $name }}-${index}`"
            x-on:keydown="handleInput"
            x-on:paste="handlePaste"
            x-on:keydown.backspace.prevent="handleBackspace(index)"
            class="bg-gray-100 p-2 rounded-lg text-center border-none focus:outline-none focus:ring-0 focus:bg-gray-200 h-14"
        >
    </template>

    <input type="hidden" name="{{ $name }}" x-model="value">
</div>

@once
<script>
function otp() {
    return {
        length: {{ $length }},
        @if ($attributes->hasStartsWith('wire:model'))
            value: @entangle($attributes->wire('model')),
        @else
            value: '{{ $value }}',
        @endif

        init() {
            @if ($focus)
            this.$nextTick(() => {
                this.reFocus();
            });
            @endif
        },

        reFocus() {
            this.getInput(0).focus();
            this.getInput(0).select();
        },

        getInput(index) {
            return this.$root.querySelector(`#otp-{{ $name }}-${index}`);
        },

        handleInput(event) {
            const input = event.target;
            const charCode = event.which ? event.which : event.keyCode;

            if (event.key === 'Backspace') {
                return;
            }

            {{-- only allow numbers --}}
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();

                return;
            }

            setTimeout(() => {
                this.value = Array.from(Array(this.length), (element, index) => {
                    return this.getInput(index).value || '';
                }).join('');

                const hasMoreInputs = input.nextElementSibling
                    && input.nextElementSibling.getAttribute('data-otp') === '1';

                if (hasMoreInputs && input.value) {
                    input.nextElementSibling.focus();
                    input.nextElementSibling.select();
                } else if (! hasMoreInputs && input.value) {
                    this.$dispatch('otp-finish');
                }
            }, 50);
        },

        handlePaste(event) {
            const text = event.clipboardData.getData('text');
            this.value = text;

            const inputs = Array.from(Array(this.length));

            inputs.forEach((element, index) => {
                this.getInput(index).value = text[index] || '';
            });

            setTimeout(() => {
                try {
                    const length = text.length >= this.length ? this.length - 1 : text.length;

                    this.getInput(length).focus();
                    this.getInput(length).select();
                } catch (e) {}
            }, 50);
        },

        handleBackspace(index) {
            this.getInput(index).value = '';

            const previous = parseInt(index, 10) - 1;
            const input = this.getInput(previous);

            setTimeout(() => {
                input && input.focus();
            }, 50);
        },
    }
}
</script>
@endonce
