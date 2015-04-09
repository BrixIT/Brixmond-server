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

    public function __construct()
    {
        $this->datapoints = new ArrayCollection();
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
}
