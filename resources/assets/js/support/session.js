class Session {
    constructor(storage) {
        this.storage = storage;
    }
    set (key, value) {
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
        Vue.$session = Vue.prototype.$session = new Session(options.storage);
    }
};