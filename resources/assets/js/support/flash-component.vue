<template>
    <div v-if="$flash.has(bag)" :class="classes">
        <button v-if="dismissible" type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{$flash.get(bag).first()}}
    </div>
</template>
<script>
    export default {
        props: {
            bag: String,
            classes: String,
            dismissible: Boolean
        }
    }
</script>
