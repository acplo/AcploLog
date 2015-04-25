<?php

/**
 * Module definition
 *
 * @package   AcploLog
 * @author    Abel Lopes <abel@abellpes.eti.br>
* @link      http://www.abellpes.eti.br Development Blog
 * @link      http://github.com/acplo/AcploLog for the canonical source repository
 * @copyright Copyright (c) 2015-2020 Abel Lopes (http://www.abellpes.eti.br)
 * @license   http://www.abellpes.eti.br/licenca-bsd New BSD license
 */
namespace AcploLog;

use AcploLog\Log\StaticLogger;
use AcploLog\Log\ErrorLogger;
use AcploLog\Log\EntityLogger;
use AcploLog\Log\SqlLogger;
use AcploLog\Options\ModuleOptions;
use Zend\ModuleManager\Feature\LocatorRegisteredInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Module definition
 *
 * @package AcploLog
 * @author Leandro Silva <leandro@leandrosilva.info>
 * @link http://leandrosilva.info Development Blog
 * @link http://github.com/acplo/AcploLog for the canonical source repository
 * @copyright Copyright (c) 2015-2020 Abel Lopes (http://www.abellpes.eti.br)
 * @license http://leandrosilva.info/licenca-bsd New BSD license
 */
class Module implements AutoloaderProviderInterface, LocatorRegisteredInterface
{
    /**
     * Module bootstrap
     */
    public function onBootstrap($e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $config = $sm->get('acplolog_options');

        if ($config->getUseErrorLogger()) {
            $logger = $sm->get('AcploLog\Log\ErrorLogger');

            $eventManager = $e->getApplication()->getEventManager();
            $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, [
                $logger,
                'dispatchError',
            ], - 100);
        }

        if ($config->getUseSqlLogger()) {
            $em = $sm->get('doctrine.entitymanager.orm_default');
            $sqlLogger = $sm->get('AcploLog\Log\SqlLogger');
            $sqlLogger->addLoggerTo($em);
        }
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'acplolog_options' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('Configuration');

                    return new ModuleOptions(isset($config['acplolog']) ? $config['acplolog'] : []);
                },
                'AcploLog\Log\EntityLogger' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('acplolog_options');
                    $logger = new EntityLogger($config->getEntityLoggerFile(), $config->getLogDir());

                    return $logger;
                },
                'AcploLog\Log\ErrorLogger' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('acplolog_options');
                    $logger = new ErrorLogger($config->getErrorLoggerFile(), $config->getLogDir());

                    return $logger;
                },
                'AcploLog\Log\SqlLogger' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('acplolog_options');
                    $logger = new SqlLogger($config->getSqlLoggerFile(), $config->getLogDir());

                    return $logger;
                },
                'AcploLog\Log\StaticLogger' => function (ServiceLocatorInterface $sm) {
                    $config = $sm->get('acplolog_options');
                    $logger = StaticLogger::getInstance($config->getStaticLoggerFile(), $config->getLogDir());

                    return $logger;
                },
            ],
            'aliases' => [
                'acplolog_entitylogger' => 'AcploLog\Log\EntityLogger',
                'acplolog_errorlogger' => 'AcploLog\Log\ErrorLogger',
                'acplolog_sqllogger' => 'AcploLog\Log\SqlLogger',
                'acplolog_staticlogger' => 'AcploLog\Log\StaticLogger',
            ],
        ];
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__.'/../../autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__.'/../../config/module.config.php';
    }
}
