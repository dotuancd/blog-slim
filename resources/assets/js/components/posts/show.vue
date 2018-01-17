<template>
    <div class="container">
        <div class="text-right">
            <router-link v-if="editable" :to="{ name: 'post.edit', params: { post: post.slug}}" class="btn btn-link text-right">
                <span class="glyphicon glyphicon-edit"></span> Edit
            </router-link>
        </div>
        <article>
            <h1 class="text-warning">{{post.title}}</h1>
            <!-- <author> -->
            <div class="pull-right">
                {{post.author}} published at {{post.created_at}}
            </div>
            <div class="clearfix"></div>
            <!-- </author> -->

            <!-- <content> -->
            <div v-html="post.content">
            </div>
            <new-comment :post="post"></new-comment>
            <hr>
            <div>
                <div v-for="comment in post.comments">
                    {{comment.content}}
                </div>
            </div>
            <!-- </content> -->
            <div class="col-lg-12">
                <div class="col-lg-6 text-left">
                    <router-link v-if="post.prev" :to="{name: 'post.show', params: {slug: post.prev.slug}}">
                       << Previous post: {{post.prev.title}}
                    </router-link>
                </div>
                <div class="col-lg-6 text-right">
                    <router-link v-if="post.next" :to="{name: 'post.show', params: {slug: post.next.slug}}">
                       >> Next post: {{post.next.title}}
                    </router-link>
                </div>
            </div>
        </article>
    </div>
</template>
<script>
    import marked from 'marked'
    import post from '../../models/post.js'
    import NewComment from '../comments/create'

    export default{
        created() {
            this.fetchPost();
        },
        data(){
            return {
                post: {
                    comments: []
                },
                editable: false,
                user: this.$session.get('user')
            }
        },
        methods: {
            fetchPost () {
                return post
                .findBySlug(this.$route.params.slug)
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
        },
        components: {
            newComment: NewComment
        }
    }
</script>
