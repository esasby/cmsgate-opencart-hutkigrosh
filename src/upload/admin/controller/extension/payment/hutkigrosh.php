<?php

use esas\cmsgate\opencart\AdminControllerExtensionPayment;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/system/library/esas/cmsgate/init.php');

class ControllerExtensionPaymentHutkiGrosh extends AdminControllerExtensionPayment
{

    /**
     * ControllerExtensionPaymentHutkiGrosh constructor.
     */
    public function index()
    {
        parent::index();
    }
}