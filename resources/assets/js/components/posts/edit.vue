<template>
    <div class="container">
        <form id="edit">
            <div class="form-group">
                <label for="title">Title</label>
                <input id="title" class="form-control" v-model="post.title" placeholder="Untitled Post">
            </div>
            <div class="form-group">
                <textarea class="form-control" v-model="post.content">
                </textarea>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-success" @click="update" v-bind:class="{disabled: saving}">
                    Save <span class="glyphicon glyphicon-send"></span>
                </button>
                <router-link :to="{name: 'posts.index'}" class="btn btn-link">Cancel</router-link>
            </div>
        </form>
    </div>
</template>
<script>
    import post from '../../models/post.js'
    import SimpleMDE from 'simplemde'
    import marked from 'marked'

    export default{
        mounted() {
            this.editor = new SimpleMDE({
                spellChecker: false
            });
            this.editor.codemirror.setOption('theme', 'dracula');
            this.editor.codemirror.on('change', () => {
                this.post.content = this.editor.value()
            })

            post
            .get(this.$route.params.post)
            .then(({data}) => {
                this.post = data
                this.editor.value(data.content)
            })
        },
        data(){
            return{
                post: {
                    title: '',
                    content: ''
                },
                saving: false
            }
        },
        methods: {
            update() {
                this.saving = true;
                post
                .update(this.post)
                .then(() => {
                    this.saving = false;
                    this.$router.push({name: 'posts.index'})
                    this.$flash.success('Your update has been saved')
                });
            }
        }
    }
</script>
