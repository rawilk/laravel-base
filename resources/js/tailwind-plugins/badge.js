const filterColors = require('./util/filterColors');
const darkModeSelector = require('./util/darkModeSelector');
const addDarkVariant = require('./util/addDarkVariant');

module.exports = function ({ addComponents, config, theme }) {
    const expectedVariants = ['100', '300', '500', '600', '700', '800', '900'];
    const colors = filterColors(theme('colors'), expectedVariants);
    const darkSelector = darkModeSelector(config('darkMode', 'class'));
    const badges = {};

    for (const colorName in colors) {
        const color = colors[colorName];

        badges[`.badge--${colorName}`] = {
            backgroundColor: color['100'],
            color: color['800'],
        };

        badges[`.badge--${colorName} .badge-dot`] = {
            color: color['500'],
        };

        badges[`.badge--${colorName} .badge__remove-button`] = {
            color: color['500'],
            '&:hover, &:focus': {
                color: color['700'],
            },
        };

        // Dark Mode
        addDarkVariant(badges, `.badge--${colorName}`, darkSelector, {
            backgroundColor: color['900'],
            color: color['300'],
        });

        addDarkVariant(badges, `.badge--${colorName} .badge__remove-button`, darkSelector, {
            '&:hover, &:focus': {
                color: color['600'],
            },
        });
    }

    addComponents(badges);
};
