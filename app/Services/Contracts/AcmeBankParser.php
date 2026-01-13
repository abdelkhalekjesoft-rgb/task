<?php

namespace App\Services\Contracts;

use App\Services\BankParser;
use Carbon\Carbon;

class AcmeBankParser implements BankParser{
    public function parse(string $content): array{
        //Sample Webhook From Acme Bank, each line is a transaction.
        //The format is: Amount (two decimals), "//", Reference, "//", Date

        //156,50//202506159000001//20250615
        $transactions=[];

        $lines = explode("\n", trim($content));

        foreach($lines as $line){
            $transactionArray=explode("//",$line);
            $amount=$this->parseAmount(trim($transactionArray[0],'"'));
            $reference=$transactionArray[1];
            $date=$this->parserDate(substr(trim($transactionArray[2],'"'),0,8));

            $transactions[]= [
                "date"=>$date
                ,"amount"=>$amount
                ,"reference"=>$reference
            ];
        }
        
        return $transactions;
    }

    private function parseAmount($amount){
        return str_replace(',','.',$amount);
    }

    private function parserDate($date){
        return Carbon::createFromFormat('Ymd',$date)->toDateString();
    }
}