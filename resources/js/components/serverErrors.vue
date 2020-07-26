<template>        
    <div v-if="errors.length" class="alert alert-danger" role="alert">
        <a> Исправьте следующие ошибки: </a>
        <ul>
            <li v-for="(error, key) in errors" :key="key">
                {{error}}
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'serverErrors',
        props: ['errors']
    }
</script>