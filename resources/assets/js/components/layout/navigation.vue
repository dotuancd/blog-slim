<template>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <router-link class="navbar-brand" :to="{name: 'index'}">Boom's Blog</router-link>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <router-link :to="{name: 'index'}">
                            Press
                        </router-link>
                    </li>
                    <li><a href="#">About<span class="sr-only">(current)</span></a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right" v-if="!user">
                    <li>
                        <router-link :to="{name: 'login'}"><strong>Sign in</strong></router-link>
                    </li>
                    <li class="navbar-text">or</li>
                    <li>
                        <router-link :to="{name: 'register'}"><strong>Sign up</strong></router-link>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right" v-else>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{user.name}} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Profile</a></li>
                            <li>
                                <router-link :to="{name: 'me.changes-password'}">
                                    Change password
                                </router-link>
                            </li>
                            <li class="divider"></li>
                            <li><a href="#" @click="logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "navigation",
        mounted() {
            this.$events.$on('logout', () => {
                console.log('logout');
                this.user = null;
                console.log(this.user);
            });

            this.$events.$on('login', (user) => {
                console.log("login");
                this.user = user;
            });
        },
        data() {
            return {
                user: this.$session.get('user')
            };
        },
        methods: {
            logout() {
                this.$events.$emit('logout');
                console.log("Logout triggered");
                this.$session.unset('user');
                this.$router.push({name: 'index'});
            }
        }
    }
</script>
<style scoped>
    .navbar {
        border-radius: 0px;
    }
    .navbar .navbar-text {
        margin-right: 0;
        margin-left: 0;
    }
</style>