function addIcon($el, $data) {
    var $input = $($data.inputSelector);
    var $allButtons = $($data.commonButtonSelector);
    var $curentIcon = $($data.curentIconContainer);
    $input.val($data.icon);
    yii.mes($input.val());
    $allButtons.removeClass('active');
    $el.addClass('active');
    $curentIcon.html('<i class="fa fa-' + $data.icon + '"></i>');
}

function filterIcons($el, $data) {
    var $buttons = $($data.commonButtonSelector);
    $buttons.each(function () {
        var $bData = $(this).data();
        var $t = $bData.icon.toLowerCase();
        var $textMatch = $t.indexOf($.trim($el.val().toLowerCase())) >= 0 ? true : false;
        $textMatch ? $(this).removeClass('hidden') : $(this).addClass('hidden');
    });
}