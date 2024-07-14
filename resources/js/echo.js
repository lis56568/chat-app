import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});


// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: 'bnxrlamr45txee3hryup',
//     wsHost: 'reverb.lis56568-service.com',
//     wsPort: 80,
//     wssPort: 443,
//     forceTLS: 'https',
//     enabledTransports: ['ws', 'wss'],
// });
