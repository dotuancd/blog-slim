<template>
    <div class="container">
        <div class="col-lg-8 col-lg-offset-2">
            <form action="" class="form-horizontal">
                <fieldset>
                    <legend>Change password</legend>
                    <div v-if="this.message" class="alert" v-bind:class="this.alert">
                        {{this.message}}
                    </div>
                    <div class="form-group">
                        <label for="current-password" class="control-label col-lg-4">
                            Current password
                        </label>
                        <div class="col-lg-8">
                            <input v-model="currentPassword" id="current-password" type="password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-lg-4" v-model="password">
                            Password
                        </label>
                        <div class="col-lg-8">
                            <input class="form-control" type="password" id="password" v-model="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password-confirmation" class="control-label col-lg-4">
                            Re-type password
                        </label>
                        <div class="col-lg-8">
                            <input type="password" v-model="passwordConfirmation" class="form-control" id="password-confirmation">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-8">
                            <button type="button" class="btn btn-success" @click="changePassword">Update</button>
                            <button type="button" class="btn btn-link" @click="back">Cancel</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</template>

<script>

    import User from '../../models/user';

    export default {
        name: "change-password",
        data() {
            return {
                message: null,
                alert: null,
                currentPassword: null,
                password: null,
                passwordConfirmation: null
            }
        },
        methods: {
            back() {
                this.$router.go(-1)
            },
            changePassword() {
                if (!this.currentPassword) {
                    this.message = 'Current password cannot be empty';
                    this.alert = 'alert-danger'
                    return;
                }

                if (!this.password) {
                    this.error = 'Password cannot be empty';
                    this.alert = 'alert-danger'
                    return;
                }

                if (this.password !== this.passwordConfirmation) {
                    this.error = 'Password confirmation is not match';
                    this.alert = 'alert-danger'
                    return;
                }

                User.changePassword(this)
                    .then(({data}) => {
                        this.message = data.message
                        this.alert = data.error ? 'alert-danger' : 'alert-success'
                    });
            }
        }
    }
</script>
