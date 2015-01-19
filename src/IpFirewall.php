<?php
namespace Alxsad\Stack;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Piwik\Network\IP;
use Functional as F;

class IpFirewall implements HttpKernelInterface
{
    /**
     * @var HttpKernelInterface
     */
    private $app;

    /**
     * @var array
     */
    private $whiteList = [];

    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @param HttpKernelInterface $app
     * @param array               $whiteList
     * @param bool                $enabled
     */
    public function __construct(HttpKernelInterface $app, array $whiteList = [], $enabled = true)
    {
        $this->app       = $app;
        $this->whiteList = $whiteList;
        $this->enabled   = (bool) $enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        if ($this->enabled) {
            $ip = IP::fromStringIP($request->getClientIp());
            $valid = F\some($this->whiteList, function ($range) use ($ip) {
                return $ip->isInRange($range);
            });
            if (!$valid) {
                return new Response('Forbidden', 403);
            }
        }

        return $this->app->handle($request, $type, $catch);
    }
}
