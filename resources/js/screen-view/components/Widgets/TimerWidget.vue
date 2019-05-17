<template>
    <div class="b_widgetWrap b_tw" :style="{top:positionTop,left:positionLeft}">
        <div class="b_tw_time">
            {{currentTimeString}}
        </div>
        <template v-if="advancedSize">
            <div class="b_tw_adv_size">
                + {{advancedSize}}
            </div>
            <div class="b_tw_adv_time">
                {{advancedTimeString}}
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: ['screen', 'widget'],
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
            positionTop() {
                if(this.widget.data.position) {
                    return this.widget.data.position.top;
                }
                return 0;
            },
            positionLeft() {
                if(this.widget.data.position) {
                    return this.widget.data.position.left;
                }
                return 0;
            },
        },
        mounted() {
            this.currentWidgetData = this.widget;
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
