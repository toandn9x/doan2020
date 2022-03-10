<?php

namespace App\Exports;

use App\Models\Bill;
use App\Models\Customer;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Config;
class BillsExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
    	$bills = Bill::all();
    	foreach ($bills as $key => $value) {
            $customer_name = Customer::find($value->id_customer)->name;
            $user_name = User::find($value->id_user)->name;
            $arr[$key] = array([$value->id,  $customer_name, $user_name, $value->total_price, $value->payment, $value->type_transport, $value->note, $value->created_at]);
        }
        array_unshift($arr, array('ID', 'CUSTOMER NAME', 'USER NAME', 'TOTAL PRICE', 'PAYMENT', 'TYPE TRANSPORT', 'STATUS', 'CREATED_AT'));
        return  $arr;
    }
}
