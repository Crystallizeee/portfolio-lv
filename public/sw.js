const CACHE_NAME = 'portfolio-pwa-v1';
const assetsToCache = [
    '/',
    '/manifest.json',
    '/favicon.ico',
    // We'll let the user's browser cache the compiled Vite assets automatically
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(assetsToCache);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});
