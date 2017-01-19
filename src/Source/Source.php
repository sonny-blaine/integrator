<?php

namespace Simonetti\IntegradorFinanceiro\Source;

use Doctrine\Common\Collections\ArrayCollection as DestinationsCollection;
use Simonetti\IntegradorFinanceiro\Connection;

/**
 * Class Source
 * @package Simonetti\IntegradorFinanceiro\Source
 */
class Source
{

    /**
     * Source ID
     * @var int
     */
    protected $id;

    /**
     * Source Identifier
     * @var string
     */
    protected $identifier;

    /**
     * Data connection to database
     * @var Connection
     */
    protected $connection;

    /**
     * Base SQL
     * @var string
     */
    protected $sql;

    /**
     * List of destinations
     * @var DestinationsCollection
     */
    protected $destinations;

    /**
     * Source constructor.
     * @param string $identifier
     * @param Connection $connection
     * @param string $sql
     * @param DestinationsCollection $destinations
     */
    public function __construct(string $identifier, Connection $connection, string $sql, DestinationsCollection $destinations)
    {
        $this->identifier = $identifier;
        $this->connection = $connection;
        $this->sql = $sql;
        $this->destinations = $destinations;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @return DestinationsCollection
     */
    public function getDestinations(): DestinationsCollection
    {
        return $this->destinations;
    }
}