<template>
    <div>
        <div v-for="post in posts">
            <router-link :to="{name: 'post.show', params: {post: post.id}}">
                <h2>{{post.title}}</h2>
            </router-link>
        </div>
    </div>
</template>
<script>
    import axios from "axios"
    import post from "../models/post.js"

    export default{
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
        }
    }
</script>
