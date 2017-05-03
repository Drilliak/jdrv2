function removeSession() {
    $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: [
            {
                text: "Oui",
                click: function () {
                    $(location).attr('href', "{{ path('jdr_core_session_destroy', {'id': id}) }}");
                }
            },
            {
                text: 'Non',
                click: function () {
                    $(this).dialog('close');
                }
            }
        ]
    });
    $("#opener").click(function () {
        $("#dialog").dialog("open");
    });
}


function addItem(event, idSession) {
    $.post(
        "{{ path('jdr_core_ajax_add_stat_session') }}",
        {
            stat: event.item,
            idSession: idSession
        },
        function (data) {

        },
        'json'
    );

}

function removeItem(event, idSession) {
    $.post(
        "{{ path('jdr_core_ajax_remove_stat_session') }}",
        {
            stat: event.item,
            idSession: idSession
        },
        function (data) {

        },
        'json'
    );
}

function sendInvit(ui, idSession) {
    $.post(
        "{{ path('jdr_core_ajax_send_invitation') }}",
        {
            idSession: idSession,
            playerName: ui.item.value
        },
        function (data) {
            console.log(data);
        },
        'json'
    );
}

