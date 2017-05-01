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


class SessionController extends Controller
{

    /**
     * Page de création d'une partie
     */
    public function createSessionAction(Request $request)
    {

        $session = array();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $session);

        $formBuilder
            ->add("name", TextType::class)
            ->add("allowedStats", TextType::class)
            ->add("submit", SubmitType::class);

        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();

            $gameName = $data['name'];
            $allowedStats = explode(',', $data['allowedStats']);
            $gameMaster = $this->get('security.token_storage')->getToken()->getUser();

            $session = new Session();
            $session->setName($gameName);
            $session->setAllowedStats($allowedStats);
            $session->setGameMaster($gameMaster);
            $em = $this->getDoctrine()->getManager();
            $em->persist($session);
            $em->flush();
            $id = $session->getID();
            $request->getSession()->getFlashBag()->add('newSession', "Nouvelle partie créée");

            return $this->redirectToRoute('jdr_core_home');


        }

        return $this->render("JDRCoreBundle:Session:sessioncreation.html.twig", array(
            'form' => $form->createView()
        ));
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
        $allowedStats = $session->getAllowedStats();

        $invitations = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Invitation")->findNameAndStatut($id);

        return $this->render("JDRCoreBundle:Session:editsession.html.twig",
            array(
                'name' => $name,
                'allowedStats' => $allowedStats,
                'id' => $id,
                'invitations' => $invitations
            ));
    }

    /**
     * Requête ajax pour supprimer une caractéristiques d'une partie
     */
    public function removeStatAction(Request $request){
        if ($request->isXMLHttpRequest()){
            $stat = $request->get('stat');

            $idSession = $request->get('idSession');

            $repository = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Session");
            $session= $repository->find($idSession);
            $allowedStats = $session->getAllowedStats();
            $allowedStats = array_diff($allowedStats, array($stat));
            $session->setAllowedStats($allowedStats);

            $this->getDoctrine()->getManager()->flush();


            return new JsonResponse('success');
        }

        return new Response("Ce n'est pas une requête Ajax", 400);
    }

    /**
     * Requête ajax pour ajouter une caractéristiques dans une partie
     */
    public function addStatAction(Request $request){
        if ($request->isXMLHttpRequest()){
            $stat = $request->get('stat');

            $idSession = $request->get('idSession');

            $repository = $this->getDoctrine()->getManager()->getRepository("JDRCoreBundle:Session");
            $session= $repository->find($idSession);
            $allowedStats = $session->getAllowedStats();
            $allowedStats[] = $stat;
            $session->setAllowedStats($allowedStats);

            $this->getDoctrine()->getManager()->flush();


            return new JsonResponse('success');
        }

        return new Response("Ce n'est pas une requête Ajax", 400);
    }

    /**
     * Permet de supprimer une partie.
     */
    public function removeSessionAction($id, Request $request){
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