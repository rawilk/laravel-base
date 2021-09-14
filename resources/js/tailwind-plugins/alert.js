module.exports = function({ addComponents, theme }) {
    const variants = {
        error: 'red',
        info: 'blue',
        success: 'green',
        warning: 'orange',
    };

    const alerts = {};

    Object.keys(variants).forEach(key => {
        const color = variants[key];

        alerts[`.alert--${key}`] = {
            color: theme(`colors.${color}.700`),
            backgroundColor: theme(`colors.${color}.50`),
            borderColor: theme(`colors.${color}.400`),
        };

        alerts[`.alert--${key} .alert-title`] = {
            color: theme(`colors.${color}.800`),
        };

        alerts[`.alert--${key} .alert-icon`] = {
            color: theme(`colors.${color}.500`),
        };

        alerts[`.alert--${key} .alert-dismiss`] = {
            color: theme(`colors.${color}.500`),
        };

        alerts[`.alert--${key} .alert-dismiss:hover, .alert--${key} .alert-dismiss:focus`] = {
            backgroundColor: theme(`colors.${color}.200`),
        };

        alerts[`.alert--${key} .alert-text a:hover`] = {
            color: theme(`colors.${color}.600`),
        };
    });

    addComponents(alerts);
};
