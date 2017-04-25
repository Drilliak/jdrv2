<?php


namespace JDR\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JDR\CoreBundle\Entity\PlayerCharacter;


class CoreController extends Controller
{
    public function indexAction(){

        $repository = $this->getDoctrine()->getManager()->getRepository('JDRUserBundle:User');

        $user = $repository->find(1);


        var_dump($user->getCharacters()[0]->getName());



        return $this->render("JDRCoreBundle:Core:index.html.twig");
    }
}