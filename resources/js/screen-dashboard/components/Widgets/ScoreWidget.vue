<template>
    <div class="b_widgetDashboardWrap b_dsw" :class="{'is-loading':isLoading}">
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
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Team Home name</span>
                    </div>
                    <input type="text" class="form-control" placeholder="DIN" v-model="teamHome">
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Team Away name</span>
                    </div>
                    <input type="text" class="form-control" placeholder="DIN" v-model="teamAway">
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Team Home color</span>
                    </div>
                    <input type="text" class="form-control" placeholder="red or #FFFFFF" v-model="teamHomeColor">
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Team Away color</span>
                    </div>
                    <input type="text" class="form-control" placeholder="red or #FFFFFF" v-model="teamAwayColor">
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Position Top</span>
                    </div>
                    <input type="text" class="form-control" placeholder="20" v-model="setting.position.top">
                    <div class="input-group-append">
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Position Left</span>
                    </div>
                    <input type="text" class="form-control" placeholder="20" v-model="setting.position.left">
                    <div class="input-group-append">
                        <span class="input-group-text">px</span>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary" @click="saveSetting()">Save setting</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button class="btn btn-primary" @click="activate">
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
        created() {

        },
        mounted() {
            this.refreshData();
            this.isLoading = false;
        },
        props: ['screen','widget'],
        data() {
            return {
                isLoading: true,
                setting: {
                    position: {
                        top: 0,
                        left: 0
                    }
                },
                tLogo: '/images/PFL_logo.png',
                teamHome: 'TMH',
                teamHomeColor: '#2518c6',
                teamAway: 'TMH',
                teamAwayColor: '#8d0707',
                scoreHome: 0,
                scoreAway: 0
            }
        },
        methods: {
            saveSetting() {
                this.isLoading = true;
                let setting = this.setting;
                setting.teams = {
                    team: {
                        home: this.teamHome,
                        away: this.teamAway,
                    },
                    color: {
                        home: this.teamHomeColor,
                        away: this.teamAwayColor,
                    }
                };
                axios.post('/widget/score/'+this.widget.id+'/saveSetting', {setting: setting}).then((resp) => {
                    this.widget.data = resp.data.widgetData;
                    this.refreshData();
                    this.isLoading = false;
                });
            },
            refreshData() {
                this.teamHome = this.widget.data.team.home;
                this.teamAway = this.widget.data.team.away;
                this.scoreHome = this.widget.data.score.home;
                this.scoreAway = this.widget.data.score.away;
                this.teamHomeColor = this.widget.data.color.home;
                this.teamAwayColor = this.widget.data.color.away;

                this.setting.position = this.widget.data.position;
            },
            changeScore(team, type) {
                this.isLoading = true;
                axios.get('/widget/score/'+this.widget.id+'/'+team+'/'+type).then((resp) => {
                    this.widget.data = resp.data.widgetData;
                    this.refreshData();
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
        }
    }
</script>
