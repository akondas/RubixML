<?php

namespace Rubix\ML\Tests\Backends;

use Rubix\ML\Backends\Serial;
use Rubix\ML\Backends\Backend;
use Rubix\ML\Deferred;
use PHPUnit\Framework\TestCase;

class SerialTest extends TestCase
{
    protected $backend;

    public function setUp()
    {
        $this->backend = new Serial();
    }

    public function test_build_backend()
    {
        $this->assertInstanceOf(Serial::class, $this->backend);
        $this->assertInstanceOf(Backend::class, $this->backend);
    }

    public function test_enqueue_process()
    {
        $functions = array_fill(0, 10, [self::class, 'foo']);

        foreach ($functions as $i => $function) {
            $this->backend->enqueue(new Deferred($function, [$i]));
        }

        $results = $this->backend->process();

        $this->assertCount(10, $results);
    }

    public static function foo(int $i)
    {
        return [$i, microtime(true)];
    }
}
