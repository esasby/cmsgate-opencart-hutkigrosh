<?php
require_once(dirname(__FILE__) . '/vendor/esas/cmsgate-core/src/esas/cmsgate/CmsPlugin.php');
use esas\cmsgate\CmsPlugin;
use esas\cmsgate\hutkigrosh\RegistryHutkigroshOpencart;

(new CmsPlugin(dirname(__FILE__) . '/vendor', dirname(__FILE__, 4)))
    ->setRegistry(new RegistryHutkigroshOpencart())
    ->init();