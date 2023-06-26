<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    function addTransaction(Request $request)   {
        
        $Transaction = new Transaction();
        $Transaction->sender_id = \Request::input('sender_id');
        $Transaction->receiver_id  = \Request::input('receiver_id');
        $Transaction->amount  = \Request::input('amount');
        $Transaction->save();
        return response()->json(['message'=>'Add Transaction successfully']);
    }

    function updateTransaction()    {

        $id = \Request::input('id');
        $Transaction = Transaction::where('id',$id)
            ->update([
                'sender_id' => \Request::input('sender_id'),
                'receiver_id' => \Request::input('receiver_id'),
                'amount' => \Request::input('amount')
            ]);

        return response()->json(['Message' => 'Transaction Updated']);

    }

    public function getTransaction()   {

        $Transaction = Transaction::get();
        return response()->json(['Transactions' => $Transaction]);

    }

    function deleteTransaction($id)    {
        
        $Transaction = Transaction::where('id',$id)->delete();

        return response()->json(['message'=>'Delete Transaction successfully']);

    }

    public function getTransactionById($id)     {
        
        $Transaction = Transaction::where('id',$id)->get();

        return response()->json(['Transaction' => $Transaction]);

    }
}
