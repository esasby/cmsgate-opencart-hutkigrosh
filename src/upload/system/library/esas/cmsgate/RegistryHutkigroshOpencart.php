<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 11:22
 */

namespace esas\cmsgate;

use esas\cmsgate\hutkigrosh\ConfigFieldsHutkigrosh;
use esas\cmsgate\hutkigrosh\lang\TranslatorHutkigrosh;
use esas\cmsgate\hutkigrosh\RegistryHutkigrosh;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\hutkigrosh\view\admin\ManagedFieldsHutkigrosh;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\view\admin\ConfigFormOpencart;
use esas\cmsgate\view\client\CompletionPanelOpencart;
use esas\cmsgate\wrappers\SystemSettingsWrapperOpencart;

class RegistryHutkigroshOpencart extends RegistryHutkigrosh
{
    private $opencartRegistry;

    /**
     * RegistryOpencart constructor.
     * @param $opencartRegistry
     */
    public function __construct($opencartRegistry)
    {
        $this->opencartRegistry = $opencartRegistry;
        $this->cmsConnector = new CmsConnectorOpencart($opencartRegistry);
    }

    /**
     * Переопделение для упрощения типизации
     * @return RegistryHutkigroshOpencart
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    /**
     * Переопделение для упрощения типизации
     * @return ConfigFormOpencart
     */
    public function getConfigForm()
    {
        return parent::getConfigForm();
    }

    /**
     * @return SystemSettingsWrapperOpencart
     */
    public function getSystemSettingsWrapper()
    {
        return parent::getSystemSettingsWrapper();
    }

    public function createConfigWrapper()
    {
        return new ConfigWrapperHutkigrosh();
    }

    public function createTranslator()
    {
        return new TranslatorHutkigrosh();
    }

    public function createConfigForm()
    {
        $managedFieldsHutkigrosh = new ManagedFieldsHutkigrosh();
        $managedFieldsHutkigrosh->addAllExcept([
            ConfigFieldsHutkigrosh::shopName()]);
        return $this->cmsConnector->createCommonConfigForm($managedFieldsHutkigrosh);
    }

    public function getCompletionPanel($orderWrapper)
    {
        $completionPanel = new CompletionPanelOpencart($orderWrapper);
        return $completionPanel;
    }

    /**
     * @return mixed
     */
    public function getOpencartRegistry()
    {
        return $this->opencartRegistry;
    }

    function getUrlAlfaclick($orderId)
    {
        return SystemSettingsWrapperOpencart::getInstance()->linkCatalogExtension("alfaclick");
    }

    function getUrlWebpay($orderId)
    {
        $orderWrapper = RegistryHutkigroshOpencart::getRegistry()->getOrderWrapper($orderId);
        return SystemSettingsWrapperOpencart::getInstance()->linkCatalogExtension("pay")
            . "&" . RequestParamsHutkigrosh::ORDER_NUMBER . "=" . $orderWrapper->getOrderNumber()
            . "&" . RequestParamsHutkigrosh::BILL_ID . "=" . $orderWrapper->getExtId();
    }

}