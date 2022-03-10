<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Config;
class ProductsExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
    	$products = Product::with('category')->get();
    	foreach ($products as $key => $value) {
    		$value->status == Config::get('constants.STATUS_ACTIVE') ? $value->status = "Đang hiển thị" : $value->status = "Đang ẩn";
    		$value->is_hot == Config::get('constants.HOT_PRODUCT') ? $value->is_hot = "Hot" : $value->is_hot = "Không hot";
    		$value->description = strip_tags($value->description);
    		$arr[$key] = array([$value->id, $value->category->name, $value->name, $value->image, $value->description, $value->unit_price, $value->promotional_price, $value->status, $value->is_hot, $value->created_at ]);
    	}
    	array_unshift($arr, array('ID', 'CATEGORY_NAME', 'NAME', 'IMAGE_NAME', 'DESCRIPTION', 'UNIT_PRICE', 'PROMOTION_PRICE', 'STATUS', 'HOT', 'CREATED_AT'));
        return  $arr;
    }
}
