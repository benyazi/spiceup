<template>
    <div class="b_screenDashboard">
        <div class="row">
            <template v-if="!isLoading">
                <template v-for="widget in screen.widgets">
                    <template v-if="widget.type=='score'">
                        <div class="col-4">
                        <score-widget :screen="screen" :widget="widget"></score-widget>
                        </div>
                    </template>
                    <template v-else-if="widget.type == 'timer'">
                        <div class="col-4">
                        <timer-widget :screen="screen" :widget="widget"></timer-widget>
                        </div>
                    </template>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['uuid'],
        data() {
            return {
                isLoading: true,
                screen: null
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                axios.get('/data/'+this.uuid).then((resp)=>{
                    this.screen = resp.data;
                    this.isLoading = false;
                });
            }
        }
    }
</script>
