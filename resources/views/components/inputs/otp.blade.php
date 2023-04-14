<div x-data="otp" class="grid {{ $gridCols() }} gap-4" x-on:focus-otp.window="reFocus"
     x-on:otp-reset.window="resetValue">
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
    <script nonce="{{ csp_nonce() }}">
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
            const ctrlDown = event.ctrlKey || event.metaKey; // For paste support
            const vKey = 86;

            if (ctrlDown && charCode === vKey) {
                return;
            }

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

            this._updateInputValues(text);

            setTimeout(() => {
                try {
                    if (text.length >= this.length) {
                        return this.$dispatch('otp-finish');
                    }

                    const length = text.length >= this.length ? this.length - 1 : text.length;

                    this.getInput(length).focus();
                    this.getInput(length).select();
                } catch (e) {
                }
            }, 50);
        },

        resetValue() {
            this.value = '';
            this._updateInputValues(this.value);
        },

        handleBackspace(index) {
            this.getInput(index).value = '';

            const previous = parseInt(index, 10) - 1;
            const input = this.getInput(previous);

            setTimeout(() => {
                input && input.focus();
            }, 50);
        },

        _updateInputValues(value) {
            const inputs = Array.from(Array(this.length));

            inputs.forEach((element, index) => {
                this.getInput(index).value = value[index] || '';
            });
        },
    }
}

window.addEventListener('alpine:initializing', function () {
    window.Alpine.data('otp', otp);
});
</script>
@endonce
