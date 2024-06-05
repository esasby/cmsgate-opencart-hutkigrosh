<?php
namespace Opencart\Catalog\Model\Extension\CmsgateOpencartHutkigrosh\Payment;

use esas\cmsgate\opencart\ModelExtensionPayment;

require_once(dirname(__FILE__, 4) . '/system/library/esas/cmsgate/hutkigrosh/init.php');

class Hutkigrosh extends ModelExtensionPayment
{
    public function getMethods(array $address): array
    {
        return parent::getMethod($address, false);
    }
}
