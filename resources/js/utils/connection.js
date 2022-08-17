/*
 * This is loosely based off livewire/livewire's connection js.
 */

import { getCsrfToken } from './getCsrfToken';

export default class Connection {
    constructor() {
        this.headers = {};
        this.method = 'POST';
    }

    usingMethod(method) {
        this.method = method;

        return this;
    }

    withHeaders(headers) {
        this.headers = headers;

        return this;
    }

    async send(uri, payload) {
        const csrfToken = getCsrfToken();

        const response = await fetch(
            uri,
            {
                method: this.method,
                body: JSON.stringify(payload),
                // This enables "cookies".
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'text/html, application/xhtml+xml',

                    // Set custom headers.
                    ...(this.headers),

                    // We'll set this explicitly to mitigate potential interference from ad-blockers/etc.
                    'Referer': window.location.href,
                    ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken }),
                }
            }
        );

        if (! response.ok) {
            response.text().then(response => {
                this.showHtmlModal(response);
            });

            return false;
        }

        return response.json();
    }

    // this is an (almost) exact copy of livewire/livewire
    showHtmlModal(html) {
        let page = document.createElement('html');
        page.innerHTML = html;
        page.querySelectorAll('a').forEach(a =>
            a.setAttribute('target', '_top')
        );

        let modal = document.getElementById('laravel-base-error');

        if (typeof modal != 'undefined' && modal != null) {
            // Modal already exists.
            modal.innerHTML = '';
        } else {
            modal = document.createElement('div');
            modal.id = 'laravel-base-error';
            modal.style.position = 'fixed';
            modal.style.width = '100vw';
            modal.style.height = '100vh';
            modal.style.padding = '50px';
            modal.style.backgroundColor = 'rgba(0, 0, 0, .6)';
            modal.style.zIndex = 200000;
        }

        let iframe = document.createElement('iframe');
        iframe.style.backgroundColor = '#17161A';
        iframe.style.borderRadius = '5px';
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        modal.appendChild(iframe);

        document.body.prepend(modal);
        document.body.style.overflow = 'hidden';
        iframe.contentWindow.document.open();
        iframe.contentWindow.document.write(page.outerHTML);
        iframe.contentWindow.document.close();

        // Close on click.
        modal.addEventListener('click', () => this.hideHtmlModal(modal));

        // Close on escape key press.
        modal.setAttribute('tabindex', 0)
        modal.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                this.hideHtmlModal(modal);
            }
        });
        modal.focus();
    }

    hideHtmlModal(modal) {
        modal.outerHTML = '';
        document.body.style.overflow = 'visible';
    }
}
