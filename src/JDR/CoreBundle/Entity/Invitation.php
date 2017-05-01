<?php

namespace JDR\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JDR\UserBundle\Entity\User;
use JDR\CoreBundle\Entity\Session;

/**
 * Invitation
 *
 * @ORM\Table(name="invitation")
 * @ORM\Entity(repositoryClass="JDR\CoreBundle\Repository\InvitationRepository")
 */
class Invitation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
 * @var User
 * @ORM\ManyToOne(targetEntity="JDR\UserBundle\Entity\User")
 * @ORM\JoinColumn(nullable=false)
 */
    private $player;

    /**
     * @var Session
     * @ORM\ManyToOne(targetEntity="JDR\CoreBundle\Entity\Session")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Invitation
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set player
     *
     * @param \JDR\UserBundle\Entity\User $player
     *
     * @return Invitation
     */
    public function setPlayer(\JDR\UserBundle\Entity\User $player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \JDR\UserBundle\Entity\User
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set session
     *
     * @param \JDR\CoreBundle\Entity\Session $session
     *
     * @return Invitation
     */
    public function setSession(\JDR\CoreBundle\Entity\Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return \JDR\CoreBundle\Entity\Session
     */
    public function getSession()
    {
        return $this->session;
    }
}
