<?php

namespace JDR\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="JDR\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="JDR\CoreBundle\Entity\PlayerCharacter", mappedBy="user")
     */
    private $characters;


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
     * Add character
     *
     * @param \JDR\CoreBundle\Entity\PlayerCharacter $character
     *
     * @return User
     */
    public function addCharacter(\JDR\CoreBundle\Entity\PlayerCharacter $character)
    {
        $this->characters[] = $character;

        return $this;
    }

    /**
     * Remove character
     *
     * @param \JDR\CoreBundle\Entity\PlayerCharacter $character
     */
    public function removeCharacter(\JDR\CoreBundle\Entity\PlayerCharacter $character)
    {
        $this->characters->removeElement($character);
    }

    /**
     * Get characters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCharacters()
    {
        return $this->characters;
    }
}
