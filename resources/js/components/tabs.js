export default options => ({
    id: options.id,
    parentId: options.id, // Allows our child `tabs` to access the tab component id
    activeTab: null,
    tabs: [],

    init() {
        // Wrapping in $nextTick so that all initial tabs get registered
        // before we check if nav needs refocusing.
        this.$nextTick(() => this.focusTabsOnPageLoad());
    },

    findTab(id) {
        return this.tabs.find(tab => tab.id === id);
    },

    hasTab(id) {
        return Boolean(this.findTab(id));
    },

    nonDisabledTabs() {
        return [...this.tabs]
            .filter(tab => ! tab.disabled);
    },

    registerTab(tab) {
        if (! tab.id || this.hasTab(tab.id)) {
            return;
        }

        this.tabs.push(tab);

        if (tab.active && ! this.activeTab) {
            this.activeTab = tab.id;
        }
    },

    selectTab(tabId) {
        const tab = this.findTab(tabId);

        if (! tab || tab.id === this.activeTab || tab.disabled) {
            return;
        }

        this.activeTab = tab.id;

        if (tab.href && tab.href !== '#') {
            this.navigateToTab(tab);
        }
    },

    /*
     * Navigate to the given tab's url.
     */
    navigateToTab(tab) {
        let uri = tab.href;

        try {
            /*
             * If we are navigating to a new url we need to
             * instruct our component to refocus itself on
             * page load for a better user experience.
             */
            const url = new URL(tab.href);
            const searchParams = new URLSearchParams(url.search);

            searchParams.set('_focusTabs', '1');
            url.search = searchParams.toString();

            uri = url.toString();
        } catch (e) {}

        window.location = uri;
    },

    focusTab(tabId) {
        const link = this.$refs.nav.querySelector(`#tab-${tabId}`);

        if (link && link.offsetParent) {
            link.focus();
        }
    },

    /*
     * When actual urls are used for each tab, we need to instruct
     * our component to refocus the nav on each page load for a
     * better user experience.
     */
    focusTabsOnPageLoad() {
        try {
            const search = new URLSearchParams(window.location.search);

            if (search.get('_focusTabs') !== '1') {
                return;
            }

            this.focusTab(this.activeTab);

            this.resetUrl();
        } catch (e) {}
    },

    /*
     * Remove our focus tabs query params from the URL.
     */
    resetUrl() {
        const searchParams = new URLSearchParams(window.location.search);
        searchParams.delete('_focusTabs');

        const newRelativePathQuery = `${window.location.pathname}?${searchParams.toString()}`;

        history.pushState(null, '', newRelativePathQuery);
    },

    /*
     * Event handlers...
     */

    onArrowLeft() {
        this._handleKeyboardNav(this.prevNonDisabledTab());
    },

    onArrowRight() {
        this._handleKeyboardNav(this.nextNonDisabledTab());
    },

    onHome() {
        this._handleKeyboardNav(this.nonDisabledTabs()[0]);
    },

    onEnd() {
        this._handleKeyboardNav(this.lastNonDisabledTab());
    },

    _handleKeyboardNav(tab) {
        if (! tab) {
            return;
        }

        this.selectTab(tab.id);
        this.focusTab(tab.id);
    },

    /*
     * Nav helpers
     */

    nextNonDisabledTab() {
        return this.nonDisabledTabs()[this.nextNonDisabledTabIndex()] || this.nonDisabledTabs()[0];
    },

    nextNonDisabledTabIndex() {
        return (this.nonDisabledTabs().indexOf(this.findTab(this.activeTab)) + 1) % this.nonDisabledTabs().length;
    },

    lastNonDisabledTab() {
        return this.nonDisabledTabs().slice(-1)[0];
    },

    prevNonDisabledTab() {
        return this.nonDisabledTabs()[this.prevNonDisabledTabIndex()] || this.lastNonDisabledTab();
    },

    prevNonDisabledTabIndex() {
        return Math.max(0, this.nonDisabledTabs().indexOf(this.findTab(this.activeTab))) - 1;
    },
});
