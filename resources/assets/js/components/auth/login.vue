<template>
    <div>
        <h1>Sign in</h1>
        <form>
            <div class="alert alert-danger" v-if="error" v-text="error"></div>
            <div class="alert alert-success" v-if="success" v-text="success"></div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" v-model="credentials.email" id="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" v-model="credentials.password" id="password">
            </div>
            <div class="form-group">
                <button type="button" @click="login" class="btn btn-success" v-bind:class="{disabled: authenticating}">Login</button>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                authenticating: false,
                error: null,
                success: null,
                credentials: {
                    email: null,
                    password: null
                }
            }
        },
        methods: {
            login() {
                this.authenticating = true;
                this.error = '';
                this.success = '';
                axios
                .post('/api/auth', this.credentials)
                .then(({data}) => {
                    this.authenticating = false;
                    let user = data;
                    window.user = user;
                    this.$session.set('user', JSON.stringify(user));
                    this.success = "Welcome back. Have a good day.";
                    this.$router.push({name:'posts.index'});
                })
                .catch(({data}) => {
                    this.authenticating = false;
                    this.error = 'Email or password is incorrect';
                });
            }
        }
    }
</script>
