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
                <template v-else-if="widget.type == 'squad'">
                    <squad-widget :screen="screen" :widget="widget"></squad-widget>
                </template>
                <template v-else-if="widget.type == 'github_push'">
                    <github-push-widget :screen="screen" :widget="widget"></github-push-widget>
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
        created() {
            var channel = PusherApp.subscribe('score-widget-' + this.uuid);
            channel.bind('AddedNewWidget', this.addWidget);
        },
        mounted() {
            this.getData();
        },
        methods: {
            addWidget(data) {
                let newWidget = data.newWidget;
                this.$set(this.screen.widgets, newWidget.id, newWidget);
            },
            getData() {
                this.isLoading = true;
                axios.get('/data/'+this.uuid).then((resp)=>{
                    this.screen = resp.data;
                    this.isLoading = false;
                });
            }
        }
    }
</script>
