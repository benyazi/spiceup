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
        created() {

        },
        mounted() {
            this.refreshData();
        },
        props: ['screen','widget'],
        data() {
            return {
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
                axios.post('/widget/score/'+this.widget.id+'/saveSetting', {setting: this.setting}).then((resp) => {

                    this.refreshData();
                });
            },
            refreshData() {
                this.teamHome = this.widget.data.team.home;
                this.teamAway = this.widget.data.team.away;
                this.scoreHome = this.widget.data.score.home;
                this.scoreAway = this.widget.data.score.away;
                this.teamHomeColor = this.widget.data.color.home;
                this.teamAwayColor = this.widget.data.color.away;
            },
            changeScore(team, type) {
                axios.get('/widget/score/'+this.widget.id+'/'+team+'/'+type).then((resp) => {
                    this.widget.data = resp.data.widgetData;
                    this.refreshData();
                });
            }
        }
    }
</script>
