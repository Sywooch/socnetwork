var MenuTreeWidget = new function ()
{
    /**
     * initialize js tree
     * @returns {undefined}
     */
    this.initTree = function ()
    {
        if (Common.tmp.initMenuTreeWidget) {
            return;
        }
        Common.tmp.initMenuTreeWidget = true;
        $(document).on('dnd_stop.vakata', function ($e, $data)
        {
            setTimeout(function ()
            {
                var $tree = $('#jsTreeMenuWidget').jstree();
                var $element = $data.data.obj;
                var $parent = $tree.get_parent($data.data.obj);
                $tree.open_node($parent);
                var $children = $tree.get_children_dom($parent);
                var $items = {};
                $children.each(function ($index)
                {
                    var $d = $(this).data();
                    $(this).addClass('');
                    $items[$d.id] = {
                        order: $index,
                        parent: ($parent == 'root' ? 0 : $parent)
                    };
                });
                $.ajax({
                    async: true,
                    url: $element.data().updateUrl,
                    type: 'post',
                    data: {items: $items, type: 'updatePositionsAndParents'},
                    error: function (xhr)
                    {
                        yii.mes(xhr.responseText, 'error');
                    }
                }).done(function ($r)
                {
                    var $response = JSON.parse($r);
                    yii.mes($response.message, $response.type);
                });
            }, 200);
        });
    };

    /**
     * reload js tree
     * @param {type} $el
     * @param {type} $data
     * @returns {undefined}
     */
    this.reloadJsTreeByType = function ($el, $data)
    {
        var $id = $data.id;
        $('#jsTreeMenuWidgetForm').jstree(true).settings.core.data = {
            dataType: 'json',
            url: $data.url + ($id ? '&' : '?') + 'type=' + $el.val() + '&operation=treeArray',
        };
        $('#jsTreeMenuWidgetForm').jstree(true).refresh();
    };
};


MenuTreeWidget.initTree();