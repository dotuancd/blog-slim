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
            <hr>
            <legend>{{comments.total}} Comments</legend>
            <div class="well well-lg">
                <new-comment :post="post" v-if="$session.has('user')"></new-comment>
                <login-link v-else></login-link>
            </div>
            <div class="list-group">
                <div v-for="comment in post.comments" class="list-group-item">
                    <h4 class="list-group-item-heading">{{comment.user.name}}</h4>
                    <p class="list-group-item-text">{{comment.content}}</p>
                </div>
            </div>
            <!-- </content> -->
            <div class="col-lg-12">
                <ul class="pager">
                    <li class="previous" v-if="post.prev">
                        <router-link :to="{name: 'post.show', params: {slug: post.prev.slug}}">
                            &larr; Older: {{post.prev.title}}
                        </router-link>
                    </li>
                    <li class="next" v-if="post.next">
                        <router-link :to="{name: 'post.show', params: {slug: post.next.slug}}">
                            Newer: {{post.next.title}} &rarr;
                        </router-link>
                    </li>
                </ul>
                <div class="col-lg-6 text-right">

                </div>
            </div>
        </article>
    </div>
</template>
<script>
    import marked from 'marked'
    import post from '../../models/post.js'
    import NewComment from '../comments/create'
    import LoginLink from '../supports/login-link'
    import Comment from '../../models/comment'

    export default{
        created() {
            this.fetchPost();
        },
        data(){
            return {
                post: {
                    comments: []
                },
                comments: {
                    page: 1,
                    perPage: 10,
                    total: 0
                },
                postComments: [],
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
                    this.editable = post.isOwnedBy(this.post, this.user);
                    this.fetchComments();
                })
            },
            fetchComments() {
                return Comment
                .forPost(this.post, this.comments.page, this.comments.perPage)
                .then(({data}) => {
                    this.post.comments = data.data;
                    this.comments.total = data.total;
                });
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
            LoginLink,
            NewComment
        }
    }
</script>
