
({
    className: ':template-layout-name',
    events: {
        // 'click input.toggle-metrics-settings': 'updateSettings',
        'click button.save-settings': 'updateSettings',
    },

    initialize: function (options) {

        this._super("initialize", [options]);

    },

    loadData: function (options) {

        var self = this;

        const url = app.api.buildURL('sample', 'settings', null, null)
        app.api.call('GET', url, null, {
            success: function (data) {
                self.data = data;
                self.data.dataFetched = true;

                self.render();

            },
            error: function (data) {
                self.data.dataFetched = false;
            }

        });

    },

    updateSettings: function (args) {
        var target = args['currentTarget'];

        var options = {
            enables:[],
            automatically:[],
            numbers:[],
        };


        const url = app.api.buildURL('sample', 'settings', null, null);
        app.api.call('create', url, options, {
            success: function (data) {
                //show message or animate
                SUGAR.App.alert.show('message-id', {
                    level: 'success',
                    messages: 'Saved Successfully',
                    autoClose: false
                });
            },

            error: function (data) {
                //show message or animate
                SUGAR.App.alert.show('message-id', {
                    level: 'error',
                    messages: 'Error',
                    autoClose: false
                });
            },
        });
    }


})
