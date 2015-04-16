<?php

namespace BrixIT\brixmondBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Datapoint
 *
 * @ORM\Table(indexes={@ORM\Index(name="uniquepoint", columns={"client_id", "time", "system"})})
 * @ORM\Entity
 */
class Datapoint
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
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="datapoints")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="system", type="string", length=15)
     */
    private $system;

    /**
     * @var array
     *
     * @ORM\Column(name="point", type="json_array")
     */
    private $point;


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
     * Set client
     *
     * @param integer $client
     * @return Datapoint
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return integer 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Datapoint
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set system
     *
     * @param string $system
     * @return Datapoint
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
     * Set point
     *
     * @param array $point
     * @return Datapoint
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return array 
     */
    public function getPoint()
    {
        return $this->point;
    }
}
