<template>
    <div>
        <form class="form" @submit.prevent="save">
            <div class="form-group">
                <textarea class="form-control" v-model="comment.content" id="comment-content" rows="8"></textarea>
            </div>
            <div class="form-group text-right">
                <button class="btn btn-success" type="submit">
                    <span class="glyphicon glyphicon-send"></span> Comment
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "create",
        data() {
            return {
                comment: {
                    content: null
                }
            }
        },
        props: {
            post: {
                type: Object,
                required: true
            }
        },
        methods: {
            save() {
                axios
                    .post('/api/posts/' + this.post.id + '/comments', this.comment)
                    .then(() => {
                        alert('saved');
                    })
            }
        }
    }
</script>

<style scoped>

</style>