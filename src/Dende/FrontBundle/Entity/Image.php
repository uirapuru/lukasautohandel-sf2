<?php
namespace Dende\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="projects")
 * @codeCoverageIgnore
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @var string $name
     */
    protected $name;

    /**
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     * @var string $company
     */
    protected $company;

    /**
     * @ORM\Column(name="tags", type="simple_array", length=100000, nullable=true)
     * @var array $tags
     */
    protected $tags = array();

    /**
     * @ORM\Column(name="features", type="simple_array", length=100000, nullable=true)
     * @var array $features
     */
    protected $features = array();

    /**
     * @ORM\Column(name="pictures", type="simple_array", length=100000, nullable=true)
     * @var array $pictures
     */
    protected $pictures = array();

    /**
     * @ORM\Column(name="description", type="string", length=100000, nullable=true)
     * @var string $pictures
     */
    protected $description;

}
