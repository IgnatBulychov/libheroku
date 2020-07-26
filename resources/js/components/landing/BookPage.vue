<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header">
                        <nav class="navbar navbar-light bg-light">
                            <a class="navbar-brand"></a>
                            <search></search>
                        </nav>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <tag-navigate></tag-navigate>
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="container">
                                        <div class="row">
                                            <books-grid></books-grid>
                                            <pagination></pagination>                              
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BooksGrid from './BooksGrid';
    import TagNavigate from './TagNavigate';
    import Search from './Search';
    import Pagination from './Pagination';
    export default {
        name: 'book-page',
        components: {
            BooksGrid, TagNavigate, Search, Pagination
        }
    }
</script>

<style scoped>
.cover {
    overflow: hidden;
}
img {
    width: 100%;
    object-fit: contain;
}
.cover-card .card-body {
    padding: 0;
    margin: 0;
}
</style>