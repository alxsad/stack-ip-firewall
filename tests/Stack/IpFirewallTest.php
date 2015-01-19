<?php
namespace Alxsad\Stack;

class IpFirewallTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $whiteList = [
        '86.57.200.100/25',
        '192.168.1.*',
        '192.168.10.10',
    ];

    /**
     * @dataProvider validIpProvider
     */
    public function testValid($ip)
    {
        $request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $request
            ->expects($this->any())
            ->method('getClientIp')
            ->will($this->returnValue($ip))
        ;

        $response = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Response')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $kernel
            ->expects($this->any())
            ->method('handle')
            ->will($this->returnValue($response))
        ;

        $middleware = new IpFirewall($kernel, $this->whiteList);
        $this->assertEquals($middleware->handle($request), $response);
    }

    /**
     * @dataProvider invalidIpProvider
     */
    public function testInvalid($ip)
    {
        $request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $request
            ->expects($this->any())
            ->method('getClientIp')
            ->will($this->returnValue($ip))
        ;

        $kernel = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $middleware = new IpFirewall($kernel, $this->whiteList);
        $response = $middleware->handle($request);
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function validIpProvider()
    {
        return [
            ['86.57.200.1'],
            ['86.57.200.100'],
            ['86.57.200.126'],
            ['192.168.1.0'],
            ['192.168.1.100'],
            ['192.168.1.255'],
            ['192.168.10.10'],
        ];
    }

    public function invalidIpProvider()
    {
        return [
            ['86.57.199.255'],
            ['86.57.200.128'],
            ['192.168.0.255'],
            ['192.168.2.0'],
            ['192.168.10.9'],
        ];
    }
}
