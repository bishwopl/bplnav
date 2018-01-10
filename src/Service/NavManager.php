<?php

namespace BplNav\Service;
use Zend\Navigation\Service\DefaultNavigationFactory;

class NavManager extends DefaultNavigationFactory{

    protected function getPages(\Interop\Container\ContainerInterface $container) {
        if (null === $this->pages) {
            $configuration = $container->get('config');

            if (! isset($configuration['navigation'])) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (! isset($configuration['navigation'][$this->getName()])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }

            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
            
            //added 
            $authService = $container->get(\CirclicalUser\Service\AccessService::class);
            $this->filterPages($authService, $pages);
            //added end

            $this->pages = $this->preparePages($container, $pages);
        }
        
        return $this->pages;
    }
    
    protected function filterPages($authService, &$pages){
        foreach($pages as $key=>$page){
            $allowedSuper = true;
            if(!isset($page['resource'])){
                throw new \Exception("You must specify resource name (usually controller name) for menu item");
            }
            if(!isset($page['action'])){
                throw new \Exception("You must specify action for menu item");
            }
            if(!$authService->canAccessAction($page['resource'],$page['action'])){
                unset($pages[$key]);
                $allowedSuper = false;
            }

            if(isset($page['pages']) && $allowedSuper){
                $this->filterPages($authService, $page['pages']);
            }
        }
        return;
    }
}