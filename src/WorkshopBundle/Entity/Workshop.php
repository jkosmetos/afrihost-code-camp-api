<?php

namespace WorkshopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use CommentBundle\Entity\Comment;

/**
 * Workshop
 *
 * @ORM\Table(name="workshop")
 * @ORM\Entity(repositoryClass="WorkshopBundle\Repository\WorkshopRepository")
 */
class Workshop
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
     * @ORM\Column(name="title", type="string", length=125)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_class", type="string", length=125)
     */
    private $iconClass;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="lectures")
     * @ORM\JoinColumn(name="instructor_id", referencedColumnName="id")
     */
    private $instructor;

    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User", mappedBy="workshops")
     */
    private $students;

    /**
     * @var string
     *
     * @ORM\Column(name="excerpt", type="string", length=255)
     */
    private $excerpt;

    /**
     * @ORM\OneToMany(targetEntity="CommentBundle\Entity\Comment", mappedBy="workshop", cascade={"persist"})
     */
    private $comments;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starts_at", type="datetime")
     */
    private $startsAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    public function __construct() {
        $this->students = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     *
     * @return Workshop
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
     * Set iconClass
     *
     * @param string $iconClass
     *
     * @return Workshop
     */
    public function setIconClass($iconClass)
    {
        $this->iconClass = $iconClass;

        return $this;
    }

    /**
     * Get iconClass
     *
     * @return string
     */
    public function getIconClass()
    {
        return $this->iconClass;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Workshop
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
     * Set excerpt
     *
     * @param string $excerpt
     *
     * @return Workshop
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set startsAt
     *
     * @param \DateTime $startsAt
     *
     * @return Workshop
     */
    public function setStartsAt($startsAt)
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    /**
     * Get startsAt
     *
     * @return \DateTime
     */
    public function getStartsAt()
    {
        return $this->startsAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Workshop
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
     * @return Workshop
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
     * Set instructor
     *
     * @param \UserBundle\Entity\User $instructor
     *
     * @return Workshop
     */
    public function setInstructor(\UserBundle\Entity\User $instructor = null)
    {
        $this->instructor = $instructor;

        return $this;
    }

    /**
     * Get instructor
     *
     * @return \UserBundle\Entity\User
     */
    public function getInstructor()
    {
        return $this->instructor;
    }

    /**
     * Add student
     *
     * @param \UserBundle\Entity\User $student
     *
     * @return Workshop
     */
    public function addStudent(\UserBundle\Entity\User $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \UserBundle\Entity\User $student
     */
    public function removeStudent(\UserBundle\Entity\User $student)
    {
        $this->students->removeElement($student);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Add comment
     *
     * @param \CommentBundle\Entity\Comment $comment
     *
     * @return Workshop
     */
    public function addComment(\CommentBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \CommentBundle\Entity\Comment $comment
     */
    public function removeComment(\CommentBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
