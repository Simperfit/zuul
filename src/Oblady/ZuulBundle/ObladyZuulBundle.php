<?php

namespace Oblady\ZuulBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ObladyZuulBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'SonataUserBundle';
    }
}

