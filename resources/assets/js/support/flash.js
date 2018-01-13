class MessageBag
{
    constructor() {
        this.messages = [];
    }

    add(message) {
        this.messages.push(message);
    }

    first() {
        return this.messages[0];
    }

    last() {
        return this.messages[this.messages.length - 1];
    }

    isEmpty() {
        return this.messages.length === 0;
    }

    all() {
        return this.messages;
    }
}

class Flash
{
    constructor() {
        this.flush()
    }
    // reset messages
    flush() {
        this.messages = {
            info: new MessageBag(),
            success: new MessageBag(),
            error: new MessageBag(),
            default: new MessageBag()
        }
    }

    add(message, bag) {
        bag = bag || 'default';

        if (!this.messages[bag]) {
            this.messages[bag] = new MessageBag()
        }

        this.messages[bag].add(message);
    }
    info(message) {
        this.add(message, 'info')
    }

    success(message) {
        this.add(message, 'success')
    }

    error(message) {
        this.add(message, 'error')
    }

    get(key) {
        key = key || 'default'
        if (this.has(key)) {
            return this.messages[key]
        }
    }

    has(key) {
        console.log(`has: ${key}`);
        console.log(!this.messages[key].isEmpty());
        return this.messages[key] && !this.messages[key].isEmpty()
    }

    any() {
        for (let key in this.messages) {
            if (!this.messages[key].isEmpty()) {
                return true;
            }
        }

        return false;
    }

    all() {
        let messages =  this.messages;
        this.flush();
        return messages;
    }
}

import FlashComponent from './flash-component'

export default {
    install(Vue) {

        let flash = new Flash();
        Vue.$flash = Vue.prototype.$flash = flash;
        Vue.component('flash', FlashComponent)
    }
}