<?php


namespace Tests\Hostinger\Hevents;


use Hostinger\Hevents\DefaultHandler;
use PHPUnit\Framework\TestCase;

class DefaultHandlerTest extends TestCase
{
    public function testConstructsHandler()
    {
        $handler = new DefaultHandler('test.com/test/com', 'abcdefgh', 3);
        self::assertTrue($handler instanceof DefaultHandler);
    }
}
