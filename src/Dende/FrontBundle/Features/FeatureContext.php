<?php
namespace Dende\FrontBundle\Features;

use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext extends MinkContext implements KernelAwareContext
{
    private $baseUrl;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function __construct($baseUrl, $session)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @Then /^I press "([^"]*)" in "([^"]*)" row (\d+) times$/
     */
    public function iPressInRowTimes($linkText, $rowLabel, $times)
    {
        $linkText = $this->fixStepArgument($linkText);
        $rowLabel = $this->fixStepArgument($rowLabel);
        $times = $this->fixStepArgument($times);

        $page = $this->getSession()->getPage();

        $labels = $page->findAll("css", "label");

        foreach ($labels as $label) {
            /*
             * @var NodeElement
             */
            if ($label->getText() == $rowLabel) {
                /*
                 * @var NodeElement
                 */
                $row = $label->getParent();
                continue;
            }
        }
    }

    /**
     * @Then /^I add (\d+) images$/
     */
    public function iAddImages($amount)
    {
        $amount = $this->fixStepArgument($amount);
        $page = $this->getSession()->getPage();
        $button = $page->find("css", "div#dende_form_car_images");

        if (count($button) == 0) {
            throw new Exception("Element div#dende_form_car_images not found!");
        }

        $row = $button->getParent();
        $link = $row->find("css", "a.item_add");

        for ($a = 0; $a < $amount; $a++) {
            $link->click();
        }

        $this->assertNumElements(5, "div#dende_form_car_images input[type=file]");
    }

    /**
     * @Given /^I remove (\d+) images$/
     */
    public function iRemoveImages($amount)
    {
        $amount = $this->fixStepArgument($amount);
        $page = $this->getSession()->getPage();
        $row = $page->find("css", "div#dende_form_car_images")->getParent();
        $links = array_slice($row->findAll("css", "a.item_remove"), 0, $amount);

        foreach ($links as $link) {
            $link->click();
        }
    }

    /**
     * @Given /^I have (\d+) images$/
     */
    public function iHaveImages($arg1)
    {
        $this->assertNumElements(2, "div#dende_form_car_images > ul > li");
    }

    /**
     * @Given /^images list is empty$/
     */
    public function imagesListIsEmpty()
    {
        $this->assertNumElements(0, "div#dende_form_car_images > ul > li");
    }

    /**
     * @Given /^prices list is empty$/
     */
    public function pricesListIsEmpty()
    {
        $this->assertNumElements(0, "div#dende_form_car_prices > ul > li");
    }

    /**
     * @Then /^I add (\d+) prices$/
     */
    public function iAddPrices($amount)
    {
        $amount = $this->fixStepArgument($amount);
        $page = $this->getSession()->getPage();

        $button = $page->find("css", "div#dende_form_car_prices");

        if (count($button) == 0) {
            throw new Exception("Element div#dende_form_car_prices not found!");
        }

        $row = $button->getParent();

        $link = $row->find("css", "a.item_add");

        for ($a = 0; $a < $amount; $a++) {
            $link->click();
        }

        $this->assertNumElements($amount, "div#dende_form_car_prices > ul > li");
    }

    /**
     * @Given /^I remove (\d+) prices$/
     */
    public function iRemovePrices($amount)
    {
        $amount = $this->fixStepArgument($amount);
        $page = $this->getSession()->getPage();
        $row = $page->find("css", "div#dende_form_car_prices")->getParent();
        $links = array_slice($row->findAll("css", "a.item_remove"), 0, $amount);

        foreach ($links as $link) {
            $link->click();
        }
    }

    /**
     * @Given /^I have (\d+) prices$/
     */
    public function iHavePrices($arg1)
    {
        $this->assertNumElements($arg1, "div#dende_form_car_prices > ul > li");
    }

    /**
     * @Then /^I can't see "([^"]*)" row$/
     */
    public function iCanTSeeRow($rowLabel)
    {
        $rowLabel = $this->fixStepArgument($rowLabel);

        $page = $this->getSession()->getPage();

        $labels = $page->findAll("css", "label");

        $row = null;

        foreach ($labels as $label) {
            /*
             * @var NodeElement
             */
            if (trim($label->getText()) == $rowLabel) {
                /*
                 * @var NodeElement
                 */
                $row = $label->getParent();
                continue;
            }
        }

        if (!is_null($row) && !$row->isVisible()) {
            throw new Exception("Row '$rowLabel' is visible");
        }
    }

    /**
     * @Given /^I can see "([^"]*)" row$/
     */
    public function iCanSeeRow($rowLabel)
    {
        return !$this->iCanTSeeRow($rowLabel);
    }

    /**
     * @Given /^I can see "(.*?)" element$/
     */
    public function iCanSeeElement($elementSelector)
    {
        $page = $this->getSession()->getPage();

        $elements = $page->findAll("css", $elementSelector);

        foreach ($elements as $element) {
            /*
             * @var NodeElement
             */
            if (!$element->isVisible()) {
                return false;
            }
        }
    }

    /**
     * @Given /^I click "(.*?)"$/
     */
    public function iClick($selector)
    {
        $page = $this->getSession()->getPage();
        $elements = $page->findAll("css", $selector);

        foreach ($elements as $element) {
            $element->click();
        }
    }

    /**
     * @Given /^I set (\d+) price currency to "([^"]*)"$/
     */
    public function iSetPriceCurrencyTo($number, $value)
    {
        $page = $this->getSession()->getPage();
        $button = $page->find('css', sprintf('div#dende_form_car_prices_%d button.dropdown-toggle', $number-1));

        $button->press();

        $link = $page->find(
            'css',
            sprintf(
                'div#dende_form_car_prices_%d ul.dropdown-menu a.changeCurrencyLink[data-code="%s"]',
                $number-1,
                $value
            )
        );

        $link->click();
    }

    /**
     * @Then /^I can see (\d+) price currency set to "([^"]*)"$/
     */
    public function iCanSeePriceCurrencySetTo($number, $value)
    {
        $page = $this->getSession()->getPage();
        $button = $page->find('css', sprintf('div#dende_form_car_prices_%d button.currencySymbol', $number-1));

        $val = $button->getText();

        if ($val !== $value) {
            throw new Exception("Currency button $number has value $val and it's other than '$value'");
        }
    }

    /**
     * @Given /^I submit form "([^"]*)"$/
     */
    public function iSubmitForm($arg1)
    {
        $form = $this->getSession()->getPage()->find('css', "form#".$arg1);

        if (!$form) {
            throw new Exception("Form #$arg1 not found");
        }

        $form->submit();
    }

    /**
     * @Given /^I am logged in as "([^"]*)" using "([^"]*)"$/
     */
    public function iAmLoggedInAsUsing($username, $password)
    {
        $username = $this->fixStepArgument($username);
        $password = $this->fixStepArgument($password);

        $this->visitPath("/login");
        $this->fillField("_username", $username);
        $this->fillField("_password", $password);
        $this->iSubmitForm("loginForm");
    }

    public function assertElementContains($element, $value)
    {
        $value = $this->fixStepArgument($value);
        $el = $this->getSession()->getPage()->find("css", $element);

        if ($el->getText() !== $value) {
            throw new Exception(sprintf("Element %s should have '%s' value, has '%s'", $element, $value, $el->getText()));
        }
    }

    /**
     * @Then /^I can see only "([^"]*)" cars models$/
     */
    public function iCanSeeOnlyCarsModels($brand)
    {
        $page = $this->getSession()->getPage();
        $brand = $this->fixStepArgument($brand);
        $models = $page->findAll("css", "select#car_filters_model option");

        array_shift($models);

        foreach ($models as $model) {
            var_dump($model->getText(), $brand);

            if (!strstr($model->getText(), $brand)) {
                throw new Exception("Found other models than ".$brand);
            }
        }
    }

    /**
     * @When /^I select "([^"]*)" brand$/
     */
    public function iSelectBrand($brand)
    {
        $this->selectOption('car_filters_brand', $brand);
        sleep(1);
    }

    /**
     * @Then /^I dont see any models$/
     */
    public function iDontSeeAnyModels()
    {
        $selector =  'select#car_filters_model option';
        $hasModels = $this->getSession()->getPage()->has("css", $selector);

        if ($hasModels) {
            $models = [];
            $elements = $this->getSession()->getPage()->findAll("css", $selector);

            foreach ($elements as $element) {
                $models[] = $element->getText();
            }

            if ($models == array(" ")) {
                return;
            }

            throw new Exception(sprintf("There should be no options in models select but found %d models: %s", count($elements), implode(", ", $models)));
        }
    }

    /**
     * @Then /^I can see models "([^"]*)"$/
     */
    public function iCanSeeModels($arg1)
    {
        $expectedElements = array_map("trim", explode(",", $arg1));

        $selector =  'select#car_filters_model option';

        $hasModels = $this->getSession()->getPage()->has("css", $selector);

        if ($hasModels) {
            $elements = $this->getSession()->getPage()->findAll("css", $selector);
            $actualElements = array_map(function ($el) {
                return $el->getText();

            }, $elements);

            foreach ($expectedElements as $model) {
                if (!in_array($model, $actualElements)) {
                    throw new Exception(sprintf("There are no %s model in options. Only %s exists.", $model, implode(", ", $actualElements)));
                }
            }
        } else {
            throw new Exception("There are no options in models select");
        }
    }

    /**
     * @When /^I deselect brand$/
     */
    public function iDeselectBrand()
    {
        $this->selectOption('car_filters_brand', null);
        sleep(1);
    }

    /**
     * @When /^I select "([^"]*)" type$/
     */
    public function iSelectType($arg1)
    {
        $this->selectOption('car_filters_variant', $arg1);
    }

    /**
     * @Given /^I submit search form$/
     */
    public function iSubmitSearchForm()
    {
        $this->iSubmitForm('form1');
        sleep(1);
    }

    /**
     * @Then /^I can see (\d+) cars in results$/
     */
    public function iCanSeeCarsInResults($count)
    {
        $count = (int) $this->fixStepArgument($count);
        $page = $this->getSession()->getPage();
        $hasResults = $page->has('css', 'ul.search-results li');

        if ($hasResults) {
            $results = $page->findAll('css', 'ul.search-results li');
            if (count($results) !== $count) {
                throw new Exception(sprintf("Results found differs from test. Should be %d, found %d", $count, count($results)));
            }
        } elseif ($count > 0) {
            throw new Exception("No results from filtered cars");
        }
    }

    /**
     * @Then /^I don't see any cars in results$/
     */
    public function iDonTSeeAnyCarsInResults()
    {
        $this->iCanSeeCarsInResults(0);
    }

    /**
     * @When /^I reset search form$/
     */
    public function iResetSearchForm()
    {
        $this->selectOption('car_filters_variant', null);
        $this->selectOption('car_filters_brand', null);
        $this->selectOption('car_filters_model', null);
    }

    /**
     * @Given /^I can see (.*) cars in results caption$/
     */
    public function iCanSeeCarsInResultsCaption($count)
    {
        $count = (int) $this->fixStepArgument($count);
        $selector = 'span#list-result-count';
        $page = $this->getSession()->getPage();

        $amount = (int) $page->find('css', $selector)->getText();

        if ($amount != $count) {
            throw new Exception(sprintf("Count in list caption differs from the test. Should be %d, found %d", $count, $amount));
        }
    }

    /**
     * @Given /^I select "([^"]*)" model$/
     */
    public function iSelectModel($arg1)
    {
        $this->selectOption('car_filters_model', $arg1);
    }
}
