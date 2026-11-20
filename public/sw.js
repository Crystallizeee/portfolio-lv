const CACHE_NAME = 'portfolio-pwa-v3';
const STATIC_ASSETS = [
    '/manifest.json',
    '/favicon.ico',
];

// Install: only cache truly static assets (not HTML pages)
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    // Activate immediately, don't wait for old SW to finish
    self.skipWaiting();
});

// Activate: delete old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        })
    );
    // Take control of all pages immediately
    self.clients.claim();
});

// Fetch: Network-first for HTML, Cache-first for static assets
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // For navigation requests (HTML pages): always go to network first
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .catch(() => caches.match('/'))
        );
        return;
    }

    // For static assets (JS, CSS, images): cache-first
    if (url.pathname.startsWith('/build/') || url.pathname.startsWith('/icons/')) {
        event.respondWith(
            caches.match(event.request).then((cached) => {
                return cached || fetch(event.request).then((response) => {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                    return response;
                });
            })
        );
        return;
    }

    // Everything else: network-first
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});

// Web Push: show system notification on incoming push message
self.addEventListener('push', (event) => {
    if (!event.data) return;

    let data = {};
    try {
        data = event.data.json();
    } catch {
        data = { title: 'New Post', body: event.data.text() };
    }

    const title = data.title || 'New Post Published';
    const options = {
        body: data.body || 'Check out the latest article.',
        icon: data.icon || '/apple-touch-icon.png',
        badge: data.badge || '/favicon.ico',
        data: { url: data.url || '/blog' },
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Web Push: open article URL when user taps the notification
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const targetUrl = event.notification.data?.url || '/blog';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
