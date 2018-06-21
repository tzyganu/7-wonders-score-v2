<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity
 */
class WonderSet
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false, length=50)
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true, "default":1})
     */
    private $active;
    /**
     * @var ArrayCollection
     * @OneToMany(targetEntity="Wonder", mappedBy="wonderSet")
     */
    private $wonders;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->wonders = new ArrayCollection();
    }

    /**
     * @param int $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return int
     */
    public function getActive()
    {
        return (bool)$this->active;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $wonders
     */
    public function setWonders($wonders)
    {
        $this->wonders = $wonders;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getWonders()
    {
        return $this->wonders;
    }

    /**
     * @param Wonder $wonder
     */
    public function addWonder(Wonder $wonder)
    {
        $this->wonders->add($wonder);
    }
}
