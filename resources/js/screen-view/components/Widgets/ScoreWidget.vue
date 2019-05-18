<template>
    <div class="b_widgetWrap b_sw" :style="{top:positionTop,left:positionLeft}">
        <div class="b_sw_logo">
            <img :src="tLogo">
        </div>
        <div class="b_sw_teams">
            <div class="b_sw_team b_sw_team_home">
                {{teamHome}}
            </div>
            <div class="b_sw_team_color" :style="{'background-color':teamHomeColor}">&nbsp;</div>
            <div class="b_sw_score">
                {{scoreHome}}:{{scoreAway}}
            </div>
            <div class="b_sw_team_color" :style="{'background-color':teamAwayColor}">&nbsp;</div>
            <div class="b_sw_team b_sw_team_away">
                {{teamAway}}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            // Echo.channel('my-channel').listen('my-event', function (data) {
            //     alert(JSON.stringify(data));
            // });
            var channel = PusherApp.subscribe('score-widget-' + this.screen.uuid);
            channel.bind('ScoreChanged', this.changeScore);
            channel.bind('ScorePositionChanged', this.changePositionScore);
            channel.bind('UpdateTeamData', this.updateTeamData);
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
            this.teamHome = this.widget.data.team.home;
            this.teamAway = this.widget.data.team.away;
            this.scoreHome = this.widget.data.score.home;
            this.scoreAway = this.widget.data.score.away;
            this.teamHomeColor = this.widget.data.color.home;
            this.teamAwayColor = this.widget.data.color.away;
        },
        props: ['screen','widget'],
        data() {
            return {
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
            updateTeamData(data) {
                if(data.data.team) {
                    this.teamHome = data.data.team.home;
                    this.teamAway = data.data.team.away;
                }
                if(data.data.color) {
                    this.teamHomeColor = data.data.color.home;
                    this.teamAwayColor = data.data.color.away;
                }
            },
            changeScore(data) {
                if(data.team == 'home') {
                    this.scoreHome = data.score;
                } else {
                    this.scoreAway = data.score;
                }
            },
            changePositionScore(data) {
                this.widget.data.position = data.position;
            }
        }
    }
</script>
