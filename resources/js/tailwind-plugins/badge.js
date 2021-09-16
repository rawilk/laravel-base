module.exports = function({ addComponents, config }) {
    const colors = config('theme.colors', {});
    const badges = {};

    for (const colorName in colors) {
        const color = colors[colorName];

        if (typeof color !== 'object') {
            continue;
        }

        badges[`.badge--${colorName}`] = {
            backgroundColor: color['100'],
            color: color['800'],
        };

        badges[`.badge--${colorName} .badge-dot`] = {
            color: color['500'],
        };

        badges[`.badge--${colorName} .badge__remove-button`] = {
            color: color['500'],
        };

        badges[`.badge--${colorName} .badge__remove-button:hover, .badge--${colorName} .badge__remove-button:focus`] = {
            color: color['700'],
        };
    }

    addComponents(badges);
};
