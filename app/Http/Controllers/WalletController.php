<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    function addWallet(Request $request)   {
        
        $Wallet = new Wallet();
        $Wallet->user_id = \Request::input('user_id');
        $Wallet->amount  = \Request::input('amount');
        $Wallet->save();

        return response()->json(['message'=>'Add Wallet successfully']);
    }

    function updateWallet()    {

        $id = \Request::input('id');
        $Wallet = Wallet::where('id',$id)
            ->update([
                'user_id' => \Request::input('user_id'),
                'amount' => \Request::input('amount')
            ]);

        return response()->json(['Message' => 'Wallet Updated']);

    }

    public function getWallet()   {

        $Wallet = Wallet::get();
        return response()->json(['Wallets' => $Wallet]);
    }

    function deleteWallet($id)    {
        
        $Wallet = Wallet::where('id',$id)->delete();

        return response()->json(['message'=>'delete Wallet successfully']);

    }

    public function getWalletById($id)     {
        
        $Wallet = Wallet::
        where('id',$id)->get();
        return response()->json(['Wallet' => $Wallet]);

    }
}
