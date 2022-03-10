<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Config;
class CustomersExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
    	$customers = Customer::all();
    	foreach ($customers as $key => $value) {
    		$arr[$key] = array([$value->id, $value->name, $value->email, $value->address, $value->phone, $value->note, $value->created_at]);
    	}
    	array_unshift($arr, array('ID', 'NAME', 'EMAIL', 'ADDRESS', 'PHONE', 'NOTE', 'CREATED_AT'));
        return  $arr;
    }
}
