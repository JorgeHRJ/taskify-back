<?php

namespace ApiBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Task
 *
 * @ORM\Table("tasks")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks()
 * 
 * @ExclusionPolicy("ALL")
 */
class Task
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @JMS\Expose()
     * @JMS\Groups({"api"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column (name="title", type="string", nullable=false)
     * 
     * @JMS\Expose()
     * @JMS\Groups({"api"})
     */
    private $title;

    /**
     * @var string
     * @ORM\Column (name="description", type="string", nullable=false)
     * 
     * @JMS\Expose()
     * @JMS\Groups({"api"})
     */
    private $description;

    /**
     * @var TaskStatus
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\TaskStatus", inversedBy="tasks")
     * 
     * @Expose()
     * @Groups({"api"})
     */
    private $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User", inversedBy="tasks")
     * 
     * @Expose()
     * @Groups({"api"})
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column (name="createdAt", type="datetime", nullable=true)
     * 
     * @JMS\Expose()
     * @JMS\Groups({"api"})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column (name="updatedAt", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column (name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\PreUpdate()
     */
    function update()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist()
     */
    function create()
    {
        $this->createdAt = new \DateTime('now');
    }

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
     * Set title
     *
     * @param string $title
     * @return Task
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
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param \ApiBundle\Entity\TaskStatus $status
     * @return Task
     */
    public function setStatus(\ApiBundle\Entity\TaskStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \ApiBundle\Entity\TaskStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     * @return Task
     */
    public function setUser(\ApiBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ApiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return User
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

}