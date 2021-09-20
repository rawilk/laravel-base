export default options => ({
    days: options.days,
    hours: options.hours,
    minutes: options.minutes,
    seconds: options.seconds,
    expires: options.expires,
    interval: null,

    timer: {},

    init() {
        this.expires = new Date(this.expires * 1000).getTime();

        this.timer = {
            days: this.days,
            hours: this.hours,
            minutes: this.minutes,
            seconds: this.seconds,
        };

        this.start();
    },

    formatCounter(number) {
        return number.toString().padStart(2, '0');
    },

    start() {
        this.interval = setInterval(() => {
            const timeDistance = this.expires - new Date().getTime();

            if (timeDistance < 0) {
                clearInterval(this.interval);

                this.$dispatch('timer-finished');

                return;
            }

            this.timer.days = this.formatCounter(Math.floor(timeDistance / (1000 * 60 * 60 * 24)));
            this.timer.hours = this.formatCounter(Math.floor((timeDistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)));
            this.timer.minutes = this.formatCounter(Math.floor((timeDistance % (1000 * 60 * 60)) / (1000 * 60)));
            this.timer.seconds = this.formatCounter(Math.floor((timeDistance % (1000 * 60)) / 1000));
        }, 1000);
    },
});
