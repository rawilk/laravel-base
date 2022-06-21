// TODO: Utilize a service worker for push notifications
const pushNotificationSupported = () => 'serviceWorker' in navigator && 'PushManager' in window;

export default options => ({
    notices: [],
    visible: [],
    timeShown: options.timeout || 5000, // in ms
    onclick: options.onclick || null,
    callbacks: {},
    onInit: options.onInit || null,

    init() {
        typeof this.onInit === 'function' && this.onInit();
    },

    registerCallback(name, callback) {
        this.callbacks[name] = callback;

        return this;
    },

    add(notice) {
        this.notices.push(notice);
        this.fire(notice.id);
    },

    fire(id) {
        const notification = this.notices.find(notice => notice.id === id);

        if (! notification) {
            return;
        }

        // Attempt to display a browser notification if the user has granted
        // us permission to...
        if (notification.defaultToBrowser) {
            this.attemptBrowserNotification(notification);
        } else {
            this.visible.push(notification);
        }

        if (notification.autoDismiss) {
            setTimeout(() => {
                this.remove(id);
            }, this.timeShown);
        }
    },

    attemptBrowserNotification(notification) {
        const permission = Notification.permission;

        switch (permission) {
            case 'granted':
                this.showBrowserNotification(notification);
                break;
            case 'default':
                if (pushNotificationSupported()) {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            this.showBrowserNotification(notification);
                        }
                    });
                } else {
                    this.visible.push(notification);
                }
                break;
            default:
                this.visible.push(notification);
                break;
        }
    },

    showBrowserNotification(notification) {
        const requireInteraction = notification.autoDismiss ? false : true;

        const browserNotification = new Notification(notification.title || 'Notification', {
            body: notification.text || notification.body || '',
            data: { ...notification },
            requireInteraction,
        });

        browserNotification.onclose = () => {
            try {
                this.remove(browserNotification.data.id);
            } catch (error) {}
        };

        browserNotification.onclick = () => {
            browserNotification.close();
            window.parent.focus();

            try {
                this.remove(browserNotification.data.id);
            } catch (error) {}

            if (this.callbacks.onclick) {
                this.callbacks.onclick(browserNotification);
            }
        };
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
