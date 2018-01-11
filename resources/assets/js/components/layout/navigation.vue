<template>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Boom's Blog</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Press<span class="sr-only">(current)</span></a></li>
                    <li><a href="#">About<span class="sr-only">(current)</span></a></li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "navigation"
    }
</script>
<style scoped>
    .navbar {
        border-radius: 0px;
    }
</style>