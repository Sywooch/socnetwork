function showUserRolesModal($el) {
    var $data = $el.data();
    var $modal = $('#manage-user-roles-modal');
    $modal.modal('show');
    $('.add-remove-role-button', $modal).data('user-id', $data.userId);
    $('.user-name', $modal).text('"' + $data.userName + '"');
    $('.list-group-item.item', $modal).each(function () {
        if (jQuery.inArray($(this).data('id'), $data.assignments) !== -1) {
            $(this).addClass('active');
            $('.remove-role-button', $(this)).removeClass('hidden');
            $('.add-role-button', $(this)).addClass('hidden');
        } else {
            $(this).removeClass('active');
            $('.remove-role-button', $(this)).addClass('hidden');
            $('.add-role-button', $(this)).removeClass('hidden');
        }
    });
}

function filterUserRoleItems($el) {
    var $type = $el.data('type');
    var $container = $('*[data-item-list-type="' + $type + '"]');
    var $kw = $el.val();
    $('.list-group-item.item', $container).each(function () {
        var $text = $(this).data('id') + ' ';
        var $params = $(this).data('params');
        if (typeof $params === 'object' && $params.data !== undefined && $params.data.t !== undefined) {
            $.each($params.data.t, function ($key, $value) {
                if (typeof $value === 'object') {
                    $text += $value.title !== undefined ? ' ' + $value.title : '';
                    $text += $value.description !== undefined ? ' ' + $value.description : '';
                }
            });
        }
        var $t = $text.toLowerCase();
        var $textMatch = $t.indexOf($.trim($kw.toLowerCase())) >= 0 ? true : false;
        $textMatch ? $(this).removeClass('hidden') : $(this).addClass('hidden');
    });
}

function addRemoveUserAssignment($el) {
    var $data = $el.data();
    $.ajax({
        async: true,
        url: $data.action,
        type: 'post',
        data: {role: $data.id, user: $data.userId, operation: $data.type},
        error: function (xhr) {
            yii.mes(xhr.responseText, 'error');
        }
    }).done(function ($d) {
        var $dr = JSON.parse($d);
        yii.mes($dr.message, $dr.response);
        if ($dr.response === 'success') {
            $('.list-group-item.item[data-id="' + $data.id + '"]').toggleClass('active');
            $('.add-remove-role-button[data-id="' + $data.id + '"]').toggleClass('hidden');
            var $bt = $('.user-assignments-modal-button[data-user-id="' + $data.userId + '"]');
            var $c = $bt.data('assignments');
            var $roleListContainer = $('.user-assigned-roles[data-user-id="' + $data.userId + '"]');
            if ($data.type === 'add') {
                $c.push($data.id);
                var $elType = $roleListContainer.data('element');
                var $elementCssClass = $roleListContainer.data('element-class');
                $roleListContainer.append('<' + $elType + ' data-name="' + $data.id + '" class="' + $elementCssClass + '">' + $data.title + '</' + $elType + '>');
            } else {
                $('*[data-name="' + $data.id + '"]', $roleListContainer).remove();
                $c = jQuery.grep($c, function ($value) {
                    return $value != $data.id;
                });
            }
            $bt.data('assignments', $c);
        }
    });
}