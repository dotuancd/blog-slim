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
                <strong>{{post.author}}</strong> published on {{toHumanDate(post.created_at)}}
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
            <div class="media-list">
                <div v-for="comment in post.comments" class="media">
                    <div class="media-left">
                        <div class="media-object">
                            <img v-holder="{img: '64x64', text: getHolderFromText(comment.user.name)}">
                        </div>
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading">{{comment.user.name}} <small>{{toHumanDate(comment.created_at)}}</small></h5>
                        <p>{{comment.content}}</p>
                    </div>
                </div>
            </div>
            <!-- </content> -->
            <div class="col-lg-12">
                <ul class="pager">
                    <li class="previous" v-if="post.prev">
                        <router-link :to="{name: 'posts.show', params: {slug: post.prev.slug}}">
                            &larr; Older: {{post.prev.title}}
                        </router-link>
                    </li>
                    <li class="next" v-if="post.next">
                        <router-link :to="{name: 'posts.show', params: {slug: post.next.slug}}">
                            Newer: {{post.next.title}} &rarr;
                        </router-link>
                    </li>
                </ul>
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
    import utils from '../../support/utils'

    export default{
        created() {
            window.onscroll = (e,a) => {

                if (this.comments.isLoading) {
                    return;
                }
                let top = document.body.scrollTop || document.documentElement.scrollTop;
                let bottom = top + document.documentElement.clientHeight;
                let height = document.body.scrollHeight;

                // load next comments before scroll to bottom of the page.

                let before  = 100;

                if (this.comments.allLoaded && bottom + before >= height) {
                    this.comments.page++;
                    this.fetchComments(this.post.comments);
                }
            };
            this.$events.$on('comments.submitted', () => {
                this.fetchComments()
            })
            this.fetchPost();
        },
        data(){
            return {
                post: {
                    comments: []
                },
                comments: {
                    page: 1,
                    perPage: 15,
                    total: 0,
                    allLoaded: false,
                    isLoading: false
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
            fetchComments(append) {
                this.comments.isLoading = true;
                return Comment
                .forPost(this.post, this.comments.page, this.comments.perPage)
                .then(({data}) => {
                    append = append || false;
                    let comments = data.data;
                    if (append) {
                        comments = this.post.comments.concat(comments);
                    }
                    this.$set(this.post, 'comments', comments);
                    this.comments.allLoaded = (data.next_page_url !== null);
                    this.comments.total = data.total;
                    this.comments.isLoading = false;
                });
            },
            getHolderFromText(name) {
                let parts = name.split(/\s+/);
                return parts.map((part) => { return part.substr(0, 1)}).join('');
            },
            toHumanDate(dateTime) {
                return utils.toHumanDate(dateTime);
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
