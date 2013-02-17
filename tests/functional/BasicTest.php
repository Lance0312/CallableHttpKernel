<?php

namespace functional;

use Stack\CallableHttpKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    /** @dataProvider provideRequests */
    public function testHelloWorld(Request $request)
    {
        $kernel = new CallableHttpKernel(function (Request $request) {
            return new Response('Hello World!');
        });

        $response = $kernel->handle($request);
        $this->assertEquals(new Response('Hello World!'), $response);
    }

    public function testMultipleCalls()
    {
        $kernel = new CallableHttpKernel(function (Request $request) {
            static $i = 0;
            return new Response($i++);
        });

        $response = $kernel->handle(Request::create('/'));
        $this->assertEquals(new Response('0'), $response);

        $response = $kernel->handle(Request::create('/'));
        $this->assertEquals(new Response('1'), $response);

        $response = $kernel->handle(Request::create('/'));
        $this->assertEquals(new Response('2'), $response);
    }

    public function provideRequests()
    {
        return [
            [Request::create('/')],
            [Request::create('/foo')],
            [Request::create('/foo/bar/baz?qux=quux')],
            [Request::create('/', 'POST')],
            [Request::create('/', 'PUT')],
            [Request::create('/', 'DELETE')],
            [Request::create('/foo?wat=wob', 'POST')],
        ];
    }
}