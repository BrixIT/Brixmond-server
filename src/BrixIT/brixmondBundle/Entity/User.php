<?php

namespace BrixIT\brixmondBundle\Entity;


use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\GroupInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pushover_key", type="string", length=255, nullable=true)
     */
    protected $pushoverKey = null;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="acknowledged_user")
     */
    protected $messages;

    /**
     * @ORM\ManyToMany(targetEntity="BrixIT\brixmondBundle\Entity\Group")
     * @ORM\JoinTable(name="Users_Groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;


    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Set pushoverKey
     *
     * @param string $pushoverKey
     * @return User
     */
    public function setPushoverKey($pushoverKey)
    {
        $this->pushoverKey = $pushoverKey;

        return $this;
    }

    /**
     * Get pushoverKey
     *
     * @return string 
     */
    public function getPushoverKey()
    {
        return $this->pushoverKey;
    }

    /**
     * Add groups
     *
     * @param GroupInterface $groups
     * @return User
     */
    public function addGroup(GroupInterface $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param GroupInterface $groups
     */
    public function removeGroup(GroupInterface $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add messages
     *
     * @param \BrixIT\brixmondBundle\Entity\Message $messages
     * @return User
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
