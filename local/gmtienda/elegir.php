<?php
require_once(dirname(__FILE__).'/../../config.php');
$gmuserid = optional_param('gmuserid', 0, PARAM_INT);
$objetoid = optional_param('objetoid', 0, PARAM_INT);

echo "El usuario $gmuserid quiere elegir el objeto $objetoid";