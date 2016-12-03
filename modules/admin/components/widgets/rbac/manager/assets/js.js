$(function ()
{
    checkRoleChilds();
    $('.rbac-filter-section').trigger('change');
});


function showRbacModalForm($name, $type, $data)
{
    var $modal = $('#rbac-modal-form');
    $('input,textarea', $modal).val('');
    $modal.modal('show');
    setRbacModalFormValues($('form', $modal), $name, $type, $data);
}

function setRbacModalFormValues($form, $name, $type, $data)
{
    $('input[name="operation"]').val($name === null ? 'add' : 'update');
    $('input[name="name"]').val($name);
    $('input[name="id"]').val($name);
    $('input[name="type"]').val($type);
    if ($data != undefined && $data != null) {
        if ($data != undefined && typeof $data == 'object') {
            $.each($data, function ($key, $value)
            {
                setRbacModalFormData($key, $value, $form, $key);
            });
        }
    }
}

function setRbacModalFormData($key, $value, $form, $name)
{
    if (typeof $value == 'object') {
        $.each($value, function ($key, $value)
        {
            setRbacModalFormData($key, $value, $form, ($name + '[' + $key + ']'));
        });
    } else {
        var $input = $('*[name="' + $name + '"]');
        if ($input.length > 0)
            $input.val($value);
    }
}

function sendRbacModalFromData($bt)
{
    var $selector = $bt.data('selector');
    var $form = $($selector);
    var $data = $form.serialize();
    $.ajax({
        async: true,
        url: $bt.data('url'),
        type: 'post',
        data: {data: $data, type: 'sendRbacModalFromData'},
        error: function (xhr)
        {
            yii.mes(xhr.responseText, 'error');
        }
    }).done(function ($result)
    {
        var $dr = JSON.parse($result);

        if ($dr.response == 'success') {
            $('#rbac-modal-form').modal('hide');
            var $container = $('*[data-item-container="' + $dr.name + '"]');
            if ($container.length > 0) {
                $container.html($dr.result);
            } else {
                $('*[data-item-list-type="' + $dr.type + '"] .rbac-items-container').prepend($dr.result);
            }
            checkRoleChilds();
        }
        yii.mes($dr.message, $dr.response);
    });
}

function deleteRbacItem($el, $name, $type)
{
    yii.confirm($el.attr('data-confirm-message'), function ()
    {
        $.ajax({
            async: true,
            type: 'post',
            data: {operation: 'deleteItem', name: $name, type: $type},
            error: function (xhr)
            {
                yii.mes(xhr.responseText, 'error');
            }
        }).done(function ($result)
        {
            var $dr = JSON.parse($result);
            if ($dr.response == 'success') {
                $('*[data-item-container="' + $name + '"]').remove();
                checkRoleChilds();
            }
            yii.mes($dr.message, $dr.response);
        });
    });
}

function filterRbacItems($el, $checkBySection)
{
    var $type = $el.data('type');
    var $container = $('*[data-item-list-type="' + $type + '"]');
    var $kw = $el.val();

    if ($checkBySection !== undefined) {
        $kw = $('.rbac-filter-text-input', $container).val();
        var $section = $('.rbac-filter-section', $container).val();
    }

    $('.list-group-item.item', $container).each(function ()
    {
        var $text = $(this).data('id') + ' ';
        var $params = $(this).data('params');
        if (typeof $params === 'object' && $params.data !== undefined && $params.data.t !== undefined) {
            $.each($params.data.t, function ($key, $value)
            {
                if (typeof $value === 'object') {
                    $text += $value.title !== undefined ? ' ' + $value.title : '';
                    $text += $value.description !== undefined ? ' ' + $value.description : '';
                }
            });
        }
        var $textMatch = Common.stringContains($text, $kw);
        if ($checkBySection !== undefined) {
            var $sectionMatch = Common.stringContains($text, $section);
            ($textMatch && $sectionMatch) ? $(this).removeClass('hidden') : $(this).addClass('hidden');
        } else {
            $textMatch ? $(this).removeClass('hidden') : $(this).addClass('hidden');
        }
    });
}

function toggleRole($el)
{
    if ($el.hasClass('role')) {
        $('.list-group-item').removeClass('active');
        $el.toggleClass('active');
        checkRoleChilds();
    }
}

function checkRoleChilds()
{
    var $role = $('.list-group-item.role.active');
    var $ids = null;
    if ($role.length == 0) {
        $role = $('.list-group-item.role:first').trigger('click');
    }
    if ($role.length > 0) {
        var $ids = $role.data('children');
    }
    $('.list-group-item.permission').each(function ()
    {
        var $addBtn = $('.add-remove-child-button.add-child-button', $(this));
        var $removeBtn = $('.add-remove-child-button.remove-child-button', $(this));
        $addBtn.data('item', $role.data('id'));
        $removeBtn.data('item', $role.data('id'));
        $('.role-title', $(this)).html('"' + $.trim(('.list-group-item-heading', $role).data('title')) + '"');
        if ($ids !== null && jQuery.inArray($(this).data('id'), $ids) !== -1) {
            $removeBtn.removeClass('hidden');
            $addBtn.addClass('hidden');
            $(this).addClass('active');
        } else {
            $removeBtn.addClass('hidden');
            $addBtn.removeClass('hidden');
            $(this).removeClass('active');
        }
    });
}

function addRemoveChildToItem($el)
{
    var $child = $el.data('id');
    var $item = $el.data('item');
    var $type = $el.data('type');
    $el.button('loading');
    $.ajax({
        async: true,
        url: $el.data('url'),
        type: 'post',
        data: {operation: 'addRemoveChildToItem', child: $child, item: $item, type: $type},
        error: function (xhr)
        {
            yii.mes(xhr.responseText, 'error');
            $el.button('reset');
        }
    }).done(function ($result)
    {
        var $dr = JSON.parse($result);
        if ($dr.response === 'success') {
            var $i = $('.list-group-item.item[data-id="' + $item + '"]');
            var $c = $i.data('children');
            if ($type === 'add') {
                $c.push($child);
            } else {
                $c = jQuery.grep($c, function ($value)
                {
                    return $value != $child;
                });
            }
            $i.data('children', $c);
            checkRoleChilds();
            $el.button('reset');
        }
        yii.mes($dr.message, $dr.response);
    });
}