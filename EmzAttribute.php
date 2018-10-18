<?php

namespace EmzAttribute;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Shopware-Plugin EmzAttribute.
 */
class EmzAttribute extends Plugin
{
    /**
    * @param ContainerBuilder $container
    */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }

    public function install(InstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->update('s_articles_attributes', 'emz_fsk', 'boolean', [
            'label' => 'Artikel ist ein FSK Artikel',
            'displayInBackend' => true,
            'translateable' => true,
            'position' => 1,
            'custom' => false,
        ]);

        $metaDataCache = $this->container->get('models')->getConfiguration()->getMetadataCacheImpl();
        $metaDataCache->deleteAll();
        $this->container->get('models')->generateAttributeModels(['s_articles_attributes']);

        parent::install($context);

        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
    }

    public function uninstall(UninstallContext $context)
    {
        $service = $this->container->get('shopware_attribute.crud_service');
        $service->delete('s_articles_attributes', 'emz_fsk');

        $metaDataCache = $this->container->get('models')->getConfiguration()->getMetadataCacheImpl();
        $metaDataCache->deleteAll();
        $this->container->get('models')->generateAttributeModels(['s_articles_attributes']);

        parent::uninstall($context);
    }
}
