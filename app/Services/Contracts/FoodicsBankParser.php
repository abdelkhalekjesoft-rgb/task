<?php

namespace App\Services\Contracts;

use App\Services\BankParser;
use Carbon\Carbon;

class FoodicsBankParser implements BankParser{
    public function parse(string $content): array{
        //The format is: Date, Amount (two decimals), "#", Reference, "#", Key-value pairs where the Key is before the / and the value is after it

        /*
        //20250615156,50#202506159000001#note/debt payment
        march/internal_reference/A462JE81
        */

        $lines = explode("\n", trim($content));
        $tranactions=[];
        $buffer='';

        foreach($lines as $line){
            //check start line first
            if($this->isTransactionStart($line)){
                if($buffer !== ''){
                    $tranactions[]=$this->parseTransaction($buffer);
                }
                //true
                $buffer=$line;
            }else{
                //false
                $buffer.=' '.trim($line);
            }
        }

        //last transaction check
        if($buffer !== ''){
            $tranactions[]=$this->parseTransaction($buffer);
        }

        return $tranactions;
    }

    public function isTransactionStart($line){
        return preg_match('/^\d{8}/', $line) === 1;
    }

    public function parseTransaction($line){
        $transactionArray=explode('#',trim($line));

        $date=$this->parserDate($transactionArray[0]);
        $amount=$this->parseAmount($transactionArray[0]);
        $reference=$transactionArray[1];
        $notes=$this->parseNotes($transactionArray[2]);

        return [
            "date"=>$date
            ,"amount"=>$amount
            ,"reference"=>$reference
            ,"notes"=>$notes
        ];
    }

    private function parserDate($dateAmount){
        $date=substr($dateAmount,0,8);

        return Carbon::createFromFormat('Ymd',$date)->toDateString();
    }

    private function parseAmount($dateAmount){
      return (float)str_replace(',','.',substr($dateAmount,8));
    }

    private function parseNotes($notes){
        $segments = explode('/', trim($notes, '/'));

        for ($i = 0; $i < count($segments); $i += 2) {
            if (isset($segments[$i + 1])) {
                $responsse[$segments[$i]] = trim($segments[$i + 1]);
            }
        }

        return $responsse;
    }

}