<?php

namespace JDR\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JDR\UserBundle\Entity\User;

/**
 * PlayerCharacter
 *
 * @ORM\Table(name="player_character")
 * @ORM\Entity(repositoryClass="JDR\CoreBundle\Repository\PlayerCharacterRepository")
 */
class PlayerCharacter
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var array
     *
     * @ORM\Column(name="statistics", type="array")
     */
    private $statistics;

    /**
     * @var array
     *
     * @ORM\Column(name="skills", type="array", nullable=true)
     */
    private $skills;

    /**
     * @var array
     *
     * @ORM\Column(name="stuff", type="array", nullable=true)
     */
    private $stuff;

    /**
     * @var array
     *
     * @ORM\Column(name="spells", type="array", nullable=true)
     */
    private $spells;

    /**
     * @var string
     * @ORM\Column(name="backstory", type="text", nullable=true)
     */
    private $backStory;

    /**
     * @ORM\ManyToOne(targetEntity="JDR\CoreBundle\Entity\Session", inversedBy="playersCharacters")
     * @ORM\JoinColumn(name="session_id", referencedColumnName="id")
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
     * Set name
     *
     * @param string $name
     *
     * @return PlayerCharacter
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
     * Set title
     *
     * @param string $title
     *
     * @return PlayerCharacter
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set statistics
     *
     * @param array $statistics
     *
     * @return PlayerCharacter
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;

        return $this;
    }

    /**
     * Get statistics
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * Set skills
     *
     * @param array $skills
     *
     * @return PlayerCharacter
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * Get skills
     *
     * @return array
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set stuff
     *
     * @param array $stuff
     *
     * @return PlayerCharacter
     */
    public function setStuff($stuff)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return array
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * Set spells
     *
     * @param array $spells
     *
     * @return PlayerCharacter
     */
    public function setSpells($spells)
    {
        $this->spells = $spells;

        return $this;
    }

    /**
     * Get spells
     *
     * @return array
     */
    public function getSpells()
    {
        return $this->spells;
    }


    /**
     * Set username
     *
     * @param string $username
     *
     * @return PlayerCharacter
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set backStory
     *
     * @param string $backStory
     *
     * @return PlayerCharacter
     */
    public function setBackStory($backStory)
    {
        $this->backStory = $backStory;

        return $this;
    }

    /**
     * Get backStory
     *
     * @return string
     */
    public function getBackStory()
    {
        return $this->backStory;
    }

    /**
     * Set session
     *
     * @param \JDR\CoreBundle\Entity\Session $session
     *
     * @return PlayerCharacter
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
