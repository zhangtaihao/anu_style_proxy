<?php

namespace ANU\Bundle\StyleProxyBundle\Proxy;

use Orbt\ResourceMirror\ResourceMirror;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use Symfony\Component\HttpFoundation\Response;
use Orbt\ResourceMirror\Resource\GenericResource;
use Symfony\Component\HttpFoundation\Request;

/**
 * Proxy handler for the resource mirror to materialize assets.
 */
class AssetServer
{
    /**
     * @var ResourceMirror
     */
    protected $mirror;

    /**
     * Creates an asset server.
     */
    public function __construct(ResourceMirror $mirror)
    {
        $this->mirror = $mirror;
    }

    /**
     * Retrieves a resource.
     *
     * @param Request $request
     * @return Response
     */
    public function getResource(Request $request)
    {
        $resourceRequest = new GenericResource($request->getPathInfo());
        $resource = $this->mirror->materialize($resourceRequest);
        $mimeType = MimeTypeGuesser::getInstance()->guess($resource->getRealPath());
        return Response::create($resource->getContent(), 200, array(
            'Content-Type' => $mimeType,
        ));
    }
}
