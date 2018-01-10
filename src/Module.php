<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BplNav;

use Zend\Mvc\MvcEvent;

class Module {
    
    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }
    
    public function onBootstrap(MvcEvent $e) {
        //For layout according to user role START
        $config = $e->getApplication()->getServiceManager()->get('config');
        if (!isset($config['role_wise_layouts'])) {
            return;
        }
        $e->getApplication()->getEventManager()->getSharedManager()
                ->attach('Zend\Mvc\Application', 'dispatch', function($e) {
                    $sm = $e->getApplication()->getServiceManager();
                    $authService = $sm->get(\CirclicalUser\Service\AccessService::class);
                    $roles = $authService->getRoles();
                    if ($roles === false) {
                        return;
                    }
                    $config = $e->getApplication()->getServiceManager()->get('config');
                    if (isset($config['role_wise_layouts'])) {
                        $key = $this->getMatchedeElement(array_keys($config['role_wise_layouts']), $roles);
                        if ($key !== false) {
                            $e->getViewModel()->setTemplate($config['role_wise_layouts'][$key]);
                        }
                    }
                }, 100);
            $e->getApplication()->getEventManager()->getSharedManager()
            ->attach('Zend\Mvc\Application', 'dispatch.error', function($e) {
                $sm = $e->getApplication()->getServiceManager();
                $authService = $sm->get(\CirclicalUser\Service\AccessService::class);
                $roles = $authService->getRoles();
                if ($roles === false) {
                    return;
                }
                $config = $e->getApplication()->getServiceManager()->get('config');
                if (isset($config['role_wise_layouts'])) {
                    $key = $this->getMatchedeElement(array_keys($config['role_wise_layouts']), $roles);
                    if ($key !== false) {
                        $e->getViewModel()->setTemplate($config['role_wise_layouts'][$key]);
                    }
                }
            }, 100);
        //For layout according to user role END
    }
    
    function getMatchedeElement($arr1, $arr2) {
        foreach ($arr1 as $a) {
            if (in_array($a, $arr2)) {
                return $a;
            }
        }
        return false;
    }
}