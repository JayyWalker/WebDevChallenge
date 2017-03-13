<?php

namespace Tests;

use App\Environment;
use PHPUnit\Framework\TestCase;

class TestEnvironment extends TestCase
{
    protected $environment;

    protected function setUp()
    {
        $this->environment = new Environment();
    }

    /**
     * @covers App\Environment::getAllowedEnvironments
     */
    public function testGetAllowedEnvironments()
    {
        $this->assertEquals($this->environment->getAllowedEnvironments(),
            [
                'development',
                'staging',
                'production'
            ]
        );
    }

    /**
     * @covers \App\Environment::validateEnvironment
     */
    public function testValidateEnvironmentFails()
    {
        $input = 'dev';

        $this->expectException(\App\Exception\ValidEnvironmentException::class);
        $this->expectExceptionMessage("There is no environment option for $input");

        $this->assertArrayHasKey($input,
            $this->environment->validateEnvironment($input)
        );
    }

    /**
     * @covers \App\Environment::validateEnvironment()
     */
    public function testValidateEnvironmentWorks()
    {
        $input = 'development';
        $this->assertTrue($this->environment->validateEnvironment($input));
    }

    /**
     * @covers \App\Environment::setCurrentEnvironment
     */
    public function testCurrentEnvironmentSetter()
    {
        $environment = 'development';
        $this->assertEquals(
            $this->environment->setCurrentEnvironment($environment),
            $this->environment
        );

        return $this->environment;
    }

    /**
     * @covers \App\Environment::getCurrentEnvironment
     */
    public function testCurrentEnvironmentGetter()
    {
        $environment = 'development';
        $this->environment->setCurrentEnvironment($environment);
        $this->assertEquals(
            $this->environment->getCurrentEnvironment(),
            $environment
        );
    }
}
