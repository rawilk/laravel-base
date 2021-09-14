import notification from './notification';
import scrollToTopButton from './scroll-to-top-button';

document.addEventListener('alpine:init', () => {
    Alpine.data('notification', notification);
    Alpine.data('scrollToTopButton', scrollToTopButton);
});
