<?php
namespace Opencart\Catalog\Controller\Extension\CmsgateOpencartHutkigrosh\Payment;

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
use esas\cmsgate\utils\OpencartVersion;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;

require_once(dirname(__FILE__, 4) . '/system/library/esas/cmsgate/hutkigrosh/init.php');

header('Content-Type: text/html; charset=utf-8');

/**
 * Only for oc < 2.3 compatibility. Started from version 2.3. script was moved from 'payments' dir to 'extension/payments
 */
class Hutkigrosh extends CatalogControllerExtensionPayment
{

    public function index()
    {
        return parent::index();
    }

    protected function addPaySystemIndexData(&$data, $orderWrapper)
    { // убрано в CatalogControllerExtensionPayment
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
            $configWrapper = ConfigWrapperHutkigrosh::fromRegistry();
            if (!$configWrapper->isInstructionsSectionEnabled()
                && !$configWrapper->isQRCodeSectionEnabled()
                && !$configWrapper->isWebpaySectionEnabled()
                && !$configWrapper->isAlfaclickSectionEnabled()) {
                $this->response->redirect(SystemSettingsWrapperOpencart::getInstance()->linkCatalogCheckoutSuccess());
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
            $data['completionPanel'] = $controller->process($orderId);
            $data['old_style'] = in_array(OpencartVersion::getVersion(), array(OpencartVersion::v2_1_x, OpencartVersion::v2_3_x, OpencartVersion::v3_x)) ? true : false;
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
