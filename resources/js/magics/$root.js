export default el => {
    const closestRootEl = node => {
        if (node.hasAttribute('x-data')) {
            return node;
        }

        return closestRootEl(node.parentNode);
    };

    return closestRootEl(el);
};
