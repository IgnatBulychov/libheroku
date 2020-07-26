<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card card-default">
                    <div class="card-header">Редактирование книг</div>

                    <div class="card-body">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'books-main'
    }
</script>