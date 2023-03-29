module.exports = (colors, expectedVariants) => {
    const filteredColors = {};

    for (const colorName in colors) {
        const color = colors[colorName];

        if (typeof color !== 'object' || color === null || Array.isArray(color)) {
            continue;
        }

        if (expectedVariants.every(v => color.hasOwnProperty(v))) {
            filteredColors[colorName] = color;
        }
    }

    return filteredColors;
};
