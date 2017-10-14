window.$ = window.jQuery = require('jquery');
window.axios = require('axios');
window.io    = require('socket.io-client');

window.axios.defaults.headers.common[ 'X-Requested-With' ] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) window.axios.defaults.headers.common[ 'X-CSRF-TOKEN' ] = token.content;
else console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');

import Echo from 'laravel-echo';

window.Echo = new Echo({
	broadcaster: 'socket.io',
	host       : window.location.host + ':6001',
});
