<?php
namespace Dende\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("currency")
 * @ORM\Entity()
 */
class Currency
{
    const TYPE_PREFIX = "symbol.prefix";
    const TYPE_SUFFIX = "symbol.suffix";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @var string $symbol
     */
    protected $symbol;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $type
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $code
     */
    protected $code;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}