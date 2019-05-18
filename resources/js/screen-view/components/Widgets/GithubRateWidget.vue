<template>
    <div class="b_widgetWrap b_gp" :style="{top:positionTop,left:positionLeft,display:(widget.is_active)?'block':'none'}">
        <div class="">
            <div class="gp_title">Рейтинг коммитеров</div>
            <div class="gp_item" v-for="item in rates">
                <div class="gp_item_name"><strong>{{item.place}}</strong> | <strong>{{item.sender}}</strong> | {{item.count}}</div>
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
            this.rates = [];
        },
        props: ['screen','widget'],
        data() {
            return {
                rates: []
            }
        },
        methods: {
            newPush(data) {
                if(data.rates) {
                    this.rates = data.rates;
                }
            },
            changeActivate(data) {
                if(data.widget_id != this.widget.id) {
                    return;
                }
                this.widget.is_active = data.value;
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
