<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 11:22
 */

namespace esas\cmsgate\hutkigrosh;

use esas\cmsgate\CmsConnectorOpencart;
use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\epos\ConfigFieldsEpos;
use esas\cmsgate\hutkigrosh\lang\TranslatorHutkigrosh;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormOpencart;
use esas\cmsgate\hutkigrosh\view\client\CompletionPanelHutkigroshOpencart;
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
        $this->paysystemConnector = new PaysystemConnectorHutkigrosh();
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
        $managedFields = $this->getManagedFieldsFactory()->getManagedFieldsExcept(AdminViewFields::CONFIG_FORM_COMMON, [
            ConfigFieldsHutkigrosh::paymentMethodNameWebpay(),
            ConfigFieldsHutkigrosh::paymentMethodDetailsWebpay(),
            ConfigFieldsHutkigrosh::useOrderNumber(),
            ConfigFieldsHutkigrosh::shopName()]);
        return $this->cmsConnector->createCommonConfigForm($managedFields);
    }

    public function getCompletionPanel($orderWrapper)
    {
        $completionPanel = new CompletionPanelHutkigroshOpencart($orderWrapper);
        return $completionPanel;
    }

    /**
     * @return mixed
     */
    public function getOpencartRegistry()
    {
        return $this->opencartRegistry;
    }

    function getUrlAlfaclick($orderWrapper)
    {
        return SystemSettingsWrapperOpencart::getInstance()->linkCatalogExtension("alfaclick");
    }

    function getUrlWebpay($orderWrapper)
    {
        return SystemSettingsWrapperOpencart::getInstance()->linkCatalogExtension("pay")
            . "&" . RequestParamsHutkigrosh::ORDER_NUMBER . "=" . $orderWrapper->getOrderNumber()
            . "&" . RequestParamsHutkigrosh::BILL_ID . "=" . $orderWrapper->getExtId();
    }

    public function createModuleDescriptor()
    {
        return new ModuleDescriptor(
            "esas_hutkigrosh",
            new VersionDescriptor("1.14.1", "2021-12-15"),
            "Прием платежей через ЕРИП (сервис Hutkigrosh})",
            "https://bitbucket.esas.by/projects/CG/repos/cmsgate-opencart-hutkigrosh/browse",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
    }

}