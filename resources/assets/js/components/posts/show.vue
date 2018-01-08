<template>
    <div>
        <router-link v-if="editable" :to="{ name: 'post.edit', params: { post: post.id}}" class="btn btn-success pull-right">
            <span class="glyphicon glyphicon-edit"></span> Edit
        </router-link>
        <article>
            <h1 style="color: lime">{{post.title}}</h1>
            <div class="pull-right">{{post.author}} published at {{post.created_at}}</div>
            <p v-html="post.content">
            </p>
            <router-link v-if="post.next" :to="{name: 'post.show', params: {post: post.next.id}}">
                Newer: {{post.next.title}}
            </router-link>
            <router-link v-if="post.prev" :to="{name: 'post.show', params: {post: post.prev.id}}">
                Older: {{post.prev.title}}
            </router-link>
        </article>
    </div>
</template>
<script>
    import marked from 'marked'
    import post from '../../models/post.js'

    export default{
        created() {
            post
            .get(this.$route.params.post)
            .then(({data}) => {
                this.post = data
                this.post.content = marked(data.content)
                this.editable = post.isOwnedBy(this.post, this.user)
            })
        },
        data(){
            return {
                post: {},
                editable: false,
                user: this.$session.get('user')
            }
        },
        methods: {
        }
    }
</script>
