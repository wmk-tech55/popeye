module.exports = {
    get(key) {
        return JSON.parse(localStorage.getItem(key));
    },
    set(key, data) {
        return localStorage.setItem(key, JSON.stringify(data));
    },
    delete(key) {
        return localStorage.removeItem(key);
    },
    has(key) {
        return localStorage.getItem(key) !== null;
    },
}