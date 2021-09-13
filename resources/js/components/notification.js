export default (options) => ({
    notices: [],
    visible: [],
    timeShown: options.timeout || 5000, // in ms

    add(notice) {
        this.notices.push(notice);
        this.fire(notice.id);
    },

    fire(id) {
        const notification = this.notices.find(notice => notice.id === id);

        if (! notification) {
            return;
        }

        this.visible.push(notification);

        if (notification.autoDismiss) {
            setTimeout(() => {
                this.remove(id);
            }, this.timeShown);
        }
    },

    remove(id) {
        this.removeFrom('visible', id);
        this.removeFrom('notices', id);
    },

    removeFrom(arrayName, id) {
        const notice = this[arrayName].find(notice => notice.id === id);
        const index = this[arrayName].indexOf(notice);

        if (index > -1) {
            this[arrayName].splice(index, 1);
        }
    },
});
