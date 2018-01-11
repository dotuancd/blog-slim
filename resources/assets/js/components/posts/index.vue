<template>
    <div class="container">
        <router-link class="btn btn-success pull-right" :to="{name: 'posts.create'}">
            <span class="glyphicon glyphicon-plus"></span> New
        </router-link>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="post in posts">
                    <td>
                        <router-link :to="{name: 'post.show', params: {post: post.id}}">
                            {{post.title}}
                        </router-link>
                    </td>
                    <td>
                        <router-link class="btn btn-link" :to="{name: 'post.edit', params: {post: post.id}}">
                            <span class="glyphicon glyphicon-edit"></span> Edit
                        </router-link>
                        <router-link class="btn btn-link" :to="{name: 'post.edit', params: {post: post.id}}">
                            <span class="glyphicon glyphicon-trash"></span> Delete
                        </router-link>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    import axios from "axios"
    import post from "../../models/post.js"

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
