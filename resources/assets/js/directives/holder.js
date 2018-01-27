import Holder from 'holderjs'

export default {
    install(Vue, options) {
        let defaults = {
            domain: 'holder.js',
            img: '100x100'
        };
        options = Object.assign(defaults, options);
        Vue.directive('holder', {
            bind (el, binding) {
                let value = binding.value;
                let src = options.domain + '/';
                if (!value) {
                    src += options.img;
                } else if (typeof value === 'object') {
                    let size = defaults.img;
                    if (value.hasOwnProperty('img')) {
                        size = value.img;
                        delete value.img;
                    }
                    src += size + '?' + Object.keys(value)
                        .map((prop) => {
                            return [prop, value[prop]].map(encodeURIComponent).join('=');
                        })
                        .join('&');
                } else if (typeof value === 'string') {
                    src += value;
                }
                el.setAttribute('data-src', src);
                Holder.run({
                    images: el
                });
            }
        })
    }
}