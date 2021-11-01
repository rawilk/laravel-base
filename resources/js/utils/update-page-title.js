export const updatePageTitle = ({ newTitle, separator = ' | ', selector = '.page-title' }) => {
    document.querySelector(selector).innerText = newTitle;

    const currentTitle = window.document.title.split(separator);
    currentTitle.shift();
    currentTitle.unshift(newTitle);

    window.document.title = currentTitle.join(separator);
};
