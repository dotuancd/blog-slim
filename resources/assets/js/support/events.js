import Vue from 'vue';

const events = new Vue;

export default {
    install(Vue) {
        Object.defineProperty(Vue.prototype, '$events', {
            get() {
                return events;
            }
        });

        Object.defineProperty(Vue, '$events', {
            get() {
                return events;
            }
        });
    }
}