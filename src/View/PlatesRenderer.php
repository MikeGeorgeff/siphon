<?php

namespace Siphon\View;

use League\Plates\Engine;

class PlatesRenderer implements Renderer
{
    /**
     * The plates engine instance
     *
     * @var \League\Plates\Engine
     */
    protected $plates;

    /**
     * @param \League\Plates\Engine $plates
     */
    public function __construct(Engine $plates)
    {
        $this->plates = $plates;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $params = [])
    {
        $path = str_replace('.', '/', $name);

        return $this->plates->render($path, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function addPath($namespace, $path)
    {
        $this->plates->addFolder($namespace, $path);
    }

    /**
     * {@inheritdoc}
     */
    public function shareData($param, $value, array $templates = [])
    {
        if (empty($templates)) {
            $templates = null;
        }

        $data = [$param => $value];

        $this->plates->addData($data, $templates);
    }
}