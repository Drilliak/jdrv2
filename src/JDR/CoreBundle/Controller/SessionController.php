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
    public function createSessionAction(Request $request)
    {

        if ($request->isMethod('POST')) {
            $allowedStats = explode(',', $request->request->get('_allowedstats'));
            $gameName = $request->request->get('_gamename');
            $gameMaster = $this->get('security.token_storage')->getToken()->getUser();

            $session = new Session();
            $session->setName($gameName);
            $session->setAllowedStats($allowedStats);
            $session->setGameMaster($gameMaster);

            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();

            $request->getSession()->getFlashBag()->add('indo', 'Nouvelle partie créée');

            return $this->redirectToRoute('jdr_core_home');

        }

        return $this->render("JDRCoreBundle:Session:sessioncreation.html.twig");
    }

    /**
     * Page d'édition d'une partie en cours
     */
    public function editSessionAction($id)
    {

        $gmId = $this->get('security.token_storage')->getToken()->getUser()->getId();

        $repository = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Session");
        $gameMaster = $repository->selectGM($id);

        // Si la personne essaie d'accèder à une session dont il est mj
        if ($gmId != $gameMaster->getId()) {
            return $this->redirectToRoute('jdr_core_home');
        }

        // Sinon il accède à sa page d'édition
        $session = $repository->selectSession($id);

        $name = $session->getName();
        $gameMaster = $session->getGameMaster();
        $users = $session->getUsers();
        $allowedStats = $session->getAllowedStats();


        return $this->render("JDRCoreBundle:Session:editsession.html.twig",
            array(
                'name' => $name,
                'users' => $users,
                'allowedStats' => $allowedStats
            ));
    }
}