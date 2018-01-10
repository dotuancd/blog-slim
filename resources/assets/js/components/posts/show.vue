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
                Older: {{post.next.title}}
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
            this.fetchPost();
        },
        data(){
            return {
                post: {},
                editable: false,
                user: this.$session.get('user')
            }
        },
        methods: {
            fetchPost () {
                return post
                .get(this.$route.params.post)
                .then(({data}) => {
                    this.post = data
                    this.post.content = marked(data.content)
                    document.title = data.title;
                    this.editable = post.isOwnedBy(this.post, this.user)
                })
            }
        },
        watch: {
            '$route' (to, from) {
                // One thing to note when using routes with params is that when the user
                // navigate from /posts/123 to /posts/234, the same component instance will be reused.
                // Since both routes render the same component, this more efficient than destroy the old
                // instance and then creating a new one. However, this also mean that the lifecycle hooks
                // of the component will not be called.
                //
                // To react to params changes in the same component, simply watch the '$route' object.
                // Or use the beforeRouteUpdate guard introduced in 2.2
                // So we watch the changes event to fetch the user clicked post and render to user.
                this.fetchPost();
            }
        }
    }
</script>
