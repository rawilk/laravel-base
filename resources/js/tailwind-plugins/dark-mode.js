const darkModeSelector = require('./util/darkModeSelector');
const addDarkVariant = require('./util/addDarkVariant');
const plugin = require('tailwindcss/plugin');

module.exports = plugin.withOptions(function (options = {}) {
    return function ({ addUtilities, config, theme }) {
        const darkSelector = darkModeSelector(config('darkMode', 'class'));
        const styles = {};

        // action list items
        addDarkVariant(styles, '.action-item', darkSelector, {
            backgroundColor: 'var(--action-item-dark-bg)',
            '&:focus-within': {
                '--tw-ring-color': 'var(--action-item-dark-ring-color)',
            },
        });

        // card
        addDarkVariant(styles, '.card .card-header + .card-body .table thead tr:first-child th', darkSelector, {
            backgroundColor: theme('colors.slate.800'),
        });

        // dropdown
        addDarkVariant(styles, '.dropdown-item', darkSelector, {
            '--dropdown-item-color': 'var(--dropdown-dark-item-color)',
            '--dropdown-item-hover-bg': 'var(--dropdown-dark-item-hover-bg)',
            '--dropdown-item-hover-color': 'var(--dropdown-dark-item-hover-color)',

            '&--active': {
                '--dropdown-item-active-bg': 'var(--dropdown-dark-item-active-bg)',
                '--dropdown-item-active-color': 'var(--dropdown-dark-item-active-color)',
                '--dropdown-item-active-hover-bg': 'var(--dropdown-dark-item-active-hover-bg)',
            }
        });

        // feed
        addDarkVariant(styles, '.feed-item-line', darkSelector, {
            backgroundColor: theme('colors.slate.500'),
        });

        addDarkVariant(styles, '.feed-item-ring', darkSelector, {
            backgroundColor: theme('colors.gray.600'),
        });

        addDarkVariant(styles, '.feed-white', darkSelector, {
            '.feed-item-ring': {
                '@apply ring-gray-800': {},
            },
        });

        // modal
        addDarkVariant(styles, '.modal-title', darkSelector, {
            color: 'var(--modal-dark-title-color)',
        });

        addDarkVariant(styles, '.modal-content', darkSelector, {
            color: 'var(--modal-dark-content-color)',
        });

        addDarkVariant(styles, '.modal-footer', darkSelector, {
            color: 'var(--modal-dark-footer-bg)',
        });

        // tooltip
        addDarkVariant(styles, '.tooltip', darkSelector, {
            '--tooltip-bg': 'var(--tooltip-dark-bg)',
        });

        addUtilities(styles);
    };
});
