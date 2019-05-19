<template>
    <div class="b_widgetDashboardWrap b_dqw" :class="{'is-loading':isLoading}">
        <div class="row">
            <div class="col-12">
                <button class="btn btn-primary" @click="squadEditor = true">Squad editor</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
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
        <div class="b_squadEditor" v-if="squadEditor">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                Squad editor
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6" v-for="curTeam in [teamHome, teamAway]" v-if="curTeam">
                                        <div class="form-group">
                                            <label>Team Home name</label>
                                            <input class="form-control" v-model="curTeam.title">
                                        </div>
                                        <div class="form-group">
                                            <label>Coach name</label>
                                            <input class="form-control" v-model="curTeam.coach">
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <h3>First squad</h3>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-dark" @click="addFirstPlayer(curTeam)">add player</button>
                                            </div>
                                        </div>
                                        <div>
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Number</th>
                                                    <th>Name</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(player, pIndex) in curTeam.main_players">
                                                        <td>
                                                            <input class="form-control" v-model="player.number">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" v-model="player.name">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger" @click="removeFirstPlayer(curTeam,pIndex)">remove</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <h3>Second squad</h3>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-dark" @click="addSecondPlayer(curTeam)">add player</button>
                                            </div>
                                        </div>
                                        <div>
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Number</th>
                                                    <th>Name</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(player, pIndex) in curTeam.second_players">
                                                        <td>
                                                            <input class="form-control" v-model="player.number">
                                                        </td>
                                                        <td>
                                                            <input class="form-control" v-model="player.name">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-danger" @click="removeSecondPlayer(curTeam,pIndex)">remove</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-light mr-1" @click="squadEditor = false">Close</button>
                                <button class="btn btn-primary mr-1" @click="saveSquad">Save</button>
                            </div>
                        </div>
                    </div>
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
            this.isLoading = false;
        },
        props: ['screen','widget'],
        data() {
            return {
                isLoading: true,
                squadEditor: false,
                setting: {
                    position: {
                        top: 0,
                        left: 0
                    }
                },
                teamHome: null,
                teamAway: null,
            }
        },
        methods: {
            addFirstPlayer(team) {
                team.main_players.push({
                    number: null,
                    name: null,
                });
            },
            removeFirstPlayer(team, index) {
                team.main_players.splice(index,1);
            },
            addSecondPlayer(team) {
                team.second_players.push({
                    number: null,
                    name: null,
                });},
            removeSecondPlayer(team, index) {
                team.second_players.splice(index,1);
            },
            saveSetting() {
                this.isLoading = true;
                let setting = this.setting;
                axios.post('/widget/squad/'+this.widget.id+'/saveSetting', {setting: setting}).then((resp) => {
                    this.widget.data = resp.data.widgetData;
                    this.refreshData();
                    this.isLoading = false;
                });
            },
            saveSquad() {
                this.isLoading = true;
                let squad = {
                    teamHome: this.teamHome,
                    teamAway: this.teamAway,
                };
                axios.post('/widget/squad/'+this.widget.id+'/saveSquad', {squad: squad}).then((resp) => {
                    this.widget.data = resp.data.widgetData;
                    this.refreshData();
                    this.isLoading = false;
                    this.squadEditor = false;
                });
            },
            refreshData() {
                this.teamHome = this.widget.data.teamHome;
                this.teamAway = this.widget.data.teamAway;
                this.setting.position = this.widget.data.position;
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
