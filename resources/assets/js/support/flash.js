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

        if (this.messages[bag]) {
            this.messages[bag].add(message);
        }
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

    first(bag) {
        bag = bag || 'default'
        return this.messages[bag].first();
    }

    last(bag) {
        bag = bag || 'default'
        return this.messages[bag].last();
    }

    isEmpty(bag) {
        bag = bag || 'default'
        return this.messages[bag].isEmpty()
    }

    all(bag) {
        bag = bag || 'default'
        return this.messages[bag].all();
    }
}

export default {
    install(Vue) {
        let flash = new Flash();
        Vue.$flash = Vue.prototype.$flash = flash;
    }
}