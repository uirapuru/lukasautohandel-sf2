<?php
namespace LAH\FrontBundle\Manager;

use LAH\FrontBundle\Repository\TranslatableRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TranslatableManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string Class name
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param EntityManager $em    Entity manager
     * @param string        $class Class name
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->class      = $class;
        $this->em         = $em;
        $this->repository = $em->getRepository($this->class);
    }

    /**
     * Sets the repository request default locale.
     *
     * @param ContainerInterface|null $container
     *
     * @throws \InvalidArgumentException if repository is not an instance of TranslatableRepository
     */
    public function setRepositoryLocale(ContainerInterface $container)
    {
        if (null !== $container) {
            if (!$this->repository instanceof TranslatableRepository) {
                throw new \InvalidArgumentException('A TranslatableManager needs to be linked with a TranslatableRepository to sets default locale.');
            }

            if ($container->isScopeActive('request')) {
                $locale = $container->get('request')->getLocale();
                $this->repository->setDefaultLocale($locale);
            }
        }
    }
}
