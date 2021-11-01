export const updateBreadcrumb = (newTitle, selector = '.breadcrumb-item--active') => {
    document.querySelector(selector).innerText = newTitle;
};
