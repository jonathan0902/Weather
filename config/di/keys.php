<?php
/**
 * Created by PhpStorm.
 * User: jonat
 * Date: 2018-11-27
 * Time: 15:00
 */

/**<
 * Configuration file for DI container.
 */
return [

    // Services to add to the container.
    "services" => [
        "keys" => [
            "shared" => true,
            "callback" => function () {
                // Load the configuration files
                $cfg = $this->get("configuration");
                $config = $cfg->load("keys.php");

                return (object) $config["config"];
            }
        ],
    ],
];
