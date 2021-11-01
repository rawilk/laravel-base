import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import flatpickr from 'flatpickr';
import Clipboard from '@ryangjchandler/alpine-clipboard';
import { createPopper } from '@popperjs/core';

Alpine.plugin(collapse);
Alpine.plugin(Clipboard);

window.Alpine = Alpine;
window.flatpickr = flatpickr;
window.createPopper = createPopper;

window.Alpine.start();
