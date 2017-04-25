<?php


namespace JDR\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JDR\CoreBundle\Entity\PlayerCharacter;


class CoreController extends Controller
{
    public function indexAction(){


        

        return $this->render("JDRCoreBundle:Core:index.html.twig", array("coucou" => "salut ducon"));
    }
}