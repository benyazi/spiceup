<template>
    <div class="b_screenWrap">
        <template v-if="!isLoading">
            <template v-for="widget in screen.widgets">
                <template v-if="widget.type=='score'">
                    <score-widget :screen="screen" :widget="widget"></score-widget>
                </template>
                <template v-else-if="widget.type == 'timer'">
                    <timer-widget :screen="screen" :widget="widget"></timer-widget>
                </template>
            </template>
        </template>
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
