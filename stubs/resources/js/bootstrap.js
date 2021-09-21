import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import Clipboard from '@ryangjchandler/alpine-clipboard';
import { createPopper } from '@popperjs/core';

Alpine.plugin(Clipboard);

window.Alpine = Alpine;
window.flatpickr = flatpickr;
window.createPopper = createPopper;

window.Alpine.start();
