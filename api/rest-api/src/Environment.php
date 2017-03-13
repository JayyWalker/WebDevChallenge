<?php

namespace App;

use App\Exception\ValidEnvironmentException;

class Environment
{
    const DEV     = 'development';

    const STAGING = 'staging';

    const LIVE    = 'production';

    protected $allowedEnvironments = [
        'development',
        'staging',
        'production',
    ];

    protected $currentEnvironment;

    public function getAllowedEnvironments()
    {
        return $this->allowedEnvironments;
    }

    public function validateEnvironment($input)
    {
        $validate = in_array($input, $this->allowedEnvironments);
        if ($validate === false) {
            throw new ValidEnvironmentException("There is no environment option for $input");
        }

        return $validate;
    }

    public function setCurrentEnvironment($input)
    {
        $this->currentEnvironment = $input;

        return $this;
    }

    public function getCurrentEnvironment()
    {
        return $this->currentEnvironment;
    }
}
