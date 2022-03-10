<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Config;
class UsersExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
    	$users = User::all();
    	foreach ($users as $key => $value) {
    		$value->status == Config::get('constants.STATUS_ACTIVE') ? $value->status = "Đang hoạt động" : $value->status = "Huỷ kích hoạt";
    		$value->level == Config::get('constants.ADMIN_LEVEL') ? $value->level = "Quản trị viên" : $value->level = "Người dùng";
    		$arr[$key] = array([$value->id, $value->name, $value->email, $value->status, $value->level, $value->created_at]);
    	}
    	array_unshift($arr, array('ID', 'NAME', 'EMAIL', 'STATUS', 'LEVEL', 'CREATED_AT'));
        return  $arr;
    }
}
