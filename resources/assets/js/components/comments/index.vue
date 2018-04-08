<template>
    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Comment</th>
                    <th>Commented at</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="comment in comments">
                    <td>{{comment.user.name}}</td>
                    <td>{{comment.content}}</td>
                    <td>{{toHumanDate(comment.created_at)}}</td>
                </tr>
            </tbody>
            <ul class="pagination">
                <li v-for="page in pages" :class="{active: page === currentPage}">
                    <router-link :class="{disabled: page === currentPage}" :to="{name: 'posts.comments', params: {post: $route.params.post}, query: {page: page}}">
                        {{page}}
                    </router-link>
                </li>
            </ul>
        </table>
    </div>
</template>

<script>

    import Comment from '../../models/comment'
    import utils from '../../support/utils'

    export default {
        name: "comments",
        mounted() {
            this.fetchComments(this.currentPage)
        },
        data() {
            return {
                comments: [],
                pages: [],
                currentPage: 1
            }
        },
        methods: {
            toHumanDate(dateTime) {
                return utils.toHumanDate(dateTime)
            },
            fetchComments(page) {
                let post = {id: this.$route.params.post}
                Comment
                    .forPost(post, page, 2)
                    .then(({data}) => {
                        this.comments = data.data;

                        let pages = [];
                        for (var i = 1; i <= data.last_page; i++) {
                            pages.push(i)
                        }
                        this.currentPage = data.current_page
                        this.pages = pages;
                    })
            }
        },
        watch: {
            $route(to, from) {
                this.fetchComments(to.query.page)
            }
        }
    }
</script>

<style scoped>
    .pagination a{
        text-decoration-line: none;
    }
</style>