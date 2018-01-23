<template>
    <div class="container">
        <div v-for="post in posts">
            <h2>
                # <router-link :to="{name: 'posts.show', params: {slug: post.slug}}">{{post.title}}</router-link>
            </h2>
        </div>
    </div>
</template>
<script>
    import post from "../models/post.js"
    import navigation from "./layout/navigation"

    export default {
        created() {
            let page = this.$route.query.page || 1;
            post.paginate(page).then(({data}) => this.posts = data.data)
        },
        mounted() {
            console.log('Component mounted');
        },
        data(){
            return{
                posts: []
            }
        },
        components:{
            navigation
        }
    }
</script>
