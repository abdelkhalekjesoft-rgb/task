<?php

namespace Tests\Unit;

use App\Services\Contracts\FoodicsBankParser;
use PHPUnit\Framework\TestCase;

class FoodicsBankPerformanceTest extends TestCase
{
    public function test_can_parse_large_foodics_payload_efficiently(): void
    {
        $transaction = "20250615156,50#202506159000001#note/debt payment
        march/internal_reference/A462JE81";

        $payload = implode("\n", array_fill(0, 1000, $transaction));

        $parser = new FoodicsBankParser();

        $start = microtime(true);
        $transactions = $parser->parse($payload);
        $duration = microtime(true) - $start;

        $this->assertCount(1000, $transactions);
        $this->assertLessThan(1.0, $duration);
    }
}