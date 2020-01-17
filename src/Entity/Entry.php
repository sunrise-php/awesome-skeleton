<?php declare(strict_types=1);

namespace App\Entity;

/**
 * Entry
 *
 * @Table(
 *   name="entry",
 * )
 *
 * @Entity(
 *   repositoryClass="App\Repository\EntryRepository"
 * )
 *
 * @OpenApi\Schema(
 *   refName="Entry",
 *   type="object",
 *   required={"id", "name"},
 *   properties={
 *     "id": @OpenApi\SchemaReference(
 *       class="App\Entity\Entry",
 *       property="id",
 *     ),
 *     "name": @OpenApi\SchemaReference(
 *       class="App\Entity\Entry",
 *       property="name",
 *     ),
 *   },
 * )
 */
final class Entry
{

    /**
     * The entry ID
     *
     * @Id()
     *
     * @Column(
     *   type="integer",
     *   options={
     *     "unsigned": true,
     *   },
     * )
     *
     * @GeneratedValue(
     *   strategy="AUTO",
     * )
     *
     * @OpenApi\Schema(
     *   refName="EntryId",
     *   type="integer",
     *   format="int32",
     *   minimum=1,
     *   maximum=PHP_INT_MAX,
     *   nullable=false,
     *   readOnly=true,
     * )
     *
     * @var null|int
     */
    private $id;

    /**
     * The entry name
     *
     * @Column(
     *   type="string",
     *   length=255,
     *   nullable=false,
     * )
     *
     * @OpenApi\Schema(
     *   refName="EntryName",
     *   type="string",
     *   minLength=1,
     *   maxLength=255,
     *   nullable=false,
     * )
     *
     * @var null|string
     */
    private $name;

    /**
     * {@inheritDoc}
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }
}
