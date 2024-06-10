<?php

namespace RoadTraffic\Tests\Application\Service\RoadTraffic;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Application\Service\RoadTraffic\Calculate;

class CalculateTest extends TestCase
{
    public function testCalculate(): void
    {
        $this->assertEquals(1, (new Calculate())->maximalVar(1, 0));
        $this->assertEquals(2, (new Calculate())->maximalVar(1, 2));
        $this->assertGreaterThan((new Calculate())->maximalVar(1, 2), 3, 'viva lalgerie');
        $this->assertEquals(2, (new Calculate())->maximalVar(2, 2));
    }
}
