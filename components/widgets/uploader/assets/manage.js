function fiBeforeSendEvent($container, $el, $l, $p, $o, $s, $id, $jqXHR, $settings) {
    return true;
}

function fiRemoveEvent($container, $el, $file) {
    var $selector = $file.size + '-' + $file.name;
    var $fileName = $('.jFiler-item[data-origin-file="' + $selector + '"]', $container).data('file-name');
    if (!$fileName) {
        yii.mes('error', 'error');
        return false;
    }
    $.ajax({
        async: true,
        type: 'post',
        data: {fiRemoveFileAjax: $fileName},
        error: function (xhr) {
            yii.mes(xhr.responseText, 'error', 15);
        }}).done(function ($r) {
        var $response = JSON.parse($r);
    });
    return true;
}
function fiSuccessEvent($container, $data, $textStatus, $jqXHR) {
    $data = JSON.parse($data);
    var $selector = $data.info.size + '-' + $data.info.name;
    var $el = $('.jFiler-item[data-origin-file="' + $selector + '"]', $container);
    $el.data('file-name', $data.name);
    $('.jFiler-jProgressBar', $el).addClass('fa fa-check').removeClass('jFiler-jProgressBar');
    $el.attr('data-file-name', $data.name);
    return true;
}


function fiBeforeSelectEvent($container, $files, $l, $p, $o, $s) {
    fiInputInfoIndicator($container);
}
function fiOnEmptyEvent($container, $p, $o, $s) {
    fiInputInfoIndicator($container);
}



function fiOnSelectEvent($container, $file) {
    fiInitThumbOfFile($container, $file);
    fiInputInfoIndicator($container, $file);
    return true;
}


function fiAfterShowEvent($container, $l, $p, $o, $s) {
    return true;
}

function fiInitThumbOfFile($container, $file) {
    var $extension = $file.name.substr(($file.name.lastIndexOf('.') + 1));
    var $ico = 'file';
    var $color = '#888';
    switch ($extension) {
        case 'pdf':
            $ico = 'file-pdf-o';
            $color = '#C30C08';
            break;
        case 'txt':
            $ico = 'file-text';
            break;
        case 'doc':
        case 'pages':
        case 'docx':
            $color = 'rgb(20, 151, 175)';
            $ico = 'file-word-o';
            break;
        case 'wma':
        case 'mp3':
            $color = '#F4805C';
            $ico = 'file-audio-o';
            break;
        case 'mov':
        case 'wmv':
        case 'vob':
        case 'flv':
        case 'webm':
        case '3gp':
        case 'mp4':
        case 'mp4p':
        case 'mp4v':
        case 'avi':
        case 'mkv':
            $color = 'rgb(129, 93, 183)';
            $ico = 'file-video-o';
            break;
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            $color = 'rgb(79, 175, 75)';
            $ico = 'file-image-o';
        case 'rar':
        case 'zip':
            $color = '#D09C36';
            $ico = 'file-archive-o';
            break;

    }
    var $item = $('.jFiler-item[data-origin-file="' + $file.size + '-' + $file.name + '"]');
    $('.jFiler-icon-file', $item).addClass('fa fa-' + $ico).css('color', $color + '!important').html('');
}

function fiInputInfoIndicator($container, $file) {
    var $el = $('.fiInputInfoIndicator', $container);
    if ($el.length > 0 && $file != undefined) {
        var $data = $el.data();
        var $counter = $('.jFiler-items-list .jFiler-item', $container).length;
        if ($counter === 1) {
            $el.val($file.name);
        } else {
            $el.val($data.text + ' : ' + $counter);
        }
        $el.data('counter', $counter);
    } else {
        $el.val('');
    }
}