class Session {
    constructor(storage) {
        this.storage = storage;
    }

    set (key, value) {
        console.log(`Key ${key} changed to: ${value}`);
        this.storage.setItem(key, value);
    }
    get (key) {
        let value = this.storage.getItem(key);
        try {
            return JSON.parse(value);
        } catch (e) {
            return value;
        }
    }
    has (key) {
        return this.storage.hasOwnProperty(key);
    }
    unset(key) {
        this.storage.removeItem(key);
    }
}

export default {
    install(Vue, options) {
        const session = new Session(options.storage);
        Object.defineProperty(Vue, '$session', {
            get() {
                return session;
            }
        });
        Object.defineProperty(Vue.prototype, '$session', {
            get() {
                return session;
            }
        });
    }
};