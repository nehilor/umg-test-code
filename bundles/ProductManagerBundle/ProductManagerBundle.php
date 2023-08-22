<?php

namespace ProductManagerBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class ProductManagerBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/productmanager/js/pimcore/startup.js'
        ];
    }
}