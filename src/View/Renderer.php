<?php

namespace Siphon\View;

interface Renderer
{
    /**
     * Render a new template
     *
     * @param string  $name
     * @param array   $params
     * @return string
     */
    public function render($name, array $params = []);

    /**
     * Add a name spaced template folder path
     *
     * @param string $namespace
     * @param string $path
     * @return void
     */
    public function addPath($namespace, $path);

    /**
     * Add data to be shared with the given templates
     *
     * @param string $param
     * @param mixed  $value
     * @param array  $templates
     * @return void
     */
    public function shareData($param, $value, array $templates = []);
}