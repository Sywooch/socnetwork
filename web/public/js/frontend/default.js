/**
 * 
 * @type Friends.defaultFriends
 */
var Friends = new function ()
{
    this.inviteToFriends = function ($el)
    {
        yii.confirm($el.data('confirm-message'), function ()
        {
            return Friends.request($el);
        });
    };

    /**
     * delete friend
     * @param {type} $el
     * @returns {undefined}
     */
    this.delete = function ($el)
    {
        yii.confirm($el.data('confirm-message'), function ()
        {
            return Friends.request($el);
        });
    };

    /**
     * delete friend
     * @param {type} $el
     * @returns {undefined}
     */
    this.accept = function ($el)
    {
        yii.confirm($el.data('confirm-message'), function ()
        {
            return Friends.request($el);
        });
    };

    /**
     * delete friend
     * @param {type} $el
     * @returns {undefined}
     */
    this.reject = function ($el)
    {
        yii.confirm($el.data('confirm-message'), function ()
        {
            return Friends.request($el);
        });
    };

    /**
     * @param {type} $url
     * @returns {undefined}
     */
    this.request = function ($el)
    {
        $el.button('loading');
        $.ajax({
            type: 'POST',
            url: $el.data('url'),
            async: true,
            error: function (xhr)
            {
                $el.button('reset');
                yii.mes(xhr.responseText, 'error');
            },
        }).done(function ($data)
        {
            $el.button('reset');
            $.pjax.reload('#pjax-page-container');
            return $data;
        });
    };
};