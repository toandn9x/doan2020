<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Config;
class CategoryExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
    	$categories = Category::all();
    	foreach ($categories as $key => $value) {
    		$value->status == Config::get('constants.STATUS_ACTIVE') ? $value->status = "Đang hiển thị" : $value->status = "Đang ẩn";
    		$value->description = strip_tags($value->description);
    		$arr[$key] = array([$value->id, $value->name, $value->description, $value->image, $value->status, $value->created_at]);
    	}
    	array_unshift($arr, array('ID', 'NAME', 'DESCRIPTION', 'IMAGE_NAME', 'STATUS', 'CREATED_AT'));
        return  $arr;
    }
}
