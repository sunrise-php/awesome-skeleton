<?php declare(strict_types=1);

namespace App\Bundle\Example\Entity;

/**
 * Import classes
 */
use Arus\Doctrine\Bridge\Validator\Constraint\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Sunrise\Http\Router\OpenApi\Annotation\OpenApi;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
use DateTimeInterface;

/**
 * @ORM\Table(
 *   indexes={
 *     @ORM\Index(columns={"createdAt"}),
 *   },
 *   uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"slug"}),
 *   },
 *   options={
 *     "charset": "utf8mb4",
 *     "collate": "utf8mb4_unicode_ci",
 *   },
 * )
 *
 * @ORM\Entity(
 *   repositoryClass="App\Bundle\Example\Repository\EntryRepository",
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity({"slug"})
 */
class Entry
{

    /**
     * @ORM\Id()
     *
     * @ORM\Column(
     *   type="uuid",
     *   nullable=false,
     * )
     *
     * @var string|UuidInterface
     */
    private $id;

    /**
     * @ORM\Column(
     *   type="string",
     *   length=128,
     *   nullable=false,
     * )
     *
     * @Assert\Length(min=1, max=128)
     *
     * @OpenApi\Schema(
     *   refName="EntryName",
     *   type="string",
     *   minLength=1,
     *   maxLength=128,
     *   nullable=false,
     * )
     *
     * @var string
     */
    private $name = '';

    /**
     * @ORM\Column(
     *   type="string",
     *   length=128,
     *   nullable=false,
     * )
     *
     * @Assert\Length(min=1, max=128)
     *
     * @OpenApi\Schema(
     *   refName="EntrySlug",
     *   type="string",
     *   minLength=1,
     *   maxLength=128,
     *   nullable=false,
     * )
     *
     * @var string
     */
    private $slug = '';

    /**
     * @ORM\Column(
     *   type="datetime",
     *   nullable=false,
     * )
     *
     * @var null|DateTimeInterface
     */
    private $createdAt = null;

    /**
     * @ORM\Column(
     *   type="datetime",
     *   nullable=true,
     * )
     *
     * @var null|DateTimeInterface
     */
    private $updatedAt = null;

    /**
     * Inits the entity
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * Gets the entity ID
     *
     * @return string|UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the entry name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Gets the entry slug
     *
     * @return string
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * Gets the entity created date
     *
     * @return null|DateTimeInterface
     */
    public function getCreatedAt() : ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Gets the entity last updated date
     *
     * @return null|DateTimeInterface
     */
    public function getUpdatedAt() : ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Sets the entry name
     *
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * Sets the entry slug
     *
     * @param string $slug
     *
     * @return void
     */
    public function setSlug(string $slug) : void
    {
        $this->slug = $slug;
    }

    /**
     * @ORM\PrePersist()
     *
     * @return void
     */
    public function prePersist() : void
    {
        $this->createdAt = new DateTime('now');
    }

    /**
     * @ORM\PreUpdate()
     *
     * @return void
     */
    public function preUpdate() : void
    {
        $this->updatedAt = new DateTime('now');
    }
}
