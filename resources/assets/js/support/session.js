let Session = {};

Session.install = function (Vue, options) {

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
    }

    Vue.Session = Vue.prototype.$session = new Session(options.storage);
}

export default Session;