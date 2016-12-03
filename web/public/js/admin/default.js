/**
 * 
 * @returns {defaultSettings}
 */
Settings = new function ()
{
    /**
     * 
     * @returns {Settings.defaultSettings#Update}
     */
    this.Update = new function ()
    {
        /**
         * restore default settings buttons function /views/settings/update
         * @param {type} $el
         * @returns {undefined}
         */
        this.restoreDefaultSettings = function ($el)
        {
            var $data = $el.data();
            yii.confirm($data.confirmMessage, function ()
            {
                $.ajax({
                    async: true,
                    type: 'post',
                    url: $el.data('url'),
                    data: {type: 'restoreDefaults'},
                    error: function (xhr)
                    {
                        yii.mes(xhr.responseText, 'error');
                    }
                }).done(function ($r)
                {
                    var $response = JSON.parse($r);
                    if ($response.result == 'success') {
                        Common.refresh();
                    }
                });
            });
        };
    };
};