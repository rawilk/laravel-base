import notification from './notification';

document.addEventListener('alpine:init', () => {
    Alpine.data('notification', notification);
});
