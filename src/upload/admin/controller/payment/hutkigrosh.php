<?php
namespace Opencart\Admin\Controller\Extension\CmsgateOpencartHutkigrosh\Payment;

use esas\cmsgate\opencart\AdminControllerExtensionPayment;

require_once(dirname(__FILE__, 4) . '/system/library/esas/cmsgate/hutkigrosh/init.php');

/**
 * Only for oc < 2.3 compatibility. Started from version 2.3. script was moved from 'payments' dir to 'extension/payments
 */
class Hutkigrosh extends AdminControllerExtensionPayment
{

    /**
     * AdminControllerExtensionPayment constructor.
     */
    public function index()
    {
        parent::index();
    }
}