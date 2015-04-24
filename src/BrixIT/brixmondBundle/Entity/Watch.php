<?php

namespace BrixIT\brixmondBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Watch
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Watch
{
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="system", type="string", length=64)
     */
    private $system;

    /**
     * @var string
     *
     * @ORM\Column(name="expression", type="text")
     */
    private $expression;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_title", type="string", length=255)
     */
    private $notificationTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_message", type="text")
     */
    private $notificationMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="watch")
     */
    protected $messages;

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
     * @return Watch
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
     * Set type
     *
     * @param string $type
     * @return Watch
     */
    public function setType($type)
    {
        if (!in_array($type, ['point', 'info'])) {
            throw new \InvalidArgumentException("Invalid type");
        }
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set system
     *
     * @param string $system
     * @return Watch
     */
    public function setSystem($system)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * Get system
     *
     * @return string
     */
    public function getSystem()
    {
        return $this->system;
    }

    /**
     * Set expression
     *
     * @param string $expression
     * @return Watch
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * Get expression
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Set notificationTitle
     *
     * @param string $notificationTitle
     * @return Watch
     */
    public function setNotificationTitle($notificationTitle)
    {
        $this->notificationTitle = $notificationTitle;

        return $this;
    }

    /**
     * Get notificationTitle
     *
     * @return string 
     */
    public function getNotificationTitle()
    {
        return $this->notificationTitle;
    }

    /**
     * Set notificationMessage
     *
     * @param string $notificationMessage
     * @return Watch
     */
    public function setNotificationMessage($notificationMessage)
    {
        $this->notificationMessage = $notificationMessage;

        return $this;
    }

    /**
     * Get notificationMessage
     *
     * @return string 
     */
    public function getNotificationMessage()
    {
        return $this->notificationMessage;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return Watch
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add messages
     *
     * @param \BrixIT\brixmondBundle\Entity\Message $messages
     * @return Watch
     */
    public function addMessage(\BrixIT\brixmondBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \BrixIT\brixmondBundle\Entity\Message $messages
     */
    public function removeMessage(\BrixIT\brixmondBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
