<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 01/05/2017
 * Time: 14:02
 */

namespace JDR\CoreBundle\Controller;


use JDR\CoreBundle\Entity\Invitation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvitationController extends Controller
{

    private static $ajaxError = "Ce n'est pas une requête ajax";

    /**
     * Requête ajax qui crée une invitation quand un joueur est ajouté dans une partie
     */
    public function addPlayerAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $idSession = $request->get('idSession');
            $playerName = $request->get('playerName');

            $invitationRepository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('JDRCoreBundle:Invitation');

            /*$invitation = $invitationRepository->findOneBy(array());

            // Si l'invitation a déjà été envoyé, on ne l'ajoute pas
            if ($invitation != null) {
                return new JsonResponse('invitation already exists');
            }*/
            $sessionRepository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('JDRCoreBundle:Session');
            $userRepository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('JDRUserBundle:User');
            $session = $sessionRepository->find($idSession);
            $player = $userRepository->findOneBy(array('username' => $playerName));

            $invitation = new Invitation();
            $invitation->setPlayer($player);
            $invitation->setSession($session);
            $invitation->setStatut('wait');

            $em = $this->getDoctrine()->getManager();
            $em->persist($invitation);
            $em->flush();

            return new JsonResponse('success');
        }

        return new Response(self::$ajaxError, 400);
    }


    /**
     * Renvoie toutes les invitations en attentes envoyés à un joueurs.
     */
    public function findInvitationsAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('JDRCoreBundle:Invitation');

            $idUser = $request->get('idUser');
            $invitations = $repository->findSessions($idUser);
            $nbInvitations = count($invitations);
            $res = [
                'invitation'   => $invitations,
                'nbInvitations' => $nbInvitations
            ];
            return new JsonResponse($res);
        }
        return new Response(self::$ajaxError, 400);
    }

    /**
     * retire l'invitation envoyé au joueur
     */
    public function cancelInvitationAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('JDRCoreBundle:Invitation');

            $playerName = $request->get('playerName');
            $idSession = $request->get('idSession');
            $invitation = $repository->select($playerName, $idSession);

            $em->remove($invitation);
            $em->flush();
            return new JsonResponse('success');

        }
        return new Response(self::$ajaxError, 400);
    }

    /**
     * Retire l'invitation envoyé au joueur et supprime le joueur de la partie
     */
    public function removePlayerAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            // On retire l'invitation
            $this->cancelInvitationAction($request);

            // TODO Retirer l'utilisateur de la session
        }
        return new Response(self::$ajaxError, 400);
    }


    /**
     * Renvoie l'invitation au joueur
     */
    public function sendAgainInvitationAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $playerName = $request->get('playerName');
            $idSession = $request->get('idSession');
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('JDRCoreBundle:Invitation');
            $invitation = $repository->select($playerName, $idSession);
            $invitation->setStatut('wait');

            $em->flush();
            return new JsonResponse('success');

        }

        return new Response(self::$ajaxError, 400);
    }
}