<?php

use esas\cmsgate\opencart\ModelExtensionPayment;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/system/library/esas/cmsgate/hutkigrosh/init.php');

/**
 * Only for oc < 2.3 compatibility. Started from version 2.3. script was moved from 'payments' dir to 'extension/payments
 */
class ModelPaymentHutkigrosh extends ModelExtensionPayment
{

}