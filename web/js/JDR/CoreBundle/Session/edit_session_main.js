/** Récupération des variables passées par le contructeur **/
let JsVars = jQuery('#js-vars').data('vars');
let idSession = JsVars.idSession;

let pathCurrentPage = $('#js-vars-twig').data('path');
let sendInvitationPah = $('#js-vars-twig').data('sendinvitationpath');
let autocompletePath = $('#js-vars-twig').data('autocompletepath');

/* ========================================================================
 * Fonctions utiles à la page (ajax principalement)
 * Toutes les méthodes ajax doivent envoyer l'attribut functionName
 * et envoyer le nom de leur fonction, ce qui permet au contrôleur de
 * décider du comportement de la page.
 * ========================================================================
 */

/**
 * Requête AJAX permettant d'ajouter une caractéristiques à celles autorisées.
 */
function addItem(event, idSession) {
    $.post(
        pathCurrentPage,
        {
            functionName: "addItem",
            stat: event.item,
            idSession: idSession
        },
        function (data) {
        },
        'json'
    );
}

/**
 * Requête AJAX permettant de surpprimer une caractéristiques à celles autorisées.
 */
function removeItem(event, idSession) {
    $.post(
        pathCurrentPage,
        {
            functionName: "removeItem",
            stat: event.item,
            idSession: idSession
        },
        function (data) {
        },
        'json'
    );
}

/**
 * Requête AJAX permettant de modifier la description de la partie.
 */
function changeDescription(idSession) {
    $("#save-description").click(function () {
        let newDescription = $("textarea#description").val();
        $.post(
            pathCurrentPage,
            {
                functionName: "changeDescription",
                newDescription: newDescription,
                idSession: idSession
            },
            function (date) {
            },
            'json'
        );
    });
}

/**
 * Requête ajax permettant d'annuler l'invitation envoyée à un joueur.
 */
function cancelInvitation(idSession) {
    let thbodyParticipants = $("#participants");
    thbodyParticipants.on("click", "a.cancel-invitation", function () {
        trElement = $(this).parent().parent();
        trElement.remove();
        $.post(
            pathCurrentPage,
            {
                functionName: "cancelInvitation",
                playerName: trElement.attr('id'),
                idSession: idSession
            },
            function (data) {
            },
            'json'
        );
    });
}

/**
 * Retire le joueur de la session
 */
function removePlayer(idSession) {
    let thbodyParticipants = $("#participants");
    thbodyParticipants.on("click", "a.remove-player", function () {
        trElement = $(this).parent().parent();
        let playerName = trElement.attr('id');
        trElement.remove();
        $.post(
            pathCurrentPage,
            {
                functionName: "removePlayer",
                playerName: playerName,
                idSession: idSession
            },
            function (data) {
            },
            'json'
        );
    });
}

function sendInvit(ui, idSession) {
    $.post(
        sendInvitationPah,
        {
            idSession: idSession,
            playerName: ui.item.value
        },
        function (data) {
        },
        'json'
    );
}

function addPlayer() {
    $("#users").autocomplete({
        source: autocompletePath,

        select: function (event, ui) {

            // Si l'utilisateur ne figure pas déjà dans la liste
            let username = $("#" + ui.item.value);
            let error = $("#error");
            if (!username.length) {
                $("#participants").append('<tr id="' + ui.item.value + '"><td>' + ui.item.value + '</td> <td>En attente</td> <td><a class="btn btn-primary cancel-invitation">Annuler l\'invitation</a></td></tr>');
                sendInvit(ui, idSession);

            } else {
                error.show();
                error.html('<a href="#" class="close" data-dismiss="alert">&times;</a>Une invitation a déjà été envoyée à ce joueur');
                setTimeout(function () {
                    error.hide(1000);
                    error.val('');
                }, 5000);

            }
            // TODO impossible des'(s'inviter soi-même)

            ui.item.value = "";
        }
    });

}


/* ========================================================================
 * Méthode principale de la page
 * ========================================================================
 */
$(document).ready(function () {
    /***** Actions sur le caractéristiques autorisée *****/
    let input = $('#allowed-stats');
    input.on('itemAdded', function (event) {
        addItem(event, idSession);
    });

    input.on('itemRemoved', function (event) {
        removeItem(event, idSession);
    });

    changeDescription(idSession);

    /**** Actions sur les boutons de gestion des joueurs *****/
    cancelInvitation(idSession);
    removePlayer(idSession);

    addPlayer();

});