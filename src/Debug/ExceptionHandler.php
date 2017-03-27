<?php

namespace Siphon\Debug;

use Exception;
use Whoops\Run;
use Psr\Log\LoggerInterface;
use Siphon\Event\Dispatcher;
use Siphon\Http\Response\Factory;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;
use Psr\Http\Message\ServerRequestInterface;

class ExceptionHandler
{
    /**
     * @var \Siphon\Event\Dispatcher
     */
    protected $event;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * @var \Siphon\Http\Response\Factory
     */
    protected $response;

    /**
     * Determine if debugging is active
     *
     * @var bool
     */
    protected $debug;

    /**
     * Request formats with MIME types
     *
     * @var array
     */
    protected $formats = [
        'html' => ['text/html', 'application/xhtml+xml'],
        'json' => ['application/json', 'text/json', 'application/x-json'],
        'txt'  => ['text/plain']
    ];

    /**
     * @param \Siphon\Event\Dispatcher      $event
     * @param \Psr\Log\LoggerInterface      $log
     * @param \Siphon\Http\Response\Factory $response
     * @param bool                          $debug
     */
    public function __construct(Dispatcher $event, LoggerInterface $log, Factory $response, $debug)
    {
        $this->event    = $event;
        $this->log      = $log;
        $this->response = $response;
        $this->debug    = $debug;
    }

    /**
     * Handle an exception and generate a response
     *
     * @param \Exception                               $e
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(Exception $e, ServerRequestInterface $request)
    {
        $this->event->dispatch(new Event\ExceptionWasCaught($e, $request));

        $this->log->error($e);

        return $this->createResponse($e, $this->getRequestFormat($request));
    }

    /**
     * Handle an exception for cli environment
     *
     * @param \Exception $e
     * @return string
     */
    public function handleForConsole(Exception $e)
    {
        $whoops = $this->setPlainTextHandler();

        $this->log->error($e->getMessage());

        return $whoops->handleException($e);
    }

    /**
     * Create the response
     *
     * @param \Exception $e
     * @param string     $format
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function createResponse(Exception $e, $format)
    {
        switch ($format) {
            case 'json':
                $whoops = $this->setJsonResponseHandler();
                return $this->response->json($whoops->handleException($e));
            case 'html':
                if ($this->debug) {
                    $whoops = $this->setPrettyPageHandler();
                    return $this->response->html($whoops->handleException($e));
                } else {
                    $whoops = $this->setPlainTextHandler();
                    return $this->response->text($whoops->handleException($e));
                }
            case 'txt':
                $whoops = $this->setPlainTextHandler();
                return $this->response->text($whoops->handleException($e));
        }
    }

    /**
     * Get the request format
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return string
     */
    protected function getRequestFormat(ServerRequestInterface $request)
    {
        $acceptType = $request->getHeader('accept');

        if (count($acceptType) > 0) {
            foreach ($this->formats as $format => $values) {
                if (in_array($acceptType[0], $values)) {
                    return $format;
                }
            }
        }

        return 'html';
    }

    /**
     * Set the whoops handler to json response handler
     *
     * @return \Whoops\Run
     */
    public function setJsonResponseHandler()
    {
        $whoops = $this->getWhoops();

        $handler = new JsonResponseHandler;
        $handler->addTraceToOutput($this->debug);

        $whoops->pushHandler($handler);

        return $whoops;
    }

    /**
     * Set the whoops handler to the pretty page handler
     *
     * @return \Whoops\Run
     */
    public function setPrettyPageHandler()
    {
        $whoops = $this->getWhoops();

        $whoops->pushHandler(new PrettyPageHandler);

        return $whoops;
    }

    /**
     * Set the whoops handler to the plain text handler
     *
     * @return \Whoops\Run
     */
    public function setPlainTextHandler()
    {
        $whoops = $this->getWhoops();

        $handler = new PlainTextHandler;
        $handler->addTraceToOutput($this->debug);

        $whoops->pushHandler($handler);

        return $whoops;
    }

    /**
     * Get the whoops instance
     *
     * @return \Whoops\Run
     */
    public function getWhoops()
    {
        $whoops = new Run;

        $whoops->allowQuit(false);

        return $whoops;
    }
}