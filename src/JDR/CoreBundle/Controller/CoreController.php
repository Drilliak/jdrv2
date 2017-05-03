<?php


namespace JDR\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JDR\CoreBundle\Entity\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CoreController extends Controller
{
    /**
     * Page principale où l'utilisateur arrive après la connexion
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Si l'utilisateur a demandé la création d'une partie
        if ($request->isMethod('POST')){
            $sessionName = $request->get('_sessionName');
            $allowedStats = array('PV', 'PV max', 'Mana', 'Mana max');
            $gameMaster = $this->get('security.token_storage')->getToken()->getUser();

            $session = new Session();
            $session->setName($sessionName);
            $session->setAllowedStats($allowedStats);
            $session->setGameMaster($gameMaster);
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();
            $id = $session->getID();
            $request->getSession()->getFlashBag()->add('newSession', "Nouvelle partie créée, vous pouvez désormais la personnaliser");

            return $this->redirectToRoute('jdr_core_game_edition', ['id' => $id]);
        }

        // Sinon on affiche simplement la page d'index.
        $repository = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Session");

        $idUser = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $gamesAsGm = $repository->selectSessionAsGm($idUser, array('name', 'id'));

        return $this->render("JDRCoreBundle:Core:index.html.twig", array("gameAsGm" => $gamesAsGm));
    }

    /**
     * Récupération des noms des utilisateur en bdd pour autocomplétion.
     */
    public function usernameAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $term = $request->get('term');
            $repository = $this->getDoctrine()->getManager()->getRepository("JDRUserBundle:User");
            $doctrineRes = $repository->selectSimilarName($term);
            $res = array();
            foreach ($doctrineRes as $array) {
                foreach ($array as $key => $value) {
                    $res[] = $value;
                }
            }
            return new JsonResponse($res);


        }
        return new Response("Ce n'est pas une requête Ajax", 400);

    }



}