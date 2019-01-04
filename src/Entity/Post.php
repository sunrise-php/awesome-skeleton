<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="post")
 */
class Post extends AbstractEntity
{

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 *
	 * @var null|int
	 */
	protected $id;

	/**
	 * @ORM\Column(
	 *   type="string",
	 *   length=128,
	 *   nullable=false
	 * )
	 *
	 * @Assert\NotBlank
	 * @Assert\Type("string")
	 * @Assert\Length(max=128)
	 *
	 * @var null|string
	 */
	protected $title;

	/**
	 * @ORM\Column(
	 *   type="text",
	 *   nullable=false
	 * )
	 *
	 * @Assert\NotBlank
	 * @Assert\Type("string")
	 *
	 * @var null|string
	 */
	protected $content;

	/**
	 * {@inheritDoc}
	 *
	 * @var array
	 */
	protected $loadableProperties = [
		'title',
		'content',
	];

	/**
	 * @return null|int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return null|string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return null|string
	 */
	public function getContent()
	{
		return $this->content;
	}
}
