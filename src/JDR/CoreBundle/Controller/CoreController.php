<?php


namespace JDR\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JDR\CoreBundle\Entity\PlayerCharacter;


class CoreController extends Controller
{
    /**
     * Page principale où l'utilisateur arrive après la connexion
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(){
        return $this->render("JDRCoreBundle:Core:index.html.twig");
    }
}