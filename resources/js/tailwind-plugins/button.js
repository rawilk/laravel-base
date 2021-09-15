module.exports = function({ addComponents, addUtilities, theme, config }) {
    const colors = config('theme.colors', {});
    const expectedVariants = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'];
    const buttons = {};

    for (const colorName in colors) {
        const color = colors[colorName];

        if (typeof color !== 'object') {
            continue;
        }

        if (! expectedVariants.every(key => Object.keys(color).includes(key))) {
            continue;
        }

        buttons[`.button--${colorName}`] = {
            backgroundColor: color['600'],
            color: theme(`colors[white]`),
            '@apply shadow': {},
        };

        buttons[`.button--${colorName}:hover:not([disabled]):not(.button--disabled):not(.button--busy):not(.button--no-hover)`] = {
            backgroundColor: color['700'],
            '@apply shadow-lg': {},
        };

        buttons[`.button--${colorName}:focus:not([disabled]):not(.button--disabled):not(.button--busy):not(.button--no-hover)`] = {
            [`@apply ring-${colorName}-500`]: {},
        };

        buttons[`.button--outline-${colorName}`] = {
            backgroundColor: theme('colors.transparent'),
            color: color['600'],
            '@apply border-solid': {},
            [`@apply border-${colorName}-600`]: {},
        };

        buttons[`.button--outline-${colorName}:hover:not([disabled]):not(.button--disabled):not(.button--busy):not(.button--no-hover)`] = {
            backgroundColor: color['600'],
            color: theme('colors.white'),
        };

        buttons[`.button--outline-${colorName}:focus:not([disabled]):not(.button--disabled):not(.button--busy):not(.button--no-hover)`] = {
            '@apply border-transparent': {},
            [`@apply ring-${colorName}-500`]: {},
        };
    }

    const buttonUtilities = {
        // icon
        '.button--icon': {
            paddingLeft: theme('spacing[2]'),
            paddingRight: theme('spacing[2]'),
        },

        // xs
        '.button--xs': {
            paddingTop: theme('spacing[1.5]'),
            paddingBottom: theme('spacing[1.5]'),
            fontSize: theme('fontSize.xs'),
            lineHeight: theme('lineHeight[4]'),
        },
        '.button--xs:not(.button--icon)': {
            paddingLeft: theme('spacing[2.5]'),
            paddingRight: theme('spacing[2.5]'),
        },
        '.button--xs svg:not(.plain)': {
            height: theme('height.4'),
            width: theme('width.4'),
        },
        '.button--xs.button--icon': {
            paddingLeft: theme('spacing[1.5]'),
            paddingRight: theme('spacing[1.5]'),
        },

        // sm
        '.button--sm': {
            paddingTop: theme('spacing[2]'),
            paddingBottom: theme('spacing[2]'),
            fontSize: theme('fontSize.sm'),
            lineHeight: theme('lineHeight[4]'),
        },
        '.button--sm:not(.button--icon)': {
            paddingLeft: theme('spacing[3]'),
            paddingRight: theme('spacing[3]'),
        },
        '.button--sm.button--icon': {
            paddingLeft: theme('spacing[2]'),
            paddingRight: theme('spacing[2]'),
        },

        // md
        '.button--md': {
            paddingTop: theme('spacing[2]'),
            paddingBottom: theme('spacing[2]'),
            fontSize: theme('fontSize.sm'),
        },
        '.button--md:not(.button--icon)': {
            paddingLeft: theme('spacing[4]'),
            paddingRight: theme('spacing[4]'),
        },
        '.button--md.button--icon': {
            paddingLeft: theme('spacing[2]'),
            paddingRight: theme('spacing[2]'),
        },
        '.button--md.button--icon svg:not(.plain), .button.button--icon svg:not(.plain)': {
            height: theme('height.6'),
            width: theme('width.6'),
        },

        // lg
        '.button--lg': {
            paddingTop: theme('spacing[2]'),
            paddingBottom: theme('spacing[2]'),
            fontSize: theme('fontSize.base'),
        },
        '.button--lg:not(.button--icon)': {
            paddingLeft: theme('spacing[4]'),
            paddingRight: theme('spacing[4]'),
        },
        '.button--lg.button--icon': {
            paddingLeft: theme('spacing[2]'),
            paddingRight: theme('spacing[2]'),
        },

        // xl
        '.button--xl': {
            paddingTop: theme('spacing[3]'),
            paddingBottom: theme('spacing[3]'),
            fontSize: theme('fontSize.base'),
        },
        '.button--xl:not(.button--icon)': {
            paddingLeft: theme('spacing[6]'),
            paddingRight: theme('spacing[6]'),
        },
        '.button--xl.button--icon': {
            paddingLeft: theme('spacing[3]'),
            paddingRight: theme('spacing[3]'),
        },
    };

    addComponents(buttons);
    addUtilities(buttonUtilities);
};
