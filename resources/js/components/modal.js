export default options => ({
    show: options.show || false,
    id: options.id,

    init() {
        this.$watch('show', value => {
            if (value) {
                document.body.classList.add('overflow-y-hidden');
                setTimeout(() => { this.autofocus() }, 100);
                this.$dispatch('modal-shown', this.id);
            } else {
                document.body.classList.remove('overflow-y-hidden');
                this.$dispatch('modal-closed', this.id);
            }
        });
    },

    hideModal() {
        this.show = false;
    },

    focusables() {
        // All focusable element types...
        const selector = 'a, button, input, textearea, select, details, [tabindex]:not([tabindex="-1"])';

        return [...this.$root.querySelectorAll(selector)]
            // All non-disabled elements...
            .filter(el => ! el.hasAttribute('disabled'));
    },

    autofocus() {
        const selector = '[autofocus], [focus]';
        const focusables = [...this.$root.querySelectorAll(selector)]
            .filter(el => ! el.hasAttribute('disabled'));
        const first = focusables[0];

        if (first) {
            first.focus();
        }
    },

    firstFocusable() {
        return this.focusables()[0];
    },

    lastFocusable() {
        return this.focusables().slice(-1)[0];
    },

    nextFocusable() {
        return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable();
    },

    nextFocusableIndex() {
        return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1);
    },

    prevFocusable() {
        return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable();
    },

    prevFocusableIndex() {
        return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1;
    },
});
