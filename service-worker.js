
const CACHE_NAME = 'v-18';
const OFFLINE_URL = '/gw/offline_sie.html';

const PRECACHE_URLS = [
    '/gw/jquery-3.5.1.min.js',
    '/gw/dexie.js',
    '/gw/manifest.json',
    '/gw/offline_sie.html',
    '/gw/logo_Educ_PNG.png',
    '/gw/logo-sie-white.png'
];
self.addEventListener('install', (event) => {
    event.waitUntil((async () => {
        const cache = await caches.open(CACHE_NAME);
        // Setting {cache: 'reload'} in the new request will ensure that the response
        // isn't fulfilled from the HTTP cache; i.e., it will be from the network.
        await cache.addAll(PRECACHE_URLS);
        await cache.add(new Request(OFFLINE_URL, {cache: 'reload'}));
    })());
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames.filter(function (cacheName) {
                    // Return true if you want to remove this cache,
                    // but remember that caches are shared across
                    // the whole origin
                }).map(function (cacheName) {
                    return caches.delete(cacheName);
                })
            );
        })
    );
});


self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        }).catch(() => {
            return caches.match(OFFLINE_URL);
        })
    );
});

