<?php

namespace Rubix\ML\Tests\NeuralNet\CostFunctions;

use Rubix\Tensor\Matrix;
use Rubix\ML\NeuralNet\CostFunctions\CrossEntropy;
use Rubix\ML\NeuralNet\CostFunctions\CostFunction;
use PHPUnit\Framework\TestCase;

class CrossEntropyTest extends TestCase
{
    protected $costFn;

    protected $expected;

    protected $activation;

    protected $delta;

    public function setUp()
    {
        $this->expected = Matrix::quick([[1.], [0.], [0.], [1.], [0.]]);

        $this->activation = Matrix::quick([[0.99], [0.2], [0.7], [0.80], [0.02]]);

        $this->costFn = new CrossEntropy();
    }

    public function test_build_cost_function()
    {
        $this->assertInstanceOf(CrossEntropy::class, $this->costFn);
        $this->assertInstanceOf(CostFunction::class, $this->costFn);
    }

    public function test_compute()
    {
        $cost = $this->costFn->compute($this->expected, $this->activation);

        $this->assertEquals(0.046638777433542236, $cost);
    }

    public function test_differentiate()
    {
        $derivative = $this->costFn->differentiate($this->expected, $this->activation)
            ->asArray();

        $expected = [
            [-1.01010101010101],
            [1.2499999999999998],
            [3.3333333333333326],
            [-1.25],
            [1.0204081632653061],
        ];

        $this->assertEquals($expected, $derivative);
    }
}
