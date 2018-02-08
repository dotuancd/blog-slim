<template>
    <div>
        <form class="form" @submit.prevent="save">
            <div class="form-group">
                <textarea class="form-control" v-model="comment.content" id="comment-content" rows="5"></textarea>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-link" type="button" v-if="comment.content" @click="clearComment">Cancel</button>
                <button class="btn btn-success" v-bind:disabled="!comment.content" type="submit">
                    <span class="glyphicon glyphicon-send"></span> Comment
                </button>
            </div>
            <div class="form-group" v-if="error">
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{error}}
                </div>
            </div>
        </form>
    </div>
</template>

<script>

    import autosize from 'autosize';

    export default {
        name: "create",
        mounted() {
            autosize(document.getElementById('comment-content'));
        },
        data() {
            return {
                comment: {
                    content: null
                },
                error: null
            }
        },
        props: {
            post: {
                type: Object,
                required: true
            }
        },
        methods: {
            clearComment() {
                this.comment.content = null;
            },
            save() {
                axios
                .post('/api/posts/' + this.post.id + '/comments', this.comment)
                .then(() => {
                    this.$events.$emit('comments.submitted')
                    this.clearComment()
                }).catch(({response}) => {
                    this.error = 'An error occurred during save your comment. Please try again later';
                })
            }
        }
    }
</script>