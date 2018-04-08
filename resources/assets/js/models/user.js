export default {
    changePassword(data) {
        return axios.post('/api/me/change-password', {
            current_password: data.currentPassword,
            password: data.password
        })
    }
}