$(document).ready(function() {
    $(".sidebar-open").click(function() {
        $(".sidebar-container").toggleClass("open");
        $(".content-container").toggleClass("open");
    });
    // Tooltip

    $('.copy-button').tooltip({
        trigger: 'click',
        placement: 'bottom'
    });

    function setTooltip(btn, message) {
        $(btn).tooltip('hide')
            .attr('data-original-title', message)
            .tooltip('show');
    }

    function hideTooltip(btn) {
        setTimeout(function() {
            $(btn).tooltip('hide');
        }, 1000);
    }

    // Clipboard

    var clipboard = new Clipboard('.copy-button');

    clipboard.on('success', function(e) {
        setTooltip(e.trigger, 'Copied!');
        hideTooltip(e.trigger);
    });

    clipboard.on('error', function(e) {
        setTooltip(e.trigger, 'Failed!');
        hideTooltip(e.trigger);
    });
    $.fn.editable.defaults.mode = 'inline';
    $('.editable').editable();
});
