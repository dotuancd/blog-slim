import axios from 'axios'

export default {
    forPost(post, page, perPage) {
        page = page || 1;
        perPage = perPage || 10;
        return axios.get('/api/posts/' + post.id + '/comments', {
            params: {
                page,
                limit: perPage
            }
        })
    }
}