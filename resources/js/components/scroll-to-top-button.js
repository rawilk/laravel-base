export default (options) => ({
    show: false,
    observer: null,

    init() {
        this.observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                this.show = ! entry.isIntersecting;
            });
        });

        this.observer.observe(this.$el);
    },

    toTop() {
        window.scroll({ top: 0, left: 0, behavior: 'smooth' });
    },
});
