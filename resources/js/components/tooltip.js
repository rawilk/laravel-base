let createPopper;

const MAX_UID = 1000000;
const getUid = prefix => {
    do {
        prefix += Math.floor(Math.random() * MAX_UID);
    } while (document.getElementById(prefix));

    return prefix;
};

const VERTICAL_OFFSET = 20;
const HORIZONTAL_OFFSET = 10;

export default options => ({
    placement: options.placement || 'top',
    content: options.content || '',
    title: (options.content || '').replace(/(<([^>]+)>)/gi, ''),
    tooltipId: null,
    _popper: null,

    init() {
        createPopper = window.Popper ? window.Popper.createPopper : window.createPopper;

        if (typeof createPopper !== 'function') {
            throw new TypeError(`<x-tooltip> requires Popper (https://popper.js.org)`);
        }
    },

    hide() {
        console.log('i should hide');
        const tooltip = document.getElementById(this.tooltipId);
        if (tooltip) {
            tooltip.parentNode.removeChild(tooltip);
        }

        if (this._popper) {
            this._popper.destroy();
            this._popper = null;
        }

        this.title = this.content;
        this.tooltipId = null;
    },

    show() {
        console.log('I should show');
        // Abort if tooltip is already showing...
        if (this.title === null) {
            return;
        }

        this.title = null;
        this.tooltipId = getUid('tooltip');

        const tooltip = document.createElement('div');
        tooltip.setAttribute('class', 'tooltip');
        tooltip.setAttribute('role', 'tooltip');
        tooltip.setAttribute('id', this.tooltipId);
        tooltip.innerHTML = this.content;

        const arrow = document.createElement('div');
        arrow.setAttribute('class', 'tooltip-arrow');
        tooltip.appendChild(arrow);

        document.body.append(tooltip);

        this._popper = createPopper(this.$el, tooltip, {
            placement: this.placement,
            modifiers: [
                {
                    name: 'offset',
                    options: {
                        offset: ({ placement }) => {
                            return ['top', 'bottom'].includes(placement)
                                ? [0, VERTICAL_OFFSET]
                                : [0, HORIZONTAL_OFFSET];
                        }
                    },
                },
                {
                    name: 'preventOverflow',
                    options: {
                        boundariesElement: this.$el,
                    },
                },
            ],
        });
    },

    toggle() {
        if (this.title === null) {
            return this.hide();
        }

        this.show();
    },
});
