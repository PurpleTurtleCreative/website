function isBrowserLocalhost(): boolean {
    if (typeof window === "undefined") {
        return false;
    }
    const { hostname } = window.location;
    return (
        hostname === "localhost" ||
        hostname === "127.0.0.1" ||
        hostname === "[::1]"
    );
}

export const API_BASE_URL = isBrowserLocalhost()
    ? "http://localhost:8080"
    : "https://api.purpleturtlecreative.com";
