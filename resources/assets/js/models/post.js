export default {
    get(id) {
        return axios.get('/api/posts/' + id)
    },
    paginate(page) {
        return axios.get('/api/posts', {params: {page}})
    },
    store(post) {
        return axios.post('/api/posts', {
            title: post.title,
            content: post.content
        });
    },
    update(post) {
        return axios
            .put('/api/posts/' + post.id, {
                title: post.title,
                content: post.content
            });
    },
    isOwnedBy(post, user) {
        return user && (post.user_id == user.id);
    }
}