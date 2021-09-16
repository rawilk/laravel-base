import dropdown from './dropdown';
import notification from './notification';
import scrollToTopButton from './scroll-to-top-button';
import tooltip from './tooltip';

document.addEventListener('alpine:init', () => {
    Alpine.data('dropdown', dropdown);
    Alpine.data('notification', notification);
    Alpine.data('scrollToTopButton', scrollToTopButton);
    Alpine.data('tooltip', tooltip);
});
