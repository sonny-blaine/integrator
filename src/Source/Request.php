<?php
namespace Simonetti\IntegradorFinanceiro\Source;

use Doctrine\Common\Collections\ArrayCollection as DestinationsCollection;
use Doctrine\ORM\Mapping as ORM;
use Simonetti\IntegradorFinanceiro\Connection;

/**
 * Class Request
 * @package Simonetti\IntegradorFinanceiro\Source
 * @ORM\Entity()
 * @ORM\Table(name="source_request")
 */
class Request
{
    /**
     * Source ID
     * @ORM\Id()
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Source of Request
     * @ORM\ManyToOne(targetEntity="Simonetti\IntegradorFinanceiro\Source\Source")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id")
     * @var Source
     */
    protected $source;

    /**
     * Query Parameter
     * @ORM\Column(type="string", name="query_pamameter")
     * @var string
     */
    protected $queryPamameter;

    /**
     * Request constructor.
     * @param Source $source Source of Request
     * @param string $queryParameter Parameter of the Query
     */
    public function __construct(Source $source, string $queryParameter)
    {
        $this->source = $source;
        $this->queryPamameter = $queryParameter;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->source->getConnection();
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->source->getSql();
    }

    /**
     * @return Destination[]|DestinationsCollection
     */
    public function getDestinations(): DestinationsCollection
    {
        return $this->source->getDestinations();
    }

    /**
     * @return string
     */
    public function getQueryPamameter(): string
    {
        return $this->queryPamameter;
    }
}