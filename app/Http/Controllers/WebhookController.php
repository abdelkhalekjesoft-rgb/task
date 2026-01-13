<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\WebHook;
use App\Services\Contracts\FoodicsBankParser;
use App\Services\Contracts\AcmeBankParser;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function receive(Request $request,string $bank){
        $content=$request->getContent();
        //insert in db (id-bank-content)
        WebHook::create([
            "bank"=>$bank
            ,"webhook"=>$content
        ]);


        $parser=match($bank){
            'foodics'=>new FoodicsBankParser()
            ,'acme'=>new AcmeBankParser()
            ,default => abort(400,'unsupported bank')
        };


        $transactions=$parser->parse($content);
        //insert in db

        foreach($transactions as $transaction){
            $transaction['notes']=json_encode([$transaction['notes']]);
            Transaction::firstOrCreate(
                ['reference' => $transaction['reference']],
                $transaction
            );
        }
       

        return response()->json($transactions);

    }
}
