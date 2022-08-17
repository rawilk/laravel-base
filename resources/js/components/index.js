import countdown from './countdown';
import dropdown from './dropdown';
import impersonate from './impersonate';
import modal from './modal';
import notification from './notification';
import scrollToTopButton from './scroll-to-top-button';
import tabs from './tabs';
import tab from './tab';
import tooltip from './tooltip';

document.addEventListener('alpine:init', () => {
    Alpine.data('countdown', countdown);
    Alpine.data('dropdown', dropdown);
    Alpine.data('impersonate', impersonate);
    Alpine.data('modal', modal);
    Alpine.data('notification', notification);
    Alpine.data('scrollToTopButton', scrollToTopButton);
    Alpine.data('tabs', tabs);
    Alpine.data('tab', tab);
    Alpine.data('tooltip', tooltip);
});
