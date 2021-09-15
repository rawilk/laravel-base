import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import { createPopper } from '@popperjs/core';
import $root from '../../vendor/rawilk/laravel-base/resources/js/magics/$root';

Alpine.magic('root', $root);

window.Alpine = Alpine;
window.flatpickr = flatpickr;
window.createPopper = createPopper;

window.Alpine.start();
