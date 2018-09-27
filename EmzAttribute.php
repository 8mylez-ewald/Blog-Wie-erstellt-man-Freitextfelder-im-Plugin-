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
        $container->setParameter('emz_attribute.plugin_dir', $this->getPath());
        parent::build($container);
    }

    public function install(InstallContext $context)
    {
        parent::install($context);

        $service = $this->container->get('shopware_attribute.crud_service');
        $service->update('s_articles_attributes', 'emz_fsk', 'integer', [
            'label' => 'Artikel ist ein FSK Artikel',
            'displayInBackend' => true,
            'translateable' => true,
            'position' => 1,
            'custom' => false,
        ]);

        $metaDataCache = $this->container->get('models')->getConfiguration()->getMetadataCacheImpl();
        $metaDataCache->deleteAll();
        $this->container->get('models')->generateAttributeModels(['s_articles_attributes']);

        $context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
    }

    public function uninstall(UninstallContext $context)
    {
        parent::uninstall($context);

        $service = $this->container->get('shopware_attribute.crud_service');
        $service->delete('s_articles_attributes', 'emz_fsk');

        $metaDataCache = $this->container->get('models')->getConfiguration()->getMetadataCacheImpl();
        $metaDataCache->deleteAll();
        $this->container->get('models')->generateAttributeModels(['s_articles_attributes']);
    }
}
