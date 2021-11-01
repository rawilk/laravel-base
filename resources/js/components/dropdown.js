let createPopper;

const FOCUSABLE_ELEMENTS = '.dropdown-item:not([disabled]), [role="menuitem"]:not([disabled])';

const focusElement = (el, menu) => {
    try {
        const offsetTop = el.offsetTop;
        menu.scrollTop = offsetTop || 0;

        el.focus({ preventScroll: true });
    } catch (e) {}
};

export default options => ({
    open: false,
    fixed: options.fixed || false,
    disabled: options.disabled || false,
    placement: options.placement || 'bottom-start',
    offset: options.offset,
    focusedIndex: -1,
    focusableElements: null,
    // We need to store a reference to our $root element, as sometimes in Livewire components
    // it will can be undefined on a re-render. (Possible bug?)
    _root: null,
    _popper: null,

    get trigger () {
        if (! this.$refs.trigger) {
            return this._root.querySelector('[x-ref="trigger"]');
        }

        return this.$refs.trigger;
    },

    get menu () {
        if (! this.$refs.menu) {
            return this._root.querySelector('[x-ref="menu"]');
        }

        return this.$refs.menu;
    },

    init() {
        this._root = this.$root;

        createPopper = window.Popper ? window.Popper.createPopper : window.createPopper;

        if (typeof createPopper !== 'function') {
            throw new TypeError(`<x-dropdown> requires Popper (https://popper.js.org)`);
        }
    },

    closeMenu(refocusTrigger = true) {
        if (! this.open) {
            return;
        }

        this.open = false;
        this.focusedIndex = -1;
        this.focusableElements = null;

        if (this._popper) {
            this._popper.destroy();
            this._popper = null;
        }

        if (refocusTrigger) {
            this.trigger && this.trigger.focus();
        }
    },

    openMenu() {
        if (this.disabled) {
            return;
        }

        this._popper = createPopper(this.trigger, this.menu, {
            placement: this.placement,
            strategy: this.fixed ? 'fixed' : 'absolute',
            modifiers: [
                {
                    name: 'offset',
                    options: {
                        offset: [0, this.offset],
                    },
                },
                {
                    name: 'preventOverflow',
                    options: {
                        boundariesElement: this._root,
                    },
                },
            ],
        });

        this.open = true;
    },

    toggleMenu() {
        if (this.open) {
            return this.closeMenu();
        }

        this.openMenu();
    },

    getFocusableElements() {
        if (this.focusableElements !== null) {
            return this.focusableElements;
        }

        return this.focusableElements = this.menu.querySelectorAll(FOCUSABLE_ELEMENTS);
    },

    /*
     * Event handlers...
     */

    focusNext() {
        if (this.disabled) {
            return;
        }

        if (! this.open) {
            this.openMenu();

            return this.$nextTick(() => { this.focusNext() });
        }

        const elements = this.getFocusableElements();

        if (! elements.length) {
            this.focusedIndex = -1;

            return;
        }

        this.focusedIndex++;
        if (this.focusedIndex + 1 > elements.length) {
            this.focusedIndex = 0;
        }

        focusElement(elements[this.focusedIndex], this.menu);
    },

    focusPrevious() {
        if (this.disabled) {
            return;
        }

        if (! this.open) {
            this.openMenu();

            return this.$nextTick(() => { this.focusPrevious() });
        }

        const elements = this.getFocusableElements();

        if (! elements.length) {
            this.focusedIndex = -1;

            return;
        }

        this.focusedIndex--;
        if (this.focusedIndex < 0) {
            this.focusedIndex = elements.length - 1;
        }

        focusElement(elements[this.focusedIndex], this.menu);
    },

    focusFirst() {
        const elements = this.getFocusableElements();

        if (! elements.length) {
            this.focusedIndex = -1;

            return;
        }

        this.focusedIndex = 0;

        focusElement(elements[this.focusedIndex], this.menu);
    },

    focusLast() {
        const elements = this.getFocusableElements();

        if (! elements.length) {
            this.focusedIndex = -1;

            return;
        }

        this.focusedIndex = elements.length - 1;

        focusElement(elements[this.focusedIndex], this.menu);
    },
});
