<?php

namespace Tests\Unit;

use App\Services\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    public function test_notes_tag_is_not_rendered_when_empty(): void
    {
        $xml = (new PaymentService(
            senderAccount: 'SA123',
            receiverBankCode: 'FDCSSARI',
            receiverAccount: 'SA456',
            beneficiaryName: 'John Doe'
        ))->toXml();

        $this->assertStringNotContainsString('<Notes>', $xml);
    }

    public function test_notes_tag_is_rendered_when_notes_exist(): void
    {
        $xml = (new PaymentService(
            'SA123',
            'FDCSSARI',
            'SA456',
            'John Doe',
            ['Note 1', 'Note 2']
        ))->toXml();

        $this->assertStringContainsString('<Notes>', $xml);
        $this->assertStringContainsString('<Note>Note 1</Note>', $xml);
    }

    public function test_payment_type_is_not_rendered_when_value_is_99(): void
    {
        $xml = (new PaymentService(
            'SA123',
            'FDCSSARI',
            'SA456',
            'John Doe',
            [],
            99
        ))->toXml();

        $this->assertStringNotContainsString('<PaymentType>', $xml);
    }

    public function test_payment_type_is_rendered_when_value_is_not_99(): void
    {
        $xml = (new PaymentService(
            'SA123',
            'FDCSSARI',
            'SA456',
            'John Doe',
            [],
            421
        ))->toXml();

        $this->assertStringContainsString('<PaymentType>421</PaymentType>', $xml);
    }

    public function test_charge_details_is_not_rendered_when_value_is_sha(): void
    {
        $xml = (new PaymentService(
            'SA123',
            'FDCSSARI',
            'SA456',
            'John Doe',
            [],
            99,
            'SHA'
        ))->toXml();

        $this->assertStringNotContainsString('<ChargeDetails>', $xml);
    }

    public function test_charge_details_is_rendered_when_value_is_not_sha(): void
    {
        $xml = (new PaymentService(
            'SA123',
            'FDCSSARI',
            'SA456',
            'John Doe',
            [],
            99,
            'RB'
        ))->toXml();

        $this->assertStringContainsString('<ChargeDetails>RB</ChargeDetails>', $xml);
    }
}