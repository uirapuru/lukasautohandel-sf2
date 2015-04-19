<?php
namespace LAH\MainBundle\Twig;

class Revision extends \Twig_Extension
{
    private $revisionFile;

    /**
     * @var \DateTime
     */
    private $revisionDate;

    /**
     * @param mixed $revisionFile
     */
    public function setRevisionFile($revisionFile)
    {
        if (!file_exists($revisionFile)) {
            return;
        }

        $this->revisionFile = file_get_contents($revisionFile);

        $this->revisionDate = new \DateTime();
        $this->revisionDate->setTimestamp(filectime($revisionFile));
    }

    public function getFunctions()
    {
        $content = $this->revisionFile;
        $date = $this->revisionDate;

        return [
            new \Twig_SimpleFunction('revision', function () use ($content) {
                return $content;
            }),
            new \Twig_SimpleFunction('revisionDate', function () use ($date) {
                return $date;
            }),
        ];
    }

    public function getName()
    {
        return 'revision';
    }
}
