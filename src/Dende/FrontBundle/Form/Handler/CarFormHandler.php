<?php
namespace Dende\FrontBundle\Form\Handler;

use Dende\FrontBundle\Entity\Car;

final class CarFormHandler
{
    /**
     * @var Car
     */
    private $car;

    /**
     * @var ProcessColor
     */
    private $processColors;

    /**
     * @var ProcessType
     */
    private $processTypes;

    /**
     * @var ProcessModel
     */
    private $processModels;

    /**
     * @var ProcessImages
     */
    private $processImages;

    /**
     * @var ProcessPrices
     */
    private $processPrices;

    /**
     * @param ProcessColor $processColors
     */
    public function setProcessColors(ProcessColor $processColors)
    {
        $this->processColors = $processColors;
    }

    /**
     * @param ProcessType $processTypes
     */
    public function setProcessTypes(ProcessType $processTypes)
    {
        $this->processTypes = $processTypes;
    }

    /**
     * @param ProcessModel $processModels
     */
    public function setProcessModels(ProcessModel $processModels)
    {
        $this->processModels = $processModels;
    }

    /**
     * @param ProcessImages $processImages
     */
    public function setProcessImages(ProcessImages $processImages)
    {
        $this->processImages = $processImages;
    }

    /**
     * @param ProcessPrices $processPrices
     */
    public function setProcessPrices(ProcessPrices $processPrices)
    {
        $this->processPrices = $processPrices;
    }

    /**
     * @param Car $car
     */
    public function setCar(Car $car)
    {
        $this->car = $car;
        return $this;
    }

    public function handle($form)
    {
        $this->processImages->setCar($this->car);
        $this->processPrices->setCar($this->car);

        $this->processColors->setForm($form)->addColor();
        $this->processTypes->setForm($form)->addType();
        $this->processModels->setForm($form)->addModel();

        if ($this->car->getId() !== null) {
            $this->processPrices->removeUnused();
            $this->processImages->removeUnused();
        }

        $this->processImages->processUploaded();
    }
}
