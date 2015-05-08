<?php
namespace Jrnv\Akismet;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;

class AkismetClient
{
    /**
     * Akismet API key.
     *
     * @var string
     */
    protected $key;

    /**
     * The Guzzle HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Class constructor.
     *
     * @param string $key The Akismet API key.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($key)
    {
        // Simple key validation
        if (!preg_match('/^[a-z0-9]{12}$/i', $key)) {
            throw new \InvalidArgumentException('Invalid key length.');
        }

        $this->key = $key;
        $this->client = new Client();
    }

    /**
     * Sends a request to an API endpoint
     *
     * @param string $endpoint The slug of the resource.
     * @param array  $data     Array with POST data.
     *
     * @return \GuzzleHttp\Message\ResponseInterface
     */
    public function request($endpoint, array $data)
    {
        $request = $this->client->createRequest('POST', $this->getUrl($endpoint), [
            'body' => $data
        ]);
        $response = $this->client->send($request);

        return $response;
    }

    /**
     * Gets the base URL for the API call.
     *
     * @param string $endpoint The name of the resource
     *
     * @return string
     */
    protected function getUrl($endpoint)
    {
        $protocol = 'https';
        $url = $protocol . '://' . $this->key . '.rest.akismet.com';

        if ($endpoint === 'verify-key') {
            $url = $protocol . '://rest.akismet.com';
        }

        $url .= '/1.1/' . $endpoint;

        return $url;
    }
}
