<?php

namespace Siphon\Http\Request;

use Dflydev\FigCookies\FigRequestCookies;
use Psr\Http\Message\ServerRequestInterface as Request;

trait RequestTrait
{
    /**
     * Get a cookie from the request
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param string                                   $name
     * @param string|null                              $value
     * @return \Dflydev\FigCookies\Cookie
     */
    public function getCookie(Request $request, $name, $value = null)
    {
        return FigRequestCookies::get($request, $name, $value);
    }

    /**
     * Get an item from the query parameters array
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param string                                   $parameter
     * @param mixed                                    $default
     * @return mixed
     */
    public function getQueryParam(Request $request, $parameter, $default = null)
    {
        return $this->getParam($request->getQueryParams(), $parameter, $default);
    }

    /**
     * Get an item from the server parameters array
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param string                                   $parameter
     * @param mixed                                    $default
     * @return mixed
     */
    public function getServerParam(Request $request, $parameter, $default = null)
    {
        return $this->getParam($request->getQueryParams(), $parameter, $default);
    }

    /**
     * Get an uploaded file instance from the uploaded files array
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param string                                   $parameter
     * @return \Psr\Http\Message\UploadedFileInterface
     */
    public function getUploadedFile(Request $request, $parameter)
    {
        return $this->getParam($request->getUploadedFiles(), $parameter);
    }

    /**
     * Get a parameter from an array
     *
     * @param array      $array
     * @param string     $parameter
     * @param mixed|null $default
     * @return mixed
     */
    public function getParam(array $array, $parameter, $default = null)
    {
        return array_key_exists($parameter, $array) ? $array[$parameter] : $default;
    }
}