import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import Clipboard from '@ryangjchandler/alpine-clipboard';
import { createPopper } from '@popperjs/core';
import $root from '../../vendor/rawilk/laravel-base/resources/js/magics/$root';

Alpine.magic('root', $root);
Alpine.plugin(Clipboard);

window.Alpine = Alpine;
window.flatpickr = flatpickr;
window.createPopper = createPopper;

window.Alpine.start();
