<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * TaskStatus
 *
 * @ORM\Table("taskstatus")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * 
 * @ExclusionPolicy("ALL")
 */
class TaskStatus
{

    const _PENDING_CODE     = 'PENDING';
    const _INPROCESS_CODE   = 'INPROCESS';
    const _COMPLETED_CODE   = 'COMPLETED';

    const _PENDING_DESC     = 'PENDING';
    const _INPROCESS_DESC   = 'IN PROCESS';
    const _COMPLETED_DESC   = 'COMPLETED';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="codec", type="string", length=10)
     * @Expose()
     * @Groups({"api"})
     */
    private $codec;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var Request
     *
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Task", mappedBy="status")
     */
    private $tasks;

    /**
     * @var \DateTime
     *
     * @ORM\Column (name="createdAt", type="datetime", nullable=true)
     *
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column (name="updatedAt", type="datetime", nullable=true)
     * 
     */
    protected $updatedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set codec
     *
     * @param string $codec
     *
     * @return TaskStatus
     */
    public function setCodec($codec)
    {
        $this->codec = $codec;

        return $this;
    }

    /**
     * Get codec
     *
     * @return string
     */
    public function getCodec()
    {
        return $this->codec;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return TaskStatus
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
     * Add task
     *
     * @param \ApiBundle\Entity\Task $task
     *
     * @return TaskStatus
     */
    public function addTask(\ApiBundle\Entity\Task $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task
     *
     * @param \ApiBundle\Entity\Task $task
     */
    public function removeTask(\ApiBundle\Entity\Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TaskStatus
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
     * @return TaskStatus
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

}
