<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\hutkigrosh\view\client;

use esas\cmsgate\hutkigrosh\hro\client\CompletionPanelHutkigroshHRO_v2;

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
}