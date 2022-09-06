<?php

namespace DPG\WordPress\EventApi\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Response;

class EventApi
{
    private static $accessTokenData = [];

    /**
     * @var \GuzzleHttp\Cookie\CookieJar
     */
    private static $cookieJar;

    /**
     * @var string
     */
    private static $routeAccessToken = '/oauth/v2/token';

	/**
	 * @var
	 */
	private static $transientExpiration = HOUR_IN_SECONDS;

    /**
     * @var array
     */
    private static $routeEndpoints = [
        'activities'      => '/api/b2b/edition/{editionId}/activity',
        'activity'        => '/api/b2b/edition/{editionId}/activity/{id}',
        'exhibitor'       => '/api/b2b/edition/{editionId}/exhibitors',
        'editionCheckout' => '/api/event/{eventId}/edition/{editionId}/checkout',
        'event'           => '/api/event',
        'eventEdition'    => '/api/event/{eventId}/edition',
        'eventExhibitor'  => '/api/edition/{eventId}/exhibitor',
    ];

    /**
     * @var array
     */
    private static $config = [
        'grantType'    => 'client_credentials',
        'clientId'     => 'test',
        'clientSecret' => 'test',
    ];

    const CDN_SECRET = 'www-zGkp2RGHhShU';

    /**
     * @param string $endpoint
     * @param array  $params
     *
     * @return array
     */
    public static function get(string $endpoint, array $params = [], array $queryParams = []): array
    {
        $queryRoute   = array_merge($queryParams, ['access_token' => self::getAccessToken()]);
        $route        = self::getRoute($endpoint, $params);
        $transientKey = DPG_EVENTAPI_SLUG.md5('get'.$route);
        if (false !== ($output = get_transient($transientKey))) {
            return $output;
        }
        $response = self::request('GET', $route, $queryRoute);
        $output   = self::output($response);
        set_transient($transientKey, $output, self::$transientExpiration );

        return $output;
    }

    /**
     * @param string $endpoint
     * @param array  $params
     * @param array  $data
     *
     * @return array
     */
    public static function post(string $endpoint, array $params, array $data): array
    {
        $route        = self::getRoute($endpoint, $params);
        $transientKey = DPG_EVENTAPI_SLUG.md5('post'.$route);
        if (false !== ($output = get_transient($transientKey))) {
            return $output;
        }
        $response = self::request('POST', $route, ['access_token' => self::getAccessToken()]);
        $output   = self::output($response);
        set_transient($transientKey, $output, self::$transientExpiration);

        return $output;
    }

    /**
     * @param Response $response
     *
     * @return array
     */
    private static function output(Response $response): array
    {
        if (empty((string)$response->getBody())) {
            return [];
        }

        $json = @json_decode((string)$response->getBody(), true);

        if ($json === null) {
            return [];
        }

        return $json;
    }

    /**
     * @param string $requestMethod
     * @param string $endpoint
     * @param array  $queryParams
     * @param array  $headers
     * @param array  $body
     *
     * @return Response
     */
    private static function request(
        string $requestMethod,
        string $endpoint,
        array $queryParams = [],
        array $headers = [],
        array $body = []
    ): Response {
        if (empty(self::$cookieJar)) {
            self::$cookieJar = new \GuzzleHttp\Cookie\CookieJar();
        }

        $client   = new Client([
            'headers'  => ['X-CDN-Secret' => self::CDN_SECRET],
            'base_uri' => rtrim(EVENTAPI_BASEURI, '/'),
            'cookies'  => true,
        ]);
        $endpoint = ! empty($queryParams) ? $endpoint.'?'.http_build_query($queryParams) : $endpoint;

        $requestOptions = [
            'cookies' => self::$cookieJar,
            'headers' => $headers,
        ];

        try {
            $response = $client->request(
                $requestMethod,
                $endpoint,
                $requestOptions
            );

        } catch (BadResponseException $e) {
            return new Response($e->getCode(), [], null, 1.1, (string)$e->getResponse()->getBody());
        }

        return $response;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    private static function getAccessToken(): string
    {
        if (! empty(self::$accessTokenData)) {
            $data = unserialize(self::$accessTokenData);
            if ($data['expires'] > time() && ! empty($data['accessToken'])) {
                return $data['accessToken'];
            }
        }

        $response = self::request('GET', self::$routeAccessToken, [
            'grant_type'    => self::$config['grantType'],
            'client_id'     => self::$config['clientId'],
            'client_secret' => self::$config['clientSecret'],
        ], [
                'Authorization' => 'OAuth',
                'Accept'        => 'application/json',
            ]
        );

        $json = @json_decode((string)$response->getBody(), false);

        if ($json === null) {
            throw new \Exception('Could not retrieve oauth token from event api');
        }

        self::$accessTokenData = serialize([
            'accessToken' => $json->access_token,
            'expires'     => time() + $json->expires_in,
            'type'        => $json->token_type,
        ]);

        return $json->access_token;
    }

    /**
     * @param string $endpoint
     * @param array  $params
     *
     * @return string
     */
    private static function getRoute(string $endpoint, array $params): string
    {
        $endpoint = self::$routeEndpoints[$endpoint];

        foreach ($params as $key => $value) {
            $endpoint = str_replace('{'.$key.'}', $value, $endpoint);
        }

        return $endpoint;
    }
}
