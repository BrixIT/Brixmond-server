<?php

namespace BrixIT\brixmondBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientInfo
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="uniquevalue", columns={"client_id", "name"})})
 * @ORM\Entity
 */
class ClientInfo
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
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="info")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="value", type="json_array")
     */
    private $value;


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
     * @return ClientInfo
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
     * Set value
     *
     * @param array $value
     * @return ClientInfo
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return array 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set client
     *
     * @param \BrixIT\brixmondBundle\Entity\Client $client
     * @return ClientInfo
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
}
