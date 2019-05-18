<template>
    <div class="b_screenDashboard">
        <div class="row">
            <div class="input-group">
                <select class="custom-select" v-model="newWidgetType">
                    <option value="score">Score</option>
                    <option value="timer">Timer</option>
                    <option value="squad">Squad</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" @click="addNewWidget">Add widget</button>
                </div>
            </div>
        </div>
        <div class="row">
            <template v-if="!isLoading">
                <template v-if="widget" v-for="widget in screen.widgets">
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
                    <template v-else-if="widget.type == 'squad'">
                        <div class="col-4">
                        <squad-widget :screen="screen" :widget="widget"></squad-widget>
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
                screen: null,
                newWidgetType: 'score'
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            addNewWidget() {
                if(this.isLoading) {
                    return;
                }
                this.isLoading = true;
                axios.get('/screen/'+this.screen.id+'/addwidget/'+this.newWidgetType).then((resp)=>{
                    this.screen.widgets[resp.data.newWidget.id] = resp.data.newWidget;
                    this.isLoading = false;
                });
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
