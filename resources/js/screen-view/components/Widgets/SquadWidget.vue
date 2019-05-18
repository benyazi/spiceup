<template>
    <div class="b_widgetWrap b_sqw" :style="{top:positionTop,left:positionLeft,display:(widget.is_active)?'block':'none'}">
        <div class="b_sq_teams">
            <div class="b_sq_team" v-if="curTeam" v-for="(curTeam, index) in [teamHome, teamAway]" :class="{'b_sq_team--second':index}">
                <div class="sq_Name">
                    {{curTeam.title}}
                </div>
                <div class="sq_list sq_list--first">
                    <div class="sq_subtitle">Основной состав</div>
                    <div class="sq_list_item" v-for="player in curTeam.main_players">
                        <template v-if="index">
                            <div class="sq_list_item_number">
                                {{player.number}}
                            </div>
                            <div class="sq_list_item_name">
                                {{player.name}}
                            </div>
                        </template>
                        <template v-else>
                            <div class="sq_list_item_name">
                                {{player.name}}
                            </div>
                            <div class="sq_list_item_number">
                                {{player.number}}
                            </div>
                        </template>
                    </div>
                </div>
                <div class="sq_list sq_list--second">
                    <div class="sq_subtitle">Запасные</div>
                    <div class="sq_list_item" v-for="player in curTeam.second_players">
                        <template v-if="index">
                            <div class="sq_list_item_number">
                                {{player.number}}
                            </div>
                            <div class="sq_list_item_name">
                                {{player.name}}
                            </div>
                        </template>
                        <template v-else>
                            <div class="sq_list_item_name">
                                {{player.name}}
                            </div>
                            <div class="sq_list_item_number">
                                {{player.number}}
                            </div>
                        </template>
                    </div>
                </div>
                <div class="sq_Coach">
                    <div class="sq_subtitle">Тренер</div>
                    <div>
                        {{curTeam.coach}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            var channel = PusherApp.subscribe('score-widget-' + this.screen.uuid);
            channel.bind('WidgetPositionChanged', this.changePositionScore);
            channel.bind('UpdateSquad', this.UpdateSquad);
        },
        computed: {
            positionTop() {
                if(this.widget.data.position) {
                    return this.widget.data.position.top + 'px';
                }
                return 0;
            },
            positionLeft() {
                if(this.widget.data.position) {
                    return this.widget.data.position.left + 'px';
                }
                return 0;
            },
        },
        mounted() {
            this.teamHome = this.widget.data.teamHome;
            this.teamAway = this.widget.data.teamAway;
        },
        props: ['screen','widget'],
        data() {
            return {
                teamHome: null,
                teamAway: null,
            }
        },
        methods: {
            changeActivate(data) {
                if(data.widget_id != this.widget.id) {
                    return;
                }
                this.widget.is_active = data.value;
            },
            UpdateSquad(data) {
                if(data.widget_id != this.widget.id) {
                    return;
                }
                this.teamHome = data.squad.teamHome;
                this.teamAway = data.squad.teamAway;
            },
            changePositionScore(data) {
                if(data.widget_id != this.widget.id) {
                    return;
                }
                this.widget.data.position = data.position;
            }
        }
    }
</script>
