<?php
namespace Dende\FrontBundle\Entity\Translation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

trait TranslatedTrait
{
    /**
     * @param ArrayCollection $translations
     */
    public function setTranslations(ArrayCollection $translations)
    {
        $this->translations = $translations;
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param $translation
     */
    public function addTranslation(AbstractPersonalTranslation $translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations[] = $translation;
            $translation->setObject($this);
        }
    }

    /**
     * @param $translation
     */
    public function removeTranslation($translation)
    {
        $this->translations->removeElement($translation);
    }

    /**
     * @param string $field
     * @param string $lang
     *
     * @return string
     */
    public function getTranslated($field, $lang = 'pl')
    {
        $translation = $this->getTranslationEntityForLanguage($field, $lang)->first();

        if ($translation) {
            return $translation->getContent();
        }
    }

    /**
     * @param $field
     * @param string $lang
     *
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function getTranslationEntityForLanguage($field, $lang = 'pl')
    {
        $expr     = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expr->andX(
            $expr->eq('field', $field),
            $expr->eq('locale', $lang)
        ));
        $collection = $this->getTranslations();

        return $collection->matching($criteria);
    }

    /**
     * @param string $lang
     *
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function getTranslationEntitiesForLanguage($lang = 'pl')
    {
        $expr     = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expr->andX(
            $expr->eq('locale', $lang)
        ));
        $collection = $this->getTranslations();

        return $collection->matching($criteria);
    }
}
