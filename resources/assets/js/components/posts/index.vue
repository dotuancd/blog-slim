<template>
    <div class="container">
        <flash bag="success" class="alert alert-success" dismissible></flash>
        <div class="row container">
            <router-link class="btn btn-success pull-right" :to="{name: 'posts.create'}">
                <span class="glyphicon glyphicon-plus"></span> New
            </router-link>
        </div>
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
                        <router-link :to="{name: 'posts.edit', params: {post: post.id}}">
                            {{post.title}}
                        </router-link>
                    </td>
                    <td>
                        <router-link class="btn btn-link" :to="{name: 'posts.show', params: {slug: post.slug}}">
                            <span class="glyphicon glyphicon-eye-open"></span> View
                        </router-link>
                        <router-link class="btn btn-link" :to="{name: 'posts.edit', params: {post: post.id}}">
                            <span class="glyphicon glyphicon-edit"></span> Edit
                        </router-link>
                        <router-link class="btn btn-link" :to="{name: 'posts.destroy', params: {post: post.id}}">
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
            post.admin(page).then(({data}) => this.posts = data.data)
            document.title = 'Post management'
        },
        mounted() {
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
