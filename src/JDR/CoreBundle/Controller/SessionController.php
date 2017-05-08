<?php


namespace JDR\CoreBundle\Controller;

use JDR\CoreBundle\Entity\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class SessionController
 * @package JDR\CoreBundle\Controller
 */
class SessionController extends Controller
{


    /**
     * Page d'édition d'une partie en cours
     */
    public function editSessionAction(Request $request, $idSession)
    {
        // Si l'on a affaire a une requête ajax sur la page
        if ($request->isXmlHttpRequest()) {
            $functionName = $request->get('functionName');

            if ($functionName == "removeItem") {
                $stat = $request->get('stat');
                $idSession = $request->get('idSession');
                $this->removeItem($stat, $idSession);
                return new JsonResponse('success');
            }
            if ($functionName == 'addItem') {
                $stat = $request->get('stat');
                $idSession = $request->get('idSession');
                $this->addItem($stat, $idSession);
                return new JsonResponse('success');
            }
            if ($functionName == 'changeDescription') {
                $newDescription = $request->get('newDescription');
                $idSession = $request->get('idSession');
                $this->newDescritpion($newDescription, $idSession);
                return new JsonResponse('sucess');
            }
            if ($functionName == 'cancelInvitation') {
                $playerName = $request->get('playerName');
                $idSession = $request->get('idSession');
                $this->cancelInvitation($playerName, $idSession);
                return new JsonResponse('sucess');
            }
            if ($functionName =='removePlayer'){
                $playerName = $request->get('playerName');
                $idSession = $request->get('idSession');
                $this->removePlayer($playerName, $idSession);
                return new JsonResponse('success');
            }


        }

        // On récupère l'id de l'utilisateur connecté
        $currentUserId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $repository = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Session");
        $session = $repository->find($idSession);
        $gameMaster = $session->getGameMaster();

        // Si la personne essaie d'accèder à une session dont il n'est pas mj, il est redirigé à l'acceuil
        if ($currentUserId != $gameMaster->getId()) {
            return $this->redirectToRoute('jdr_core_home');
        }

        // Sinon il accède à sa page d'édition
        $name = $session->getName();
        $allowedStats = $session->getAllowedStats();
        $description = $session->getDescription();

        $invitations = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Invitation")->findNameAndStatut($idSession);


        /** VARIABLES TRANSMISES AUX FICHIERS JS */
        $this->get('acme.js_vars')->idSession = $idSession;


        return $this->render("JDRCoreBundle:Session:editsession.html.twig",
            array(
                'name'         => $name,
                'desciption'   => $description,
                'allowedStats' => $allowedStats,
                'invitations'  => $invitations
            ));
    }

    /**
     *
     */
    public function playerAction($idSession)
    {
        $em = $this->getDoctrine()->getManager();
        $sessionRepository = $em->getRepository("JDRCoreBundle:Session");
        $session = $sessionRepository->find(1);
        dump($session->getPlayersCharacters());
        return $this->render("JDRCoreBundle:Session:player.html.twig");
    }


    /**
     * Ajoute une caractéristiques parmi celles autorisées dans une partie
     *
     * @param string $stat nom de la statistique à ajouter
     * @param integer $idSession id de la session concernée
     */
    private function addItem($stat, $idSession)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Session");
        $session = $repository->find($idSession);
        $allowedStats = $session->getAllowedStats();
        $allowedStats[] = $stat;
        $session->setAllowedStats($allowedStats);
        $this->getDoctrine()->getManager()->flush();
    }

    /**
     * Retire une caratéristiques parmi celle autorisées dans une partie.
     * @param string $stat nom de la statistiques à retirer.
     * @param integer $idSession id de la session concernée.
     */
    private function removeItem($stat, $idSession)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Session");
        $session = $repository->find($idSession);
        $allowedStats = $session->getAllowedStats();
        $allowedStats = array_diff($allowedStats, array($stat));
        $session->setAllowedStats($allowedStats);
        $this->getDoctrine()->getManager()->flush();

    }

    /**
     * Modifie la description de la partie
     * @param string $newDescription nouvelle description
     * @param integer $idSession id de la session concernée.
     */
    private function newDescritpion($newDescription, $idSession)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Session");
        $session = $repository->find($idSession);
        $description = $session->setDescription($newDescription);
        $this->getDoctrine()->getManager()->flush();
    }



    /**
     * Supprime l'invitation envoyé à un joueur pour une session donnée.
     * @param string $playerName nom du joueur
     * @param integer $idSession id de la session concernée
     */
    private function cancelInvitation($playerName, $idSession)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('JDRCoreBundle:Invitation');
        $invitation = $repository->findByNameAndIdSession($playerName, $idSession);
        $em->remove($invitation);
        $em->flush();
    }

    /**
     * Retire l'invitation envoyée au joueur (à l'état accpetée) et le supprime de la liste des joueurs de la partie.
     * @param string $playerName nom du joueur
     * @param integer $idSession id de la session concernée
     */
    private function removePlayer($playerName, $idSession)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('JDRCoreBundle:Invitation');
        $invitation = $repository->findByNameAndIdSession($playerName, $idSession);
        $em->remove($invitation);
        $userRepository = $em->getRepository('JDRUserBundle:User');
        $user = $userRepository->findOneBy(['username' => $playerName]);
        $sessionRepository = $em->getRepository('JDRCoreBundle:Session');
        $session = $sessionRepository->find($idSession);
        $session->removeUser($user);
        $em->flush();
    }



    /**
     * Permet de supprimer une partie.
     */
    public function removeSessionAction($id, Request $request)
    {
        $gmId = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("JDRCoreBundle:Session");
        $gameMaster = $repository->selectGM($id);
        if ($gmId != $gameMaster->getId()) {
            return $this->redirectToRoute('jdr_core_home');
        }
        $session = $repository->selectSession($id);
        $em->remove($session);
        $em->flush();

        $request->getSession()->getFlashBag()->add('removeSession', "Votre partie a bien été supprimée");
        return $this->redirectToRoute('jdr_core_home');

    }




}