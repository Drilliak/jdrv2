
// TODO bouton pour supprimer la session





$(document).ready(function () {


    /************* Actions sur les boutons des participants ***********/


    // Supprimer le joueur


    // TODO Ajout de la suppression d'une ivnitation lors du click sur la croix (pas encore définie) à côté d'une invitation refusée.

    // Renvoyer l'invitation
    tbody.on("click", "a.sendagain", function () {
        trElement = $(this).parent().parent();
        playerName = trElement.attr('id');
        trElement.html('<td>' + playerName + '</td>' + '<td>En attente</td> <td><button class="cancel-invitation">Annuler l\'invitation</button></td>');
        $.post(
            " {{ path('jdr_core_ajax_send_again') }}",
            {
                playerName: playerName,
                idSession: {{ idSession }} }, function (data) {

        },
        'json'
        );

    });


    /**************    Action sur le champ d'ajout de joueur *************/


    // Action sur le bouton de suppression de partie
    removeSession();

    // TODO Actualisation données en temps réel
});

