<template>
    <div class="b_widgetDashboardWrap b_dsw" :class="{'is-loading':isLoading}">
        <div class="row">
            <div class="col-12">
                <h3>Github Rate</h3>
            </div>
        </div>
        <div class="row mb-1">
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
                }
            }
        },
        methods: {
            saveSetting() {
                this.isLoading = true;
                let setting = this.setting;
                axios.post('/widget/setting/'+this.widget.id, {setting: setting}).then((resp) => {
                    this.widget.data = resp.data.widgetData;
                    this.refreshData();
                    this.isLoading = false;
                });
            },
            refreshData() {
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
