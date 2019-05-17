<template>
    <div class="b_widgetDashboardWrap b_dsw">
        <div class="row">
            <div class="col-6">
                <div>
                    <button class="btn btn-primary w-100" @click="changeScore('home','add')">+</button>
                </div>
                <div class="text-center p-1" style="font-size: 36px;font-weight: bold">
                    {{scoreHome}}
                </div>
                <div>
                    <button class="btn btn-primary w-100" @click="changeScore('home','sub')">-</button>
                </div>
                <div>
                    {{teamHome}}
                </div>
            </div>
            <div class="col-6">
                <div>
                    <button class="btn btn-primary w-100" @click="changeScore('away','add')">+</button>
                </div>
                <div class="text-center p-1" style="font-size: 36px;font-weight: bold">
                    {{scoreAway}}
                </div>
                <div>
                    <button class="btn btn-primary w-100" @click="changeScore('away','sub')">-</button>
                </div>
                <div>
                    {{teamAway}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label>Position Top</label>
                    <input class="form-control" v-model="setting.position.top">
                </div>
                <div class="form-group">
                    <label>Position Left</label>
                    <input class="form-control" v-model="setting.position.left">
                </div>
                <div>
                    <button class="btn btn-primary" @click="saveSetting()">Save setting</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['screen', 'widgetData'],
        data() {
            return {
                currentWidgetData: null,
                state: 'play',
                part: 1,
                currentTime: {
                    startPoint: null,
                    minutes: 0,
                    seconds: 0
                },
                advancedSize: '3',
                advancedTime: {
                    startPoint: null,
                    minutes: 0,
                    seconds: 0
                },
                interval: null
            }
        },
        computed: {
            currentTimeString() {
                let minutes = this.currentTime.minutes;
                if(minutes < 10) {
                    minutes = '0' + minutes.toString();
                }
                let seconds = this.currentTime.seconds;
                if(seconds < 10) {
                    seconds = '0' + seconds.toString();
                }
                return minutes + ':' + seconds;
            },
            advancedTimeString() {
                let minutes = this.advancedTime.minutes;
                if(minutes < 10) {
                    minutes = '0' + minutes.toString();
                }
                let seconds = this.advancedTime.seconds;
                if(seconds < 10) {
                    seconds = '0' + seconds.toString();
                }
                return minutes + ':' + seconds;
            },
        },
        mounted() {
            this.currentWidgetData = this.widgetData;
            this.interval = setInterval(() => {
                this.secondInterval();
            }, 1000);
        },
        created() {
            var channel = PusherApp.subscribe('score-widget-' + this.screen.uuid);
            channel.bind('StateChanged', this.changeState);
            channel.bind('TimeChanged', this.changeTime);
        },
        methods: {
            changeState(data) {
                this.state = data.state;
            },
            changeTime(data) {
                this.currentTime.minutes = data.minutes;
                this.currentTime.seconds = data.seconds;
            },
            secondInterval() {
                if(this.state == 'pause') {
                    return;
                }
                let minutes = this.currentTime.minutes;
                if(minutes == 45 && this.part == 1) {

                } else if (minutes == 90 && this.part == 2) {

                } else {
                    let seconds = this.currentTime.seconds + 1;
                    if(seconds > 59) {
                        minutes++;
                        seconds = 0;
                    }
                    if(minutes > 45) {

                    }
                    this.currentTime.seconds = seconds;
                    this.currentTime.minutes = minutes;
                }
            }
        }
    }
</script>
