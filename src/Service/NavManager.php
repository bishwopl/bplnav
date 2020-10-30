<?php

namespace BplNav\Service;
use Laminas\Navigation\Service\DefaultNavigationFactory;

class NavManager extends DefaultNavigationFactory{

    protected function getPages(\Psr\Container\ContainerInterface $container) {
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
        foreach(array_keys($pages) as $key){
            $allowedSuper = true;
            if(!isset($pages[$key]['resource'])){
                throw new \Exception("You must specify resource name (usually controller name) for menu item");
            }
            if(!isset($pages[$key]['action'])){
                throw new \Exception("You must specify action for menu item");
            }
            if(!$authService->canAccessAction($pages[$key]['resource'],$pages[$key]['action'])){
                unset($pages[$key]);
                $allowedSuper = false;
            }

            if(isset($pages[$key]['pages']) && $allowedSuper){
                $this->filterPages($authService, $pages[$key]['pages']);
            }
        }
        return;
    }
}
