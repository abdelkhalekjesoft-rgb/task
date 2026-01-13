<?php

namespace Tests\Unit;

use App\Services\Contracts\FoodicsBankParser;
use PHPUnit\Framework\TestCase;

class FoodicsBankParserTest extends TestCase
{
    public function test_can_parse_multiline_foodics_transaction(): void
    {
        $payload = "20250615156,50#202506159000009#note/debt payment
        march/internal_reference/A462JE81";

        $parser = new FoodicsBankParser();
        $transactions = $parser->parse($payload);

        $this->assertCount(1, $transactions);

        $transaction = $transactions[0];

        $this->assertEquals('2025-06-15', $transaction['date']);
        $this->assertEquals(156.50, $transaction['amount']);
        $this->assertEquals('202506159000009', $transaction['reference']);

        $this->assertEquals([
            'note' => 'debt payment march',
            'internal_reference' => 'A462JE81',
        ], $transaction['notes']);
    }
}