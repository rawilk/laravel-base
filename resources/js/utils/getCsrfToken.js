/*
 * This util is based off the getCsrfToken.js util from livewire/livewire.
 */

export function getCsrfToken() {
    const tokenTag = document.head.querySelector('meta[name="csrf-token"]');

    if (tokenTag) {
        return tokenTag.content;
    }

    return window.livewire_token ?? undefined;
}
