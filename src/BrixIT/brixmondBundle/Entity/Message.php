<?php

namespace BrixIT\brixmondBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Messages
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Message
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
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="messages")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="Watch", inversedBy="messages")
     * @ORM\JoinColumn(name="watch_id", referencedColumnName="id")
     */
    private $watch;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=15)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages")
     * @ORM\JoinColumn(name="acknowledged_user", referencedColumnName="id")
     */
    private $acknowledged;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fixed", type="boolean")
     */
    private $fixed;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var array
     *
     * @ORM\Column(name="extra", type="json_array")
     */
    private $extra = [];


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
     * Set level
     *
     * @param string $level
     * @return Message
     */
    public function setLevel($level)
    {
        if (!in_array($level, ['drop', 'info', 'warning', 'error', 'emergency'])) {
            throw new \InvalidArgumentException("Invalid level");
        }
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Message
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
     * Set message
     *
     * @param string $message
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set fixed
     *
     * @param boolean $fixed
     * @return Message
     */
    public function setFixed($fixed)
    {
        $this->fixed = $fixed;

        return $this;
    }

    /**
     * Get fixed
     *
     * @return boolean
     */
    public function getFixed()
    {
        return $this->fixed;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Message
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set extra
     *
     * @param array $extra
     * @return Message
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Set client
     *
     * @param \BrixIT\brixmondBundle\Entity\Client $client
     * @return Message
     */
    public function setClient(\BrixIT\brixmondBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \BrixIT\brixmondBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set watch
     *
     * @param \BrixIT\brixmondBundle\Entity\Watch $watch
     * @return Message
     */
    public function setWatch(\BrixIT\brixmondBundle\Entity\Watch $watch = null)
    {
        $this->watch = $watch;

        return $this;
    }

    /**
     * Get watch
     *
     * @return \BrixIT\brixmondBundle\Entity\Watch 
     */
    public function getWatch()
    {
        return $this->watch;
    }

    /**
     * Set acknowledged
     *
     * @param \BrixIT\brixmondBundle\Entity\User $acknowledged
     * @return Message
     */
    public function setAcknowledged(\BrixIT\brixmondBundle\Entity\User $acknowledged = null)
    {
        $this->acknowledged = $acknowledged;

        return $this;
    }

    /**
     * Get acknowledged
     *
     * @return \BrixIT\brixmondBundle\Entity\User 
     */
    public function getAcknowledged()
    {
        return $this->acknowledged;
    }
}
