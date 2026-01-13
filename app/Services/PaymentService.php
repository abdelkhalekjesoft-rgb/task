<?php
namespace App\Services;

use SimpleXMLElement;

class PaymentService
{
    public function __construct(
        private string $senderAccount,
        private string $receiverBankCode,
        private string $receiverAccount,
        private string $beneficiaryName,
        private array $notes = [],
        private int $paymentType = 99,
        private string $chargeDetails = 'SHA'
    ) {}

    public function toXml(): string
    {
        $xml = new SimpleXMLElement('<PaymentRequestMessage/>');

        $sender = $xml->addChild('SenderInfo');
        $sender->addChild('AccountNumber', $this->senderAccount);

        $receiver = $xml->addChild('ReceiverInfo');
        $receiver->addChild('BankCode', $this->receiverBankCode);
        $receiver->addChild('AccountNumber', $this->receiverAccount);
        $receiver->addChild('BeneficiaryName', $this->beneficiaryName);

        if (!empty($this->notes)) {
            $notes = $xml->addChild('Notes');
            foreach ($this->notes as $note) {
                $notes->addChild('Note', $note);
            }
        }

        if ($this->paymentType !== 99) {
            $xml->addChild('PaymentType', (string) $this->paymentType);
        }

        if ($this->chargeDetails !== 'SHA') {
            $xml->addChild('ChargeDetails', $this->chargeDetails);
        }

        return $xml->asXML();
    }
}