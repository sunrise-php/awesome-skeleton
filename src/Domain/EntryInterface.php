<?php declare(strict_types=1);

namespace App\Domain;

/**
 * EntryInterface
 */
interface EntryInterface
{

    /**
     * Gets the entry ID
     *
     * @return null|int
     */
    public function getId() : ?int;

    /**
     * Gets the entry name
     *
     * @return null|string
     */
    public function getName() : ?string;

    /**
     * Sets the given name to the entry
     *
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name) : void;
}
