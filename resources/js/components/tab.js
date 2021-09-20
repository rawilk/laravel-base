export default options => ({
    show: options.active,
    href: options.href,
    name: options.name,
    id: options.id,
    active: options.active,
    disabled: options.disabled,

    init() {
        this.$dispatch('register-tab', {
            id: this.id,
            name: this.name,
            href: this.href,
            active: this.active,
            disabled: this.disabled,
        });

        // Watch the 'activeTab' property defined on the parent tab's component definition.
        this.$watch('activeTab', tabId => {
            this.show = tabId === this.id;
        });
    }
});
