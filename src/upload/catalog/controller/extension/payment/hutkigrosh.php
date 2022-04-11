<?php
header('Content-Type: text/html; charset=utf-8');

use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshAddBill;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshAlfaclick;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshCompletionPanel;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshNotify;
use esas\cmsgate\hutkigrosh\RegistryHutkigroshOpencart;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\opencart\CatalogControllerExtensionPayment;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\view\ViewBuilderOpencart;
use esas\cmsgate\wrappers\SystemSettingsWrapperOpencart;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/system/library/esas/cmsgate/hutkigrosh/init.php');

class ControllerExtensionPaymentHutkiGrosh extends CatalogControllerExtensionPayment
{
    public function index()
    {
        return parent::index();
    }

    /**
     * @param $data
     * @param $orderWrapper
     * @throws Throwable
     */
    protected function addPaySystemIndexData(&$data, $orderWrapper)
    {
        $data['confirmOrderForm'] = ViewBuilderOpencart::elementConfirmOrderForm($orderWrapper);
    }


    public function pay()
    {
        try {
            $orderId = $this->session->data['order_id'];
            if (!isset($orderId)) {
                $this->response->redirect(SystemSettingsWrapperOpencart::getInstance()->linkCatalogCheckout());
                return false;
            }
            $orderWrapper = RegistryHutkigroshOpencart::getRegistry()->getOrderWrapper($orderId);
            // проверяем, привязан ли к заказу billid, если да,
            // то счет не выставляем, а просто прорисовываем старницу
            if (empty($orderWrapper->getExtId())) {
                $controller = new ControllerHutkigroshAddBill();
                $controller->process($orderWrapper);
            }
            $controller = new ControllerHutkigroshCompletionPanel();
            $completionPanel = $controller->process($orderId);
            $data['completionPanel'] = $completionPanel;
            $this->document->setTitle($this->language->get('heading_title'));
            $this->addCommon($data);
            $this->addCheckoutContinueButton($data);
            $this->response->setOutput(
                $this->load->view(
                    $this->getView("hutkigrosh_checkout_success"), $data));

        } catch (Throwable $e) {
            return $this->redirectFailure("pay", $e);
        } catch (Exception $e) { // для совместимости с php 5
            return $this->redirectFailure("pay", $e);
        }
    }

    public function alfaclick()
    {
        try {
            $controller = new ControllerHutkigroshAlfaclick();
            $controller->process($this->request->post[RequestParamsHutkigrosh::BILL_ID], $this->request->post[RequestParamsHutkigrosh::PHONE]);
        } catch (Throwable $e) {
            Logger::getLogger("alfaclick")->error("Exception: ", $e);
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("alfaclick")->error("Exception: ", $e);
        }
    }

    public function notify()
    {
        try {
            $billId = $this->request->get[RequestParamsHutkigrosh::PURCHASE_ID];
            $controller = new ControllerHutkigroshNotify();
            $controller->process($billId);
        } catch (Throwable $e) {
            Logger::getLogger("notify")->error("Exception:", $e);
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("notify")->error("Exception:", $e);
        }
    }



}
