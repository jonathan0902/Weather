<?php
/**
 * Created by PhpStorm.
 * User: jonat
 * Date: 2018-11-28
 * Time: 08:16
 */

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

class MapControllerTest extends TestCase
{

    // Create the di container.
    protected $di;
    protected $controller;



    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // View helpers uses the global $di so it needs its value
        $di = $this->di;
    }

    /**
     * Test the route "index".
     */
    public function testWeatherAction()
    {
        $controller = new IPController();
        $controller->setDI($this->di);
        $this->di->get("request")->setGet('location', "212.212.100.110");
        $res = $controller->weatherActionGet();
        $this->assertNotNull($res);
    }

    public function testWeatherFailedAction()
    {
        $controller = new IPController();
        $controller->setDI($this->di);
        $this->di->get("request")->setGet('location', "212.212.100.1103124124124");
        $res = $controller->weatherActionGet();
        $this->assertNotNull($res);
    }

    public function testWeatherRestApiAction()
    {
        $controller = new IPController();
        $controller->setDI($this->di);
        $res = $controller->weatherRestApiActionGet("161.185.160.93");
        $this->assertNotNull($res);
    }

    public function testWeatherRestApiFailedAction()
    {
        $controller = new IPController();
        $controller->setDI($this->di);
        $res = $controller->weatherRestApiActionGet("161.185.160.93321123123");
        $this->assertNotNull($res);
    }
}
