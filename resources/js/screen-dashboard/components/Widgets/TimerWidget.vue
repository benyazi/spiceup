<template>
    <div class="b_widgetDashboardWrap b_dtw">
        <div class="row">
            <div class="col-12">
                Время: {{currentTimeString}}
            </div>
            <div class="col-12">
                Дополнительное время(+{{advancedSize}}): {{advancedTimeString}}
            </div>
            <div class="col-12">
                <button class="btn-primary" @click="changeState()">
                    <template v-if="state=='pause'">
                        Старт
                    </template>
                    <template v-if="state=='play'">
                        Пауза
                    </template>
                </button>
            </div>
            <div class="col-12">
                <label>Change current time</label>
                <div class="input-group">
                    <input class="form-group" v-model="newTime">
                    <button class="btn btn-primary" @click="updateTime()">Update</button>
                </div>
            </div>
            <div class="col-12">
                <label>Update advanced time value</label>
                <div class="input-group">
                    <input class="form-group" v-model="advancedSize">
                    <button class="btn btn-primary" @click="updateAdvancedTime()">Update</button>
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
                interval: null,
                setting: {
                    position: {
                        top: 0,
                        left: 0
                    }
                },
                newTime: null,
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
        },
        mounted() {
            this.currentWidgetData = this.widget;
            this.loadData();
            this.interval = setInterval(() => {
                this.secondInterval();
            }, 1000);
        },
        created() {

        },
        methods: {
            updateTime() {},
            saveSetting() {},
            updateAdvancedTime() {},
            changeState() {
                let newState = (this.state === 'pause')?'play':'pause';
                let url = '/widget/timer/'+this.widget.id+'/'+newState;
                this.isLoading = true;
                axios.get(url).then((resp) => {
                    this.state = resp.data.state;
                    this.isLoading = false;
                });
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
                this.setting.position = this.widget.data.position;
            },
            getFullMinutes(seconds){
                return (seconds - seconds % 60) / 60;
            },
            secondInterval() {
                if(this.state == 'pause') {
                    return;
                }
                this.timestamp++;
            }
        }
    }
</script>
