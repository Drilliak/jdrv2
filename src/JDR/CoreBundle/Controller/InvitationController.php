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

        return new Response("Ce n'est pas une requête Ajax", 400);
    }

    public function showInvitAction(Request $request){
        if ($request->isXmlHttpRequest()){
            $idSession = $request->get('idSession');
            $invitationRepository = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('JDRCoreBundle:Invitation');

            return new JsonResponse($invitationRepository->findNameAndStatut($idSession));

        }
        return new Response("Ce n'est pas une requête Ajax", 400);
    }
}