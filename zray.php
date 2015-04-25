<?php
namespace AcploLog;

class AcploZray
{
    public function storeLog($context, &$storage)
    {
        $msg = $context["functionArgs"][0];
        list($usec, $sec) = explode(" ", microtime());
        $date = date("Y-m-d H:i:s", $sec).substr($usec, 1);
        $storage['AcploLog'][] = array('date' => $date, 'message' => $msg);
    }
}

$acploStorage = new \AcploLog\AcploZray();
$acplolog = new \ZRayExtension("acplolog");
$acplolog->setMetadata(array(
    'logo' => __DIR__.DIRECTORY_SEPARATOR.'logo.png',
));
$acplolog->setEnabledAfter('Zend\Mvc\Application::init');
$acplolog->traceFunction("AcploLog\\Log\\StaticLogger::save",  array($acploStorage, 'storeLog'), function () {});
