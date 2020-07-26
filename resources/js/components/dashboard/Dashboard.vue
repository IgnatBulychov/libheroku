<template>
    <div>        
        <div class="progress">
            <div v-bind:class="[isLoading ? 'progress-bar-animated progress-bar-striped' : '']" class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <router-view></router-view>            
    </div>
</template>

<script>
    export default {
        name: 'dashboard',
        computed: {
            isLoading() {
                return this.$store.getters.isLoading;
            }
        }
    }
</script>