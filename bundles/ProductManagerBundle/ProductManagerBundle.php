<?php

namespace ProductManagerBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class ProductManagerBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            'bundles/ProductManagerBundle/public/js/pimcore/startup.js'
        ];
    }
}
