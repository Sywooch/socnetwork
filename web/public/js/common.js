/**
 * common js functions
 * @type Common.commonCommon
 */
var Common = new function ()
{
    this.tmp = {};
    /**
     * return values of checked ccheckboxes
     * @param {JQuerySelector} $selector
     * @returns {Array|colectCheckedValues.$ar}
     */
    this.colectCheckedValues = function ($selector)
    {
        var $ar = [];
        $($selector).each(function ()
        {
            if ($(this).is(':checked')) {
                $ar.push($(this).val());
            }
        });
        return $ar;
    };

    /**
     * @param {string} $gridViewCssClass
     * @param {string} $url
     * @param {string} $confirm
     * @param {array} $ids [1,2,3...]
     * @returns {Boolean}
     */
    this.deleteRecordsFromArray = function ($gridViewCssClass, $url, $confirm, $ids, $callback)
    {
        $ids = $ids === undefined ? this.colectCheckedValues($gridViewCssClass) : $ids;
        if ($ids.length === 0)
            return false;
        yii.confirm($confirm.replace(' ?', ' (' + $ids.length + ')' + ' ?'), function ()
        {
            $.ajax({
                async: true,
                url: $url,
                type: 'post',
                data: {ids: $ids},
                error: function (xhr)
                {
                    yii.mes(xhr.responseText, 'error');
                }
            }).done(function ($data)
            {
                var $dr = JSON.parse($data);
                yii.mes($dr['message'], $dr['type']);
                if ($dr['type'] == 'success') {
                    if (typeof $callback == 'function') {
                        return $callback();
                    } else {
                        if (Common.pjaxData.id != undefined) {
                            $.pjax.reload({
                                container: "#" + Common.pjaxData['id'],
                                timeout: Common.pjaxData['timeout']
                            });
                        }
                    }
                }
            });
        });
    }


    /**
     * check if string contains string (no case sensitive)
     * @param {string} $text
     * @param {string} $needle
     * @returns {Boolean}
     */
    this.stringContains = function ($text, $needle)
    {
        var $t = $text.toLowerCase();
        return $t.indexOf($.trim($needle.toLowerCase())) >= 0 ? true : false;
    };

    /**
     * redirects to url
     * @param {string} $url
     * @returns {undefined}
     */
    this.redirect = function ($url)
    {
        if (Common.pjaxData.id != undefined) {
            return this.pjaxRedirect($url);
        } else {
            return location.href = $url;
        }

    };

    /**
     * refresh page
     * @returns {undefined}
     */
    this.refresh = function ()
    {
        if (Common.pjaxData.id != undefined) {
            return this.pjaxRedirect(null);
        } else {
            return location.reload();
        }
    };

    this.pjaxData = {};
    /**
     * redirect pjax
     * @param {string} $url
     * @returns {undefined}
     */
    this.pjaxRedirect = function ($url)
    {
        if (Common.pjaxData.id != undefined) {
            var $data = Common.pjaxData;
            $data['url'] = $url;
            $.pjax($data);
        }
    };
    /**
     * init pjax page if it is set
     * @returns {undefined}
     */
    this.initPjaxPage = function ()
    {
        if ($('.pjax-app').length > 0) {
            this.pjaxData['id'] = $('.pjax-app').attr('id');
            this.pjaxData['container'] = '#' + this.pjaxData['id'];
            $.each($('.pjax-app').data(), function ($k, $v)
            {
                Common.pjaxData[$k] = $v;
            });
            var $pjaxContainer = $(this.pjaxData['container']);
            $pjaxContainer.on('pjax:start', function ($data, $status, $xhr, $options)
            {
                $('.js-pjax-loading-progress,.pjax-loading').removeClass('hidden');
            });
            $pjaxContainer.on('pjax:success', function ($data, $status, $xhr, $options)
            {
                //console.log($options.getAllResponseHeaders());
                $('.js-pjax-loading-progress,.pjax-loading').addClass('hidden');
                $('.test-pjax-status').text('PJAX').removeClass('text-danger').addClass('text-info');
            });
            $pjaxContainer.on('pjax:error', function ($xhr, $textStatus, $error, $options)
            {
                if ($textStatus.data != undefined && $textStatus.responseText != undefined) {
                    yii.mes($textStatus.data + '<br/>' + $textStatus.responseText, 'error');
                } else {
                    console.log('pjax error:');
                    console.log($options);
                    console.log($xhr);
                    console.log($textStatus);
                    console.log($error);
                }
                $('.js-pjax-loading-progress').addClass('hidden');
            });
        }
    };
};
Common.initPjaxPage();