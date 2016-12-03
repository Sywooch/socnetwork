yii.confirm = function ($message, $ok, $cancel)
{
    bootbox.confirm($message, function ($confirmed)
    {
        if ($confirmed) {
            return !$ok || $ok();
        } else {
            return !$cancel || $cancel();
        }
    });
};
yii.alert = function ($message)
{
    bootbox.alert($message, function ($ok)
    {
        return $ok;
    });
};
yii.prompt = function ($title, $callback, $default)
{
    bootbox.prompt({
        title: (($title !== null && $.trim($title) !== '') ? $title : '&nbsp'),
        value: (($default === null && $.trim($title) !== '') ? '' : $default),
        callback: function ($result)
        {
            if ($result !== null) {
                if (typeof $callback == 'function') {
                    $callback($.trim($result));
                }
            }
        }
    });
};
yii.allowAction = function ($e)
{
    var $message = $e.data('confirm');
    return $message === undefined || yii.confirm($message, $e);
};

/**
 * notification function
 * @param {string} message
 * @param {string} type
 * @param {array/int} $param - array as for params and int as for close timeout (seconds)
 * @returns {undefined}
 */
yii.mes = function (e, a, s)
{
    var $modal = ($('.modal.in').length > 0);
    var $topMargin = ($('body').data('notification-top-margin'));
    var n = "object" == typeof s ? s : null;
    "error" == a && (a = "danger"), "info" != a && "success" != a && "warning" != a && "danger" != a && (a = "info");
    var t = "number" == typeof s ? 1e3 * s : "success" === a ? 3e3 : 6e3, r = {replace: !0, type: a, placement: {from: "top", align: "center"}, offset: {y: ($modal ? 0 : ($topMargin != null ? $topMargin : 0))}, z_index: (!$modal ? 1029 : 1056), allow_dismiss: !0, mouse_over: "pause", delay: t, onShown: null, onClose: null, showProgressbar: !0, template: '<div data-notify="container" class="notify-message-container col-xs-12 col-sm-12 text-center alert alert-{0}" role="alert"><div class="mes-timeout-progress progress" data-notify="progressbar"><div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">\n</div></div><button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button><span data-notify="icon"></span><span class="title" data-notify="title">{1}</span><span class="text" data-notify="message">{2}</span><a href="{3}" target="{4}" data-notify="url"></a></div>'}, o = $.extend({}, r, null !== n ? n : {});
    o.replace === !0 && $('*[data-notify="container"]').remove(), $.notify({message: e}, o);
}

