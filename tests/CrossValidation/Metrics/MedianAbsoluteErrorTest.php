<?php

namespace Rubix\ML\Tests\CrossValidation\Metrics;

use Rubix\ML\CrossValidation\Metrics\Metric;
use Rubix\ML\CrossValidation\Metrics\MedianAbsoluteError;
use PHPUnit\Framework\TestCase;
use Generator;

class MedianAbsoluteErrorTest extends TestCase
{
    protected const LABELS = [10, 10.0, 6, -1400, .08];

    protected $metric;

    public function setUp()
    {
        $this->metric = new MedianAbsoluteError();
    }

    public function test_build_metric()
    {
        $this->assertInstanceOf(MedianAbsoluteError::class, $this->metric);
        $this->assertInstanceOf(Metric::class, $this->metric);

        $this->assertNotEmpty(array_filter($this->metric->range(), 'is_numeric'));
        $this->assertNotEmpty(array_filter($this->metric->compatibility(), 'is_int'));
    }

    /**
     * @dataProvider score_provider
     */
    public function test_score(array $predictions, float $expected)
    {
        [$min, $max] = $this->metric->range();

        $score = $this->metric->score($predictions, self::LABELS);

        $this->assertThat(
            $score,
            $this->logicalAnd(
                $this->greaterThanOrEqual($min),
                $this->lessThanOrEqual($max)
            )
        );

        $this->assertEquals($expected, $score);
    }

    public function score_provider() : Generator
    {
        yield [[7, 9.5, -20, -500, .079], -3.0];
        yield [[0, 0, 0, 0, 0], -10.0];
        yield [[10, 10.0, 6, -1400, .08], 0.0];
    }
}
