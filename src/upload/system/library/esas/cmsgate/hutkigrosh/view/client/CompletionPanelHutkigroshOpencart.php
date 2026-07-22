<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\hutkigrosh\view\client;

use esas\cmsgate\hutkigrosh\hro\client\CompletionPanelHutkigroshHRO_v2;
use esas\cmsgate\hro\accordions\AccordionTabHROFactory;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\OpencartVersion;

class CompletionPanelHutkigroshOpencart extends CompletionPanelHutkigroshHRO_v2
{
    public function getCssClass4MsgSuccess()
    {
        return "alert alert-info";
    }

    public function getCssClass4MsgUnsuccess()
    {
        return "alert alert-danger";
    }

    public function getCssClass4Button()
    {
        return "btn btn-primary";
    }

    public function getCssClass4TabsGroup()
    {
        return "panel-group";
    }

    public function getCssClass4Tab()
    {
        return "panel panel-default";
    }

    public function getCssClass4TabHeader()
    {
        return "panel-heading";
    }

    public function getCssClass4TabHeaderLabel()
    {
        return "panel-title";
    }

    public function getCssClass4TabBody()
    {
        return "panel-collapse";
    }

    public function getCssClass4TabBodyContent()
    {
        return "panel-body";
    }


    public function getCssClass4AlfaclickForm()
    {
        return "form-inline";
    }

    public function getCssClass4FormInput()
    {
        return "form-control";
    }

    public function getModuleCSSFilePath()
    {
        return dirname(__FILE__) . "/hiddenRadio.css";
    }

    public function elementAlfaclickTabContent()
    {
        return parent::elementAlfaclickTabContent();
        return
            element::content(
                element::div(
                    attribute::clazz("row mb-3"),
                    attribute::id("alfaclick_details"),
                    element::label(
                        attribute::forr("phone"),
                        attribute::clazz("col-md-4 col-form-label"),
                        element::content($this->getAlfaclickDetails())
                    ),
                    element::div(
                        attribute::clazz("col-md-8"),
                        element::input(
                            attribute::id("billID"),
                            attribute::type('hidden'),
                            attribute::value($this->alfaclickBillId)),
                        element::input(
                            attribute::id("phone"),
                            attribute::type('tel'),
                            attribute::clazz($this->getCssClass4FormInput()),
                            attribute::value($this->alfaclickPhone)
                        )
                    )
                ),
                element::div(
                    attribute::clazz("text-end"),
                    element::button(
                        attribute::id("alfaclick_button"),
                        attribute::clazz("hutkigrosh-button " . $this->getCssClass4Button()),
                        attribute::type("button"),
                        element::content($this->getAlfaclickButtonLabel())
                    )
                ),
                element::includeFile(dirname(__FILE__) . "/alfaclickJs.php", ["completionPanel" => $this])
            );
    }

    public function build()
    {
        if (!$this->orderCanBePayed) {
            return MessagesPanelHROFactory::findBuilder()->build();
        }
        $this->onlyOneTab = false;

        switch (OpencartVersion::getVersion()) {
            case OpencartVersion::v2_1_x:
            case OpencartVersion::v2_3_x:
            case OpencartVersion::v3_x:
                return element::content(
                    element::h4(
                        attribute::clazz($this->getCssClass4CompletionTextDiv()),
                        element::content($this->completionText)
                    ),
                    $this->elementTabs(),
                    $this->addCss()
                );
            case OpencartVersion::v4_x:
                return element::content(
                    element::div(
                        attribute::id("completion-text"),
                        attribute::clazz($this->getCssClass4CompletionTextDiv()),
                        element::content($this->completionText)
                    ),
                    $this->elementTabs(),
                    $this->addCss()
                );
        }

    }

    protected function accordionBuilder()
    {
        return AccordionOpencartHROFactory::findBuilder();
    }

    public function elementTabs()
    {
        switch (OpencartVersion::getVersion()) {
            case OpencartVersion::v2_1_x:
            case OpencartVersion::v2_3_x:
            case OpencartVersion::v3_x:
                return element::div(
                    attribute::id('accordion'),
                    attribute::clazz('panel-group'),
                    $this->elementInstructionsTab(),
                    $this->elementQRCodeTab(),
                    $this->elementWebpayTab(),
                    $this->elementAlfaclickTab()
                );
            case OpencartVersion::v4_x:
                $accordion = $this->accordionBuilder()
                    ->setId(self::TABS_ID)
                    ->addTab($this->elementInstructionsTab())
                    ->addTab($this->elementQRCodeTab())
                    ->addTab($this->elementWebpayTab())
                    ->addTab($this->elementAlfaclickTab());
                return $accordion->build();
        }

    }

    public function elementTab($key, $header, $body, $selectable = true)
    {
        switch (OpencartVersion::getVersion()) {
            case OpencartVersion::v2_1_x:
            case OpencartVersion::v2_3_x:
            case OpencartVersion::v3_x:
                return element::div(
                    attribute::clazz('panel panel-default'),
                    element::div(
                        attribute::clazz('panel-heading'),
                        element::h4(
                            attribute::clazz('panel-title'),
                            element::a(
                                attribute::href('#collapse-' . $key),
                                attribute::clazz('accordion-toggle '),
                                attribute::data_toggle('collapse'),
                                attribute::data_parent('#accordion'),
                                element::content($header),
                                element::i(
                                    attribute::clazz('fa fa-caret-down'),
								)
                            )
                        )
                    ),
                    element::div(
                        attribute::clazz('panel-collapse collapse' . ($this->isTabChecked($key) ? 'in' : '')),
                        attribute::id('collapse-' . $key),
                        element::div(
                            attribute::clazz('panel-body'),
                            element::content($body),
						)
                    )
                );
            case OpencartVersion::v4_x:
                return AccordionTabHROFactory::findBuilder()
                    ->setChecked($this->isTabChecked($key))
                    ->setHeader($header)
                    ->setBody($body)
                    ->setKey($key);
        }

    }
}