import countdown from './countdown';
import dropdown from './dropdown';
import modal from './modal';
import notification from './notification';
import scrollToTopButton from './scroll-to-top-button';
import tooltip from './tooltip';

document.addEventListener('alpine:init', () => {
    Alpine.data('countdown', countdown);
    Alpine.data('dropdown', dropdown);
    Alpine.data('modal', modal);
    Alpine.data('notification', notification);
    Alpine.data('scrollToTopButton', scrollToTopButton);
    Alpine.data('tooltip', tooltip);
});
