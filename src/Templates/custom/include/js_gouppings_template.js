(function (app) {

    let jsGroupingHandler = {
        //1 register plugin
        registerPlugin: function (app) {


            app.plugins.register('Dummy', ['view'], {
                fieldsToValidate: ['phone_office', 'phone_work', 'phone_other', 'phone_mobile', 'phone_fax', 'mobilenumber_c', 'phone_alternate'],
                onAttach: function () {
                    this.on("init", function () {
                        this._doStaff();
                    });
                },
                _doStaff: function (fields, errors, callback) {

                }
            });
        },

        registerHandleBarHelper: function (app) {
            Handlebars.registerHelper('dropdownn', function (name, options, current) {

                var str = `<select name='${name}' style="width: 100%"><option></option>`;
                options.forEach((item, index) => {
                    var selected = (item.id == current) ? 'selected' : '';
                    var option = `<option value="${item.id}" ${selected} >${item.name}</option>`;
                    str += option;
                });

                str += '</select>';

                return str;
            });

        },

        afterSaveHandler: function (app) {

        },


        syncCompleteHandler: function (app) {

        },

        onRecordHandler: function (app) {

        },

        onListHandler: function (app) {

        }

    };


    app.events.on('app:init', function () {
        /**
         * Here you are possible
         * - load additional css : css-bootstrab-input-borders
         * - add fields validation logic : this.model.addValidationTask('name',callback)
         * - register handlebar helper
         * - enable clipboard library : app:sync:complete
         */
        jsGroupingHandler.registerPlugin(app)
        jsGroupingHandler.registerHandleBarHelper(app);
    });

    app.events.on("app:bean:after_save", function (data) {
        jsGroupingHandler.afterSaveHandler(app)
    });

    //Run callback when Sidecar metadata is fully initialized
    app.events.on('app:sync:complete', function () {

        jsGroupingHandler.syncCompleteHandler(app);

        //When a record layout is loaded...
        app.router.on('route:record', function (module) {
            jsGroupingHandler.onRecordHandler(app);
        });

        app.router.on('route:list', function (data) {
            jsGroupingHandler.onListHandler(app);
        });
    });

})(SUGAR.App);