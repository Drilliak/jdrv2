<?php

namespace JDR\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JDR\UserBundle\Entity\User;

/**
 * Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity(repositoryClass="JDR\CoreBundle\Repository\SessionRepository")
 */
class Session
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @var User
     * @ORM\OneToOne(targetEntity="JDR\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $gameMaster;

    /**
     * @ORM\ManyToMany(targetEntity="JDR\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @var array
     *
     * @ORM\Column(name="allowedStats", type="array")
     */
    private $allowedStats;




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Session
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set allowedStats
     *
     * @param array $allowedStats
     *
     * @return Session
     */
    public function setAllowedStats($allowedStats)
    {
        $this->allowedStats = $allowedStats;

        return $this;
    }

    /**
     * Get allowedStats
     *
     * @return array
     */
    public function getAllowedStats()
    {
        return $this->allowedStats;
    }

    /**
     * Set gameMaster
     *
     * @param \JDR\UserBundle\Entity\User $gameMaster
     *
     * @return Session
     */
    public function setGameMaster(\JDR\UserBundle\Entity\User $gameMaster)
    {
        $this->gameMaster = $gameMaster;

        return $this;
    }

    /**
     * Get gameMaster
     *
     * @return \JDR\UserBundle\Entity\User
     */
    public function getGameMaster()
    {
        return $this->gameMaster;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \JDR\UserBundle\Entity\User $user
     *
     * @return Session
     */
    public function addUser(\JDR\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \JDR\UserBundle\Entity\User $user
     */
    public function removeUser(\JDR\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
