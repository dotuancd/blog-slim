<template>
    <div class="container">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="well bs-component">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>Sign in</legend>
                        <div class="alert alert-danger" v-if="error" v-text="error"></div>
                        <div class="alert alert-success" v-if="success" v-text="success"></div>
                        <div class="form-group">
                            <label for="email" class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="email" class="form-control" v-model="credentials.email" id="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" v-model="credentials.password" id="password">
                                <div class="checkbox">
                                    <label for="remember">
                                        <input type="checkbox" name="remember" id="remember"> Remember me in this computer
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-10 col-lg-offset-2">
                                <button type="button" @click="login" class="btn btn-success" v-bind:class="{disabled: authenticating}">Login</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
           document.title = 'Sign In'
        },
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
