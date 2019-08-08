/* ------- Init Perfect Scrollbar ------- */
var $ps = $('#perfectScroll');
if ($ps.length) {
    new PerfectScrollbar('#perfectScroll');
}
new PerfectScrollbar('#pScrollerMenu');

/* ------- Init Current Nav ------- */
var $curMenu = $('#' + GetNav);
if (!$curMenu.is(':visible')) {
    $curMenu.parent().prev('a').trigger('click');
}
$curMenu.addClass('active');

initDatatables('#voucher-table', 'asc');


function initDatatables(selector, sortOrder) {
    var $table = $(selector);
    if ($table.length) {
        $table.DataTable({
            "language": {
                "sEmptyTable": "Keine Daten in der Tabelle vorhanden",
                "sInfo": "_START_ bis _END_ von _TOTAL_ Einträgen",
                "sInfoEmpty": "0 bis 0 von 0 Eintrügen",
                "sInfoFiltered": "(gefiltert von _MAX_ Einträgen)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ Einträge anzeigen",
                "sLoadingRecords": "Wird geladen...",
                "sProcessing": "Bitte warten...",
                "sSearch": "Suchen",
                "sZeroRecords": "Keine Einträge vorhanden.",
                "oPaginate": {
                    "sFirst": "Erste",
                    "sPrevious": "Zurück",
                    "sNext": "Nächste",
                    "sLast": "Letzte"
                },
                "oAria": {
                    "sSortAscending": ": aktivieren, um Spalte aufsteigend zu sortieren",
                    "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                }
            },
            'order': [[0, sortOrder]]
        });
    }
}

$('#voucher-table').on('click','.js-toggle-use', function (e) {

    e.preventDefault();
    var $btn = $(this);
    var $toSet = $btn.attr('data-set');
    var $id = $btn.attr('data-id');

    $.post($btn.attr('data-url'), {
        toSet: $toSet,
        id: $id,
    }).done(function (data) {

        if (data.result === 'success') {
            openNoty('success', data.message);
            $btn.removeClass('btn-success').removeClass('btn-danger').addClass('btn-'+data.extra.c).html('<i class="fa fa-'+data.extra.icon+'"></i> '+data.extra.text).attr('data-set', data.extra.set);
            $('#js-used-'+$id).html(data.extra.status);
        } else {
            openNoty('error', 'Fehler beim Speichern, bitte versuchen');
        }

    }).fail(function () {
        openNoty('error', 'Fehler beim Speichern, bitte versuchen');
        return false;
    })

});

/* Noty Function
 * type = {alert, success, error, warning, information}
 */
function openNoty(type, text) {
    new Noty({
        layout: 'topRight',
        theme: 'mint',
        text: text,
        type: type,
        timeout: 3000,
        buttons: false
    }).show();
}