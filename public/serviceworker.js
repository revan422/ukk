const CACHE_VERSION = "v1.0.0";
const STATIC_CACHE = `static-${CACHE_VERSION}`;
const DYNAMIC_CACHE = `dynamic-${CACHE_VERSION}`;

const STATIC_ASSETS = [
    "/",
    "/offline.html",
    "/manifest.webmanifest",
    "/icons/icon-72x72.svg",
    "/icons/icon-96x96.svg",
    "/icons/icon-128x128.svg",
    "/icons/icon-144x144.svg",
    "/icons/icon-152x152.svg",
    "/icons/icon-192x192.svg",
    "/icons/icon-384x384.svg",
    "/icons/icon-512x512.svg",
    // CSS/JS from CDN to cache-first
    "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css",
    "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js",
];

self.addEventListener("install", (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => cache.addAll(STATIC_ASSETS)),
    );
});

self.addEventListener("activate", (event) => {
    clients.claim();
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(
                    keys
                        .filter(
                            (k) => ![STATIC_CACHE, DYNAMIC_CACHE].includes(k),
                        )
                        .map((k) => caches.delete(k)),
                ),
            ),
    );
});

function cacheFirst(request) {
    return caches.match(request).then(
        (cached) =>
            cached ||
            fetch(request)
                .then((res) => {
                    return caches.open(DYNAMIC_CACHE).then((cache) => {
                        cache.put(request, res.clone());
                        return res;
                    });
                })
                .catch(() => caches.match("/offline.html")),
    );
}

function networkFirst(request) {
    return fetch(request)
        .then((res) => {
            return caches.open(DYNAMIC_CACHE).then((cache) => {
                cache.put(request, res.clone());
                return res;
            });
        })
        .catch(() =>
            caches
                .match(request)
                .then((cached) => cached || caches.match("/offline.html")),
        );
}

self.addEventListener("fetch", (event) => {
    const url = new URL(event.request.url);

    // API & dynamic pages -> network first
    if (
        url.pathname.startsWith("/bookings") ||
        url.pathname.startsWith("/flights") ||
        url.pathname.startsWith("/profile") ||
        url.pathname.startsWith("/api")
    ) {
        event.respondWith(networkFirst(event.request));
        return;
    }

    // Static files -> cache first
    if (
        event.request.destination === "style" ||
        event.request.destination === "script" ||
        event.request.destination === "image" ||
        event.request.destination === "font"
    ) {
        event.respondWith(cacheFirst(event.request));
        return;
    }

    // Default: try cache first
    event.respondWith(cacheFirst(event.request));
});

self.addEventListener("message", (event) => {
    if (event.data && event.data.type === "SKIP_WAITING") {
        self.skipWaiting();
    }
});
