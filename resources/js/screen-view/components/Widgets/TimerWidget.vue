<template>
    <div class="b_widgetWrap b_tw" :style="{top:positionTop,left:positionLeft}">
        <div class="b_tw_time">
            {{currentTimeString}}
        </div>
        <template v-if="advancedSize || (timestamp > part.maxValue)">
            <div class="b_tw_adv_size">
                + {{advancedSize}}
            </div>
            <div class="b_tw_adv_time" v-if="(timestamp > part.maxValue)">
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
                timestamp: 0,
                currentWidgetData: null,
                state: 'play',
                part: {
                    title: 1,
                    maxValue: 100
                },
                advancedSize: null,
                startPoint: null,
                interval: null
            }
        },
        computed: {
            currentTimeString() {
                let seconds = this.timestamp;
                if(seconds >= this.part.maxValue) {
                    seconds = this.part.maxValue;
                }
                let minutes = this.getFullMinutes(seconds);
                seconds = seconds - (minutes*60);
                if(minutes < 10) {
                    minutes = '0' + minutes.toString();
                }
                if(seconds < 10) {
                    seconds = '0' + seconds.toString();
                }
                return minutes + ':' + seconds;
            },
            advancedTimeString() {
                let seconds = this.timestamp;
                if(seconds < this.part.maxValue) {
                    return null;
                }
                seconds = seconds - this.part.maxValue;
                let minutes = this.getFullMinutes(seconds);
                seconds = seconds - (minutes*60);
                if(minutes < 10) {
                    minutes = '0' + minutes.toString();
                }
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
            this.loadData();
            this.interval = setInterval(() => {
                this.secondInterval();
            }, 1000);
        },
        created() {
            var channel = PusherApp.subscribe('score-widget-' + this.screen.uuid);
            channel.bind('TimerStateChanged', this.changeState);
            channel.bind('TimerChangedTime', this.changeTime);
            channel.bind('AdvancedSizeChanged', this.changeAdvancedSize);
            channel.bind('WidgetPositionChanged', this.changePositionScore);
        },
        methods: {
            getFullMinutes(seconds){
                return (seconds - seconds % 60) / 60;
            },
            loadData() {
                this.part = this.widget.data.part;
                this.startPoint = this.widget.data.startPoint;
                this.state = this.widget.data.state;
                if(this.startPoint) {
                    let date = new Date();
                    let time = Math.floor(date.getTime()/1000);
                    console.log(time, this.startPoint);
                    this.timestamp = time - this.startPoint;
                }
                if(this.widget.data.advancedSize) {
                    this.advancedSize = this.widget.data.advancedSize;
                }
            },
            changeState(data) {
                if(data.timestamp !== undefined) {
                    this.timestamp = data.timestamp;
                }
                this.state = data.state;
            },
            changeTime(data) {
                if(data.widget_id != this.widget.id) {
                    return;
                }
                this.startPoint = data.startPoint;
                this.timestamp = data.timestamp;
            },
            changeAdvancedSize(data) {
                if(data.widget_id != this.widget.id) {
                    return;
                }
                this.advancedSize = data.advancedSize;
            },
            secondInterval() {
                if(this.state == 'pause') {
                    return;
                }
                this.timestamp++;
            },
            changePositionScore(data) {
                if(data.widget_id == this.widget.id) {
                    this.widget.data.position = data.position;
                }
            }
        }
    }
</script>
