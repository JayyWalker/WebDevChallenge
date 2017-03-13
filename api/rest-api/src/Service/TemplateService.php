<?php

namespace App\Service;

use Tale\Jade\Renderer;

class TemplateService
{
    public function create(array $config)
    {
        $templateConfig = $config['template'];

        $options = [
            'pretty' => $templateConfig['prettyprint'],
            'paths'  => [$templateConfig['viewsDirectory']],
        ];

        $options['cache_path'] = $templateConfig['cacheDirectory'];

        switch($config['environment']) {
            case('development'):
                $options['ttl'] = 0;
            break;
            case('staging'):
                $options['ttl'] = 0;
            break;
            case('production'):
                $options['ttl'] = $templateConfig['cacheLoad'];
            break;
        }

        return $pug = new Renderer($options);
    }
}
