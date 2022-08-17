import Connection from '../utils/connection';

export default options => ({
    userId: options.userId,
    impersonateUrl: options.impersonateUrl,
    stopImpersonateUrl: options.stopImpersonateUrl,
    connection: null,

    init() {
        this.connection = new Connection();
    },

    async startImpersonating() {
        let response = null;

        response = await this.connection.send(this.impersonateUrl, { userId: this.userId });

        if (response === false) {
            return;
        }

        const redirect = response?.redirect || null;

        if (redirect !== null) {
            location.href = redirect;

            return;
        }

        alert('Oops! Something went wrong.');
    },

    async stopImpersonating() {
        let response = null;

        response = await this.connection.usingMethod('DELETE')
            .send(this.stopImpersonateUrl);

        if (response === false) {
            return;
        }

        const redirect = response?.redirect || null;

        if (redirect !== null) {
            location.href = redirect;

            return;
        }

        alert('Oops! Something went wrong.');
    },
});
