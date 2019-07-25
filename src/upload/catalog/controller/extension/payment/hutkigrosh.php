<?php
header('Content-Type: text/html; charset=utf-8');

use esas\cmsgate\controllers\ControllerHutkigroshAddBill;
use esas\cmsgate\controllers\ControllerHutkigroshAlfaclick;
use esas\cmsgate\controllers\ControllerHutkigroshCompletionPage;
use esas\cmsgate\controllers\ControllerHutkigroshNotify;
use esas\cmsgate\opencart\CatalogControllerExtensionPayment;
use esas\cmsgate\Registry as HutkigroshRegistry;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\utils\OpencartUtils;
use esas\cmsgate\utils\RequestParams;
use esas\cmsgate\wrappers\OrderWrapperOpencart;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/system/library/esas/cmsgate/init.php');

class ControllerExtensionPaymentHutkiGrosh extends CatalogControllerExtensionPayment
{
    const BASE_PATH = 'extension/payment/hutkigrosh';

    public function index()
    {
        $data['sandbox'] = HutkigroshRegistry::getRegistry()->getConfigWrapper()->isSandbox();
        $data['action'] = $this->url->link(self::BASE_PATH . '/pay');
        $data['continue'] = $this->url->link('checkout/success');

        $this->i18n($data, ['text_sandbox', 'button_confirm', 'text_loading']);
        return $this->load->view($this->getView("hutkigrosh"), $data);
    }


    public function pay()
    {
        try {
            $orderId = $this->session->data['order_id'];
            if (!isset($orderId)) {
                $this->redirect($this->url->link('checkout/checkout'));
                return false;
            }
            $orderWrapper = new OrderWrapperOpencart($orderId, $this->registry);
            // проверяем, привязан ли к заказу billid, если да,
            // то счет не выставляем, а просто прорисовываем старницу
            if (empty($orderWrapper->getExtId())) {
                $controller = new ControllerHutkigroshAddBill();
                $controller->process($orderWrapper);
            }

            $controller = new ControllerHutkigroshCompletionPage(
                $this->url->link(self::BASE_PATH . '/alfaclick'),
                $this->registry->get("url")->link(self::BASE_PATH . '/pay'));
            $completionPanel = $controller->process($orderId);

            $data['completionPanel'] = $completionPanel;

            $this->document->setTitle($this->language->get('heading_title'));
            $this->addCommon($data);
            $data['button_continue_link'] = $this->url->link('checkout/success');
            $this->i18n($data, ['button_continue']);
            $this->response->setOutput(
                $this->load->view(
                    $this->getView("hutkigrosh_checkout_success"), $data));

        } catch (Throwable $e) {
            Logger::getLogger("payment")->error("Exception:", $e);
            return $this->failure($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("payment")->error("Exception:", $e);
            return $this->failure($e->getMessage());
        }
    }

    public function alfaclick()
    {
        try {
            $controller = new ControllerHutkigroshAlfaclick();
            $controller->process($this->request->post[RequestParams::BILL_ID], $this->request->post[RequestParams::PHONE]);
        } catch (Throwable $e) {
            Logger::getLogger("alfaclick")->error("Exception: ", $e);
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("alfaclick")->error("Exception: ", $e);
        }
    }

    public function notify()
    {
        try {
            $billId = $this->request->get[RequestParams::PURCHASE_ID];
            $controller = new ControllerHutkigroshNotify();
            $controller->process($billId);
        } catch (Throwable $e) {
            Logger::getLogger("notify")->error("Exception:", $e);
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("notify")->error("Exception:", $e);
        }
    }


}
