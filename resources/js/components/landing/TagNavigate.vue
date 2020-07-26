<template>
    <div v-if="tags" class="card card-default">
        <div class="text-center mt-3">
            Теги
        </div>
        <form @submit.prevent="getByTags" @change="tagsHaveBeenChanged = true">
            <div class="card-body">       
                <div class="card-body">
                    <div class="form-group">
                        <div v-for="tag in tags" :key="tag.id" class="form-check">
                            <input v-model="selectedTags" class="form-check-input" :id="'defaultCheck' + tag.id" type="checkbox" :value="tag.title">
                            <label class="form-check-label" :for="'defaultCheck' + tag.id">
                                {{tag.title}}
                            </label>
                        </div>
                    </div>
                </div>     
            </div>
            <div class="card-body text-center">    
                <button type="submit" class="btn btn-primary mx-2 my-2">Применить</button>
            </div>
        </form> 
    </div>
</template>

<script>
export default {
    name: 'tag-navigate',
    data() {
            return {
                tagsHaveBeenChanged: true
            };
    },
    created() {
        if (!this.tags.length) {
            this.$store.dispatch('getTags');
        }       
        if (this.$route.query.tags) {
            this.selectedTags = this.$route.query.tags.split(',');
        }
    },
    computed: {
        tags() {
            return this.$store.getters.tags;
        },
        selectedTags: {
            get: function () {
                return this.$store.getters.selectedTags;
            },
            set: function (newValue) {
                this.$store.commit('setSelectedTags', newValue);
            }
        },
        isLoading() {
            return this.$store.getters.isLoading;
        }
    },
    methods: {
        getByTags() {
            // сбрасываем строку поиска
            this.$store.commit('setSearchQuery', '');
            // проверка запроса по тегам на пустоту  
            if (this.selectedTags.length) {
                // проверка повторного перехода на тот же URL
                if (this.tagsHaveBeenChanged) {
                    this.$router.push({
                        path: '/booksbytags?tags=' + this.selectedTags.toString() + '&page=1'              
                    });
                    this.tagsHaveBeenChanged = false;
                }                
            } else {
                // проверка повторного перехода не тот же URL
                if (this.$route.fullPath != '/allbooks?page=1&tags&search') {
                     this.$router.push({
                    path: '/allbooks',
                    query: {
                        page: 1,
                        tags: null,
                        search: null
                    }
                    });
                }               
            }
        }
    }
}
</script>
