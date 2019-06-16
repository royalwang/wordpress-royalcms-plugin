<?php

namespace Royalcms\Component\Swoole\Swoole;

use Royalcms\Component\Http\Request as RoyalcmsRequest;
use Symfony\Component\HttpFoundation\ParameterBag;
use Swoole\Http\Request as SwooleRequest;

class Request
{
    protected $swooleRequest;

    public function __construct(SwooleRequest $request)
    {
        $this->swooleRequest = $request;
    }

    /**
     * Convert SwooleRequest to RoyalcmsRequest
     * @param array $rawServer
     * @param array $rawEnv
     * @return RoyalcmsRequest
     */
    public function toRoyalcmsRequest(array $rawServer = [], array $rawEnv = [])
    {
        global $__GET, $__POST, $__COOKIE, $__FILES, $_REQUEST, $_SESSION, $_ENV, $__SERVER;

        $__GET = isset($this->swooleRequest->get) ? $this->swooleRequest->get : [];
        $__POST = isset($this->swooleRequest->post) ? $this->swooleRequest->post : [];
        $__COOKIE = isset($this->swooleRequest->cookie) ? $this->swooleRequest->cookie : [];
        $server = isset($this->swooleRequest->server) ? $this->swooleRequest->server : [];
        $headers = isset($this->swooleRequest->header) ? $this->swooleRequest->header : [];
        $__FILES = isset($this->swooleRequest->files) ? $this->swooleRequest->files : [];
        $_REQUEST = [];
        $_SESSION = [];

        static $headerServerMapping = [
            'x-real-ip'       => 'REMOTE_ADDR',
            'x-real-port'     => 'REMOTE_PORT',
            'server-protocol' => 'SERVER_PROTOCOL',
            'server-name'     => 'SERVER_NAME',
            'server-addr'     => 'SERVER_ADDR',
            'server-port'     => 'SERVER_PORT',
            'scheme'          => 'REQUEST_SCHEME',
        ];

        $_ENV = $rawEnv;
        $__SERVER = $rawServer;
        foreach ($headers as $key => $value) {
            // Fix client && server's info
            if (isset($headerServerMapping[$key])) {
                $server[$headerServerMapping[$key]] = $value;
            } else {
                $key = str_replace('-', '_', $key);
                $server['http_' . $key] = $value;
            }
        }
        $server = array_change_key_case($server, CASE_UPPER);
        $__SERVER = array_merge($__SERVER, $server);
        if (isset($__SERVER['REQUEST_SCHEME']) && $__SERVER['REQUEST_SCHEME'] === 'https') {
            $__SERVER['HTTPS'] = 'on';
        }

        // Fix REQUEST_URI with QUERY_STRING
        if (strpos($__SERVER['REQUEST_URI'], '?') === false
            && isset($__SERVER['QUERY_STRING'])
            && strlen($__SERVER['QUERY_STRING']) > 0
        ) {
            $__SERVER['REQUEST_URI'] .= '?' . $__SERVER['QUERY_STRING'];
        }

        // Fix argv & argc
        if (!isset($__SERVER['argv'])) {
            $__SERVER['argv'] = isset($GLOBALS['argv']) ? $GLOBALS['argv'] : [];
            $__SERVER['argc'] = isset($GLOBALS['argc']) ? $GLOBALS['argc'] : 0;
        }

        // Initialize royalcms request
        RoyalcmsRequest::enableHttpMethodParameterOverride();
        $request = RoyalcmsRequest::createFromBase(new \Symfony\Component\HttpFoundation\Request($__GET, $__POST, [], $__COOKIE, $__FILES, $__SERVER, $this->swooleRequest->rawContent()));

        if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
            && in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), ['PUT', 'DELETE', 'PATCH'])
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new ParameterBag($data);
        }

        return $request;
    }

}
