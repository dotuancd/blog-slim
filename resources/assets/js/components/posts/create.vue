<template>
    <div class="container">
        <form id="new">
            <div class="form-group">
                <label for="title">Title</label>
                <input id="title" class="form-control" v-model="post.title" placeholder="Untitled Post">
            </div>
            <div class="form-group">
                <textarea class="form-control" v-model="post.content">
                </textarea>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-success" @click="store" v-bind:class="{disabled: saving}">
                    Save <span class="glyphicon glyphicon-send"></span>
                </button>
                <router-link type="button" class="btn btn-link" :to="{name: 'posts.index'}">
                    Cancel
                </router-link>
            </div>
        </form>
    </div>
</template>
<script>
    import post from '../../models/post.js'
    import SimpleMDE from 'simplemde'

    export default{
        mounted() {
            document.title = 'New post'
            this.editor = new SimpleMDE({
                spellChecker: false
            });
            this.editor.codemirror.setOption('theme', 'dracula');
            this.editor.codemirror.on('change', () => {
                this.post.content = this.editor.value();
            });
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
            store() {
                this.saving = true;
                post
                    .store(this.post)
                    .then(() => {
                        this.saving = false;
                        this.$router.push({name: 'posts.index'});
                    });
            }
        }
    }
</script>
