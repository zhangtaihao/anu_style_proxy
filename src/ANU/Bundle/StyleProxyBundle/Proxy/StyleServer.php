<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Guzzle\Http\Client;
use ANU\Bundle\StyleProxyBundle\Exception\ProfileNotFoundException;
use ANU\Bundle\StyleProxyBundle\Exception\InvalidProfileRequestException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Message\RequestInterface as GuzzleRequestInterface;
use Guzzle\Http\Message\Response as GuzzleResponse;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\Profile;
use ANU\Bundle\StyleProxyBundle\Proxy\Profile\ProfileHandler;

/**
 * Delegate style server.
 */
class StyleServer extends SimpleProxy
{
    /**
     * Profile handler.
     * @var ProfileHandler
     */
    protected $profileHandler;

    private $includePath;

    public function __construct($baseUrl, $includePath, ProfileHandler $profileHandler)
    {
        parent::__construct($baseUrl);
        $this->includePath = $includePath;
        $this->profileHandler = $profileHandler;
    }

    /**
     * Retrieves a style part for a given request.
     */
    public function getStyleInclude(Request $request)
    {
        $profile = $this->getProfileForRequest($request);
        if ($part = $request->get('part')) {
            if ($part == 'json') {
                return $profile->getSerializedData();
            }
            else {
                $include = $profile->get($part);
                return is_string($include) ? $include : null;
            }
        }
        return null;
    }

    /**
     * Returns a profile for a request.
     *
     * @param Request $request
     *   Profile request. Only the GET query is relevant.
     * @return Profile
     *   Profile object.
     *
     * @throws InvalidProfileRequestException
     */
    public function getProfileForRequest(Request $request)
    {
        $query = $request->query->all();
        try {
            $profile = $this->profileHandler->getProfile($query);
            return $profile;
        }
        catch (ProfileNotFoundException $e) {
            try {
                $data = $this->retrieveProfileData($request);
                return $this->profileHandler->createProfile($query, $data);
            }
            catch (\InvalidArgumentException $e)
            {
                throw new InvalidProfileRequestException();
            }
        }
    }

    /**
     * Retrieves a profile for a request.
     */
    protected function retrieveProfileData(Request $request)
    {
        $profileRequest = $this->createJSONRequest($request);
        $response = $this->delegate($profileRequest);
        return $response->getContent();
    }

    /**
     * Creates a JSON delegate request.
     */
    protected function createJSONRequest(Request $request)
    {
        $jsonRequest = clone $request;
        $jsonRequest->query->set('part', 'json');
        return $jsonRequest;
    }

    /**
     * {@inheritdoc}
     */
    protected function createBackendRequest(Request $request)
    {
        $query = $request->query->all();
        $queryUri = implode(',', array_keys($query));
        $uri = array($this->includePath.'{?'.$queryUri.'}', $query);
        $backendRequest = $this->getBackendClient()->createRequest(GuzzleRequestInterface::GET, $uri);
        return $backendRequest;
    }
}
