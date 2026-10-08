import { Passkeys } from "@laravel/passkeys";
import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

window.loginWithPasskey = async function () {
    try {
        const response = await Passkeys.verify({
            routes: {
                options: '/passkeys/login/options',
                submit: '/passkeys/login',
            },
        });

        if (response?.redirect) {
            window.location.href = response.redirect;
            return;
        }

        window.location.reload();
    } catch (error) {
        console.error(error);
        alert('Connexion avec passkey impossible.');
    }
};

window.confirmWithPasskey = async function () {
    try {
        const response = await Passkeys.verify({
            routes: {
                options: '/passkeys/confirm/options',
                submit: '/passkeys/confirm',
            },
        });

        if (response?.redirect) {
            window.location.href = response.redirect;
            return;
        }

        window.location.reload();
    } catch (error) {
        console.error(error);
        alert('Confirmation avec passkey impossible.');
    }
};

window.registerPasskey = async function (name) {
    try {
        await Passkeys.register({
            name: name || 'Passkey',
            routes: {
                options: '/user/passkeys/options',
                submit: '/user/passkeys',
            },
        });

        window.location.reload();
    } catch (error) {
        console.error(error);
        alert('Création de passkey impossible.');
    }
};