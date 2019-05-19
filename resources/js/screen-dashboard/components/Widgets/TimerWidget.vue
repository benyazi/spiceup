<template>
    <div class="b_widgetDashboardWrap b_dtw">
        <div class="row">
            <div class="col-12 mb-1">
                Время: {{currentTimeString}}
            </div>
            <div class="col-12 mb-1">
                Дополнительное время(+{{advancedSize}}): {{advancedTimeString}}
            </div>
            <div class="col-12 mb-1">
                <button class="btn btn-primary" @click="changeState()">
                    <template v-if="state=='pause'">
                        Старт
                    </template>
                    <template v-if="state=='play'">
                        Пауза
                    </template>
                </button>
            </div>
            <div class="col-12 mb-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Change current time</span>
                    </div>
                    <input type="text" class="form-control" placeholder="10:43" v-model="newTime">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" @click="updateTime()">Update</button>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Change advanced time value</span>
                    </div>
                    <input type="text" class="form-control" placeholder="5" v-model="advancedSize">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" @click="updateAdvancedTime()">Update</button>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Change part</span>
                    </div>
                    <input type="text" class="form-control" placeholder="10:00" v-model="newPartMax">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" @click="updatePart()">Update</button>
                    </div>
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
        <div class="row">
            <div class="col-12">
                <button class="btn btn-primary w-100" @click="activate" :class="{'btn-primary':!widget.is_active,'btn-danger':widget.is_active}">
                    <template v-if="widget.is_active">
                        Отключить
                    </template>
                    <template v-else>
                        Включить
                    </template>
                </button>
            </div>
        </div>
        <div class="curtain" v-if="isLoading"></div>
    </div>
</template>

<script>
    export default {
        props: ['screen', 'widget'],
        data() {
            return {
                isLoading: false,
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
                newPartMax: null,
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
            updateTime() {
                let timeData = this.newTime.split(':');
                let seconds = parseInt(timeData[0]*60) + parseInt(timeData[1]);
                let url = '/widget/timer/time/'+this.widget.id+'/'+seconds;
                this.isLoading = true;
                axios.get(url).then((resp) => {
                    this.timestamp = resp.data.timestamp;
                    this.startPoint = resp.data.startPoint;
                    this.isLoading = false;
                });
            },
            saveSetting() {
                this.isLoading = true;
                axios.post('/widget/timer/'+this.widget.id+'/saveSetting', {setting: this.setting}).then((resp) => {
                    this.isLoading = false;
                });
            },
            activate() {
                this.isLoading = true;
                let value = !this.widget.is_active;
                axios.get('/widget/activate/'+this.widget.id+'/' + value).then((resp) => {
                    this.isLoading = false;
                    this.widget.is_active = value;
                });
            },
            updateAdvancedTime() {
                let at = this.advancedSize;
                if(at == '' || at == 0) {
                    at = 'clear';
                }
                let url = '/widget/timer/advanced_size/'+this.widget.id+'/'+at;
                this.isLoading = true;
                axios.get(url).then((resp) => {
                    this.isLoading = false;
                });
            },
            updatePart() {
                this.isLoading = true;
                let val = this.newPartMax.split(':');
                this.part.maxValue = parseInt(val[0]*60) + parseInt(val[1]);
                let url = '/widget/timer/part/'+this.widget.id+'/'+this.part.maxValue;
                this.isLoading = true;
                axios.get(url).then((resp) => {
                    this.isLoading = false;
                });
            },
            changeState() {
                let newState = (this.state === 'pause')?'play':'pause';
                let url = '/widget/timer/state/'+this.widget.id+'/'+newState;
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
                let min = this.getFullMinutes(this.part.maxValue);
                this.newPartMax = min + ':' + (this.part.maxValue - (min*60));

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
