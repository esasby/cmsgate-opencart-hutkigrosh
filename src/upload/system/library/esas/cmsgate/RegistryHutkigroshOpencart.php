<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 11:22
 */

namespace esas\cmsgate;


use esas\cmsgate\lang\LocaleLoaderOpencart;
use esas\cmsgate\lang\TranslatorHutkigrosh;
use esas\cmsgate\view\admin\ConfigFormOpencart;
use esas\cmsgate\view\admin\ManagedFieldsHutkigrosh;
use esas\cmsgate\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\wrappers\OrderWrapper;
use esas\cmsgate\wrappers\OrderWrapperOpencart;
use esas\cmsgate\view\client\CompletionPanelOpencart;

class RegistryHutkigroshOpencart extends RegistryHutkigrosh
{
    private $opencartRegistry;

    /**
     * RegistryOpencart constructor.
     * @param $registry
     */
    public function __construct($opencartRegistry)
    {
        $this->opencartRegistry = $opencartRegistry;
    }

    public function createConfigWrapper()
    {
        $configStorageOpencart = new ConfigStorageOpencart($this->opencartRegistry);
        return new ConfigWrapperHutkigrosh($configStorageOpencart);
    }

    public function createTranslator()
    {
        $localeLoader = new LocaleLoaderOpencart($this->opencartRegistry);
        return new TranslatorHutkigrosh($localeLoader);
    }



    /**
     * По локальному номеру счета (номеру заказа) возвращает wrapper
     * @param $orderId
     * @return OrderWrapper
     */
    public function getOrderWrapper($orderNumber)
    {
        return new OrderWrapperOpencart($orderNumber, $this->opencartRegistry);
    }

    public function createConfigForm()
    {
        $managedFieldsHutkigrosh = new ManagedFieldsHutkigrosh();
        $managedFieldsHutkigrosh->addAllExcept([
            ConfigFieldsHutkigrosh::shopName(),
            ConfigFieldsHutkigrosh::paymentMethodName(),
            ConfigFieldsHutkigrosh::paymentMethodDetails()]);
        return new ConfigFormOpencart($managedFieldsHutkigrosh, $this->opencartRegistry);
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
}