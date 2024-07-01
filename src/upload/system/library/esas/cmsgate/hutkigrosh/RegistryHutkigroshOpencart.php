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
use esas\cmsgate\hutkigrosh\hro\client\CompletionPanelHutkigroshHRO;
use esas\cmsgate\hro\HROManager;
use esas\cmsgate\wrappers\SystemSettingsWrapperOpencart;

class RegistryHutkigroshOpencart extends RegistryHutkigrosh
{

    /**
     * RegistryOpencart constructor.
     */
    public function __construct()
    {
        $this->cmsConnector = new CmsConnectorOpencart();
        $this->paysystemConnector = new PaysystemConnectorHutkigrosh();
    }

    public function init()
    {
        parent::init();
        HROManager::fromRegistry()->addImplementation(CompletionPanelHutkigroshHRO::class, CompletionPanelHutkigroshOpencart::class);
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
            new VersionDescriptor("2.0.0", "2024-06-18"),
            "Прием платежей через ЕРИП (сервис Hutkigrosh)",
            "https://github.com/esasby/cmsgate-opencart-hutkigrosh",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
    }

}