<?php

namespace BrixIT\brixmondBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Client
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
     * @ORM\Column(name="fqdn", type="string", length=255, unique=true)
     */
    private $fqdn;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=36)
     */
    private $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="arch", type="string", length=10)
     */
    private $arch;

    /**
     * @var string
     *
     * @ORM\Column(name="dist", type="string", length=255)
     */
    private $dist;

    /**
     * @var string
     *
     * @ORM\Column(name="cpu", type="string", length=255)
     */
    private $cpu;

    /**
     * @var integer
     *
     * @ORM\Column(name="cores", type="integer")
     */
    private $cores;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var integer
     *
     * @ORM\Column(name="send_throttle", type="integer")
     */
    private $sendThrottle;

    /**
     * @ORM\OneToMany(targetEntity="Datapoint", mappedBy="client")
     */
    protected $datapoints;

    /**
     * @ORM\OneToOne(targetEntity="Host", mappedBy="client")
     **/
    private $host;

    /**
     * @ORM\OneToMany(targetEntity="ClientInfo", mappedBy="client")
     */
    protected $info;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="client")
     */
    protected $messages;

    public function __construct()
    {
        $this->datapoints = new ArrayCollection();
        $this->info = new ArrayCollection();
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
     * Set fqdn
     *
     * @param string $fqdn
     * @return Client
     */
    public function setFqdn($fqdn)
    {
        $this->fqdn = $fqdn;

        return $this;
    }

    /**
     * Get fqdn
     *
     * @return string 
     */
    public function getFqdn()
    {
        return $this->fqdn;
    }

    /**
     * Set secret
     *
     * @param string $secret
     * @return Client
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string 
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set arch
     *
     * @param string $arch
     * @return Client
     */
    public function setArch($arch)
    {
        $this->arch = $arch;

        return $this;
    }

    /**
     * Get arch
     *
     * @return string 
     */
    public function getArch()
    {
        return $this->arch;
    }

    /**
     * Set dist
     *
     * @param string $dist
     * @return Client
     */
    public function setDist($dist)
    {
        $this->dist = $dist;

        return $this;
    }

    /**
     * Get dist
     *
     * @return string 
     */
    public function getDist()
    {
        return $this->dist;
    }

    /**
     * Set cpu
     *
     * @param string $cpu
     * @return Client
     */
    public function setCpu($cpu)
    {
        $this->cpu = $cpu;

        return $this;
    }

    /**
     * Get cpu
     *
     * @return string 
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Client
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set sendThrottle
     *
     * @param integer $sendThrottle
     * @return Client
     */
    public function setSendThrottle($sendThrottle)
    {
        $this->sendThrottle = $sendThrottle;

        return $this;
    }

    /**
     * Get sendThrottle
     *
     * @return integer 
     */
    public function getSendThrottle()
    {
        return $this->sendThrottle;
    }

    /**
     * Add datapoints
     *
     * @param \BrixIT\brixmondBundle\Entity\Datapoint $datapoints
     * @return Client
     */
    public function addDatapoint(\BrixIT\brixmondBundle\Entity\Datapoint $datapoints)
    {
        $this->datapoints[] = $datapoints;

        return $this;
    }

    /**
     * Remove datapoints
     *
     * @param \BrixIT\brixmondBundle\Entity\Datapoint $datapoints
     */
    public function removeDatapoint(\BrixIT\brixmondBundle\Entity\Datapoint $datapoints)
    {
        $this->datapoints->removeElement($datapoints);
    }

    /**
     * Get datapoints
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDatapoints()
    {
        return $this->datapoints;
    }

    /**
     * Set host
     *
     * @param \BrixIT\brixmondBundle\Entity\Host $host
     * @return Client
     */
    public function setHost(\BrixIT\brixmondBundle\Entity\Host $host = null)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return \BrixIT\brixmondBundle\Entity\Host 
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Add info
     *
     * @param \BrixIT\brixmondBundle\Entity\ClientInfo $info
     * @return Client
     */
    public function addInfo(\BrixIT\brixmondBundle\Entity\ClientInfo $info)
    {
        $this->info[] = $info;

        return $this;
    }

    /**
     * Remove info
     *
     * @param \BrixIT\brixmondBundle\Entity\ClientInfo $info
     */
    public function removeInfo(\BrixIT\brixmondBundle\Entity\ClientInfo $info)
    {
        $this->info->removeElement($info);
    }

    /**
     * Get info
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInfo()
    {
        return $this->info;
    }

    function __toString()
    {
        return $this->fqdn;
    }


    /**
     * Set cores
     *
     * @param integer $cores
     * @return Client
     */
    public function setCores($cores)
    {
        $this->cores = $cores;

        return $this;
    }

    /**
     * Get cores
     *
     * @return integer 
     */
    public function getCores()
    {
        return $this->cores;
    }

    /**
     * Add messages
     *
     * @param \BrixIT\brixmondBundle\Entity\Message $messages
     * @return Client
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
