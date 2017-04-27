<?php


namespace JDR\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JDR\CoreBundle\Entity\PlayerCharacter;
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
    public function indexAction()
    {

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