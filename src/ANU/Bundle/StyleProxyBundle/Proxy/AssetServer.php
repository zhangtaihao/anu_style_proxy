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
        $resourceRequest = new GenericResource(substr($request->getPathInfo(), 1));

        if ($this->mirror->exists($resourceRequest)) {
            $realPath = realpath($this->mirror->getDirectory().'/'.$resourceRequest->getPath());
            $content = file_get_contents($realPath);
            $mimeType = MimeTypeGuesser::getInstance()->guess($realPath);
        }
        else {
            $resource = $this->mirror->materialize($resourceRequest);
            $content = $resource->getContent();
            $mimeType = MimeTypeGuesser::getInstance()->guess($resource->getRealPath());
        }

        return Response::create($content, 200, array(
            'Content-Type' => $mimeType,
        ));
    }
}
