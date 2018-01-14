export default {
    install(Vue, options) {
        window.axios.interceptors.response.use(function (response) {
            return response;
        }, function (error) {
            let router = options.router;
            let status = error.response.status;
            if (status == 404) {
                router.push({name: 'errors.404'});
            }
            return Promise.reject(error);
        });
    }
}