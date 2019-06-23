<template>
    <div class="container">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="well bs-component">
                <form class="form-horizontal">
                    <fieldset>
                        <legend>Sign Up</legend>
                        <div class="alert alert-danger" v-if="error" v-text="error"></div>
                        <div class="alert alert-success" v-if="success" v-text="success"></div>
                        <div class="form-group">
                            <label for="name" class="col-lg-4 control-label">Name</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" v-model="user.name" id="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-lg-4 control-label">Email</label>
                            <div class="col-lg-8">
                                <input type="email" class="form-control" v-model="user.email" id="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-lg-4 control-label">Password</label>
                            <div class="col-lg-8">
                                <input type="password" class="form-control" v-model="user.password" id="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password-confirmation" class="col-lg-4 control-label">Retype password</label>
                            <div class="col-lg-8">
                                <input type="password" id="password-confirmation" class="form-control" v-model="user.password_confirmation">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-2">
                                <button type="button" @click="register" class="btn btn-success" v-bind:class="{disabled: sending}">Sign In</button>
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
           document.title = 'Sign Up'
        },
        data() {
            return {
                sending: false,
                error: null,
                success: null,
                user: {
                    email: null,
                    password: null,
                    password_confirmation: null
                }
            }
        },
        methods: {
            register() {
                this.sending = true;
                this.error = '';
                this.success = '';
                axios
                .post('/api/register', this.user)
                .then(({data}) => {
                    this.sending = false;
                    let user = data;
                    window.user = user;
                    this.$session.set('user', JSON.stringify(user));
                    this.$events.$emit('login', user);
                    this.success = "Welcome back. Have a good day.";
                    this.$router.push({name:'posts.index'});
                })
                .catch(({response}) => {
                    this.sending = false;
                    this.error = response.data.message;
                    if (response.status = 422) {
                        this.error = 'Hm, look like somethings wrong. Please check your data and try again';
                    }
                });
            }
        }
    }
</script>
