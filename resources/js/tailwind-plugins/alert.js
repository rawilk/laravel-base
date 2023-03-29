const darkModeSelector = require('./util/darkModeSelector');
const addDarkVariant = require('./util/addDarkVariant');
const plugin = require('tailwindcss/plugin');

module.exports = plugin.withOptions(function (options = {}) {
    return function ({ addComponents, config, theme }) {
        const darkSelector = darkModeSelector(config('darkMode', 'class'));
        const alerts = {};

        const darkBackgroundColor = options.darkBackgroundColor ?? theme('colors.gray.800');
        const darkDismissHoverBackgroundColor = options.darkDismissHoverBackgroundColor ?? theme('colors.gray.700');

        const variants = {
            error: 'red',
            info: 'blue',
            success: 'green',
            warning: 'orange',
        };

        for (const variant in variants) {
            const color = variants[variant];

            alerts[`.alert--${variant}`] = {
                color: theme(`colors.${color}.800`),
                backgroundColor: theme(`colors.${color}.50`),
                borderColor: theme(`colors.${color}.300`),
            };

            alerts[`.alert--${variant} .alert-dismiss`] = {
                color: theme(`colors.${color}.500`),
                '&:hover, &:focus': {
                    backgroundColor: theme(`colors.${color}.200`),
                }
            };

            alerts[`.alert--${variant} .alert-text a`] = {
                '&:hover': {
                    color: theme(`colors.${color}.600`),
                }
            };

            // Dark Mode
            addDarkVariant(alerts, `.alert--${variant}`, darkSelector, {
                color: theme(`colors.${color}.400`),
                backgroundColor: darkBackgroundColor,
                borderColor: theme(`colors.${color}.800`),
            });

            addDarkVariant(alerts, `.alert--${variant} .alert-dismiss`, darkSelector, {
                color: theme(`colors.${color}.400`),
                '&:hover, &:focus': {
                    backgroundColor: darkDismissHoverBackgroundColor,
                }
            });
        }

        addComponents(alerts);
    };
});
