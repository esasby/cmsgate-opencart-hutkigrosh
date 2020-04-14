<?php
require_once(dirname(dirname(__FILE__)) . '/vendor/esas/cmsgate-core/src/esas/cmsgate/CmsPlugin.php');
use esas\cmsgate\CmsPlugin;
use esas\cmsgate\RegistryHutkigroshOpencart;


(new CmsPlugin(dirname(dirname(__FILE__)) . '/vendor', dirname(dirname(dirname(__FILE__)))))
    ->setRegistry(new RegistryHutkigroshOpencart(isset($registry) ? $registry : $this->registry))
    ->init();
