<?php


namespace JDR\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use JDR\CoreBundle\Entity\Session;


class SessionController extends Controller
{

    /**
     * Page de création d'une partie
     */
    public function createSessionAction(Request $request){

        if ($request->isMethod('POST')){
            $allowedStats = explode(',',$request->request->get('_allowedstats'));
            $gameName = $request->request->get('_gamename');
            $gameMaster = $this->get('security.token_storage')->getToken()->getUser();

            $session = new Session();
            $session->setName($gameName);
            $session->setAllowedStats($allowedStats);
            $session->setGameMaster($gameMaster);

            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();

        }

        return $this->render("JDRCoreBundle:Session:sessioncreation.html.twig");
    }
}