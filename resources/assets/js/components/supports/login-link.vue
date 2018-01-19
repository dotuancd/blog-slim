<template>
    <div class="well well-lg">
        To leave a comment please <router-link :to="{name: 'login'}">Sign In</router-link> or <router-link :to="{name: 'register'}">Sign Up</router-link>
    </div>
</template>

<script>
    export default {
        name: "login-link"
    }
</script>