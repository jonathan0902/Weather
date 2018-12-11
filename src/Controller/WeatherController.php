<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;


    public function apiKey($api = "")
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$api",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }

    public function weatherActionGet()
    {
        $page = $this->di->get("page");
        $iptest = $this->di->request->getGet('location', "");
        $validate = $this->weatherValidateGet($iptest);

        if ($validate != false) {
            $response = $this->apiKey("https://api.darksky.net/forecast/" . $this->di->get('keys')->weather . "/" . $validate["latitude"] . "," . $validate["longitude"] . "?exclude=currently,%20minutely,%20hourly,alerts&lang=sv&units=si");
        } else {
            $response = false;
        }

        $page->add("ip/weather", [
            "response" => $response
        ]);

        return $page->render();
    }

    public function weatherValidateGet($iptest)
    {
        $validated = false;

        if (filter_var($iptest, FILTER_VALIDATE_IP)) {
            $validated = $this->APIKey("http://api.ipstack.com/$iptest?access_key=" . $this->di->get("keys")->ip);
        }
        return $validated;
    }

    public function weatherRestApiActionGet($iptest = "161.185.160.93")
    {
        $validate = $this->weatherValidateGet($iptest);

        if ($validate != false) {
            $response = $this->apiKey("https://api.darksky.net/forecast/" . $this->di->get('keys')->weather . "/" . $validate["latitude"] . "," . $validate["longitude"]);
        } else {
            $response = ["Ip Could not been found!"];
        }

        return json_encode($response, JSON_PRETTY_PRINT);
    }
}
