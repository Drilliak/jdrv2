<?php


namespace JDR\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JDR\CoreBundle\Entity\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class CoreController extends Controller
{

    private static $jsonError = "Ce n'est pas une requête ajax";

    /**
     * Page principale où l'utilisateur arrive après la connexion
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // Si l'utilisateur a demandé la création d'une partie
        if ($request->isMethod('POST')) {
            $sessionName = $request->get('sessionName');
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

            return $this->redirectToRoute('jdr_core_game_edition', ['idSession' => $id]);
        }

        // Rafraichissement de la page (ajax)
        if ($request->isXmlHttpRequest()){

        }

        // Sinon on affiche simplement la page d'index.
        $repository = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Session");
        $idUser = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $sessionsAsGm = $repository->showSessionsAsGm($idUser);
        $sessionsAsPlayer = $repository->showSessionAsPlayer($idUser);


        return $this->render("JDRCoreBundle:Core:index.html.twig", array(
            "sessionsAsGm" => $sessionsAsGm,
            "sessionsAsPlayer" => $sessionsAsPlayer
        ));
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
        return new Response(self::$jsonError, 400);

    }


}