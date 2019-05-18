<template>
    <div class="b_widgetWrap b_gp" :style="{top:positionTop,left:positionLeft,display:(widget.is_active)?'block':'none'}">
        <div class="">
            <div class="gp_title">Свежие пуши</div>
            <div class="gp_item" v-for="item in pushes">
                <div class="gp_item_name"><strong>{{item.sender}}</strong> -> <strong>{{item.project}}</strong></div>
                <div class="gp_item_message">
                    {{item.message}}
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
            channel.bind('WidgetActivateChanged', this.changeActivate);
            var channelGithub = PusherApp.subscribe('scope-github');
            channelGithub.bind('NewPush', this.newPush);
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
            this.pushes = [];
        },
        props: ['screen','widget'],
        data() {
            return {
                pushes: []
            }
        },
        methods: {
            newPush(data) {
                this.pushes.push(data.pushItem);
            },
            changeActivate(data) {
                if(data.widget_id != this.widget.id) {
                    return;
                }
                this.widget.is_active = data.value;
            },
            updateTeamData(data) {
                if(data.widget_id != this.widget.id) {
                    return;
                }
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
                if(data.widget_id != this.widget.id) {
                    return;
                }
                if(data.team == 'home') {
                    this.scoreHome = data.score;
                } else {
                    this.scoreAway = data.score;
                }
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
