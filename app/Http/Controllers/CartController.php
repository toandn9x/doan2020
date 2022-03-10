<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\BillDetail;
use Cart;
use Session;
use Auth;
use Mail;
class CartController extends Controller
{
    //
    public function viewCart() {
    	return view('cart');
    }
    public function addCart(Request $request) {
    	//Cart::add('293ad', 'Product 1', 1, 9.99, 550);
    	//Cart::destroy();
    	$product = Product::find($request->id);
    	$data['id'] 			= $request->id;
    	$data['qty'] 			= $request->qty;
    	$data['name'] 			= $product->name;
    	$data['price'] 			= $request->price;
    	$data['weight'] 		= 1;
    	$data['options']['img'] = $request->img;
    	if(count(Cart::content()) > 0) {
    		foreach(Cart::content() as $cart) {
    			if($cart->id == $request->id) {
    				Cart::update($cart->rowId, $cart->qty + 1);
    				break;
    			}
    			else {
    				Cart::add($data);
    				break;
    			}
    		}
    	}
    	else {
    		Cart::add($data);
    	}
    	echo json_encode(['status' => 1, 'qty' => count(Cart::content())]);
    }
    public function deleteCart($rowId) {
    	Cart::remove($rowId);
    	return redirect()->back();
    }
    public function deleteAll() {
    	Cart::destroy();
    	return redirect()->back();
    }
    public function order(Request $request) {        
        // thêm thông tin kh vào db
        $customer   = new Customer;
        $customer->name         =   $request->name;
        $customer->email        =   $request->email;
        $customer->address      =   $request->address;
        $customer->phone        =   $request->phone;
        $customer->note         =   $request->note;
        $customer->save();
        // thêm hóa đơn
        $total = 0;
        foreach(Cart::content() as $cart) {
            $total += $cart->price *  $cart->qty;
        }
        $bill = new Bill;
        $bill->id_customer  = $customer->id;
        $bill->id_user      = Auth::id();
        $bill->total_price  = $total;
        $bill->payment      = $request->payment_type;
        $bill->type_transport = $request->type_transport;
        $bill->note         = 'đã tiếp nhận';
        $bill->save();
        // thêm chi tiết hóa đơn
        foreach(Cart::content() as $value) {
            $bill_detail = new BillDetail;
            $bill_detail->id_bill = $bill->id;
            $bill_detail->id_product = $value->id;
            $bill_detail->quantity = $value->qty;
            $bill_detail->unit_price = $value->price;
            $bill_detail->save();
        }
        Cart::destroy();
        
        // gửi email thông báo cho chủ shop //
        $email = 'ngoc98toan@gmail.com';
        Mail::send('email', array('name' => $request->name, 'phone' => $request->phone, 'price' => $total, 'address' => $request->address), function($message) use ($email){
                $message->to($email)->subject('Đơn Hàng mới');
        });

        // check hình thức thanh toán. nếu thanh toán trực tiếp thì return
        // nếu thanh toán vnpay thì gọi hàm create() thanh toán
        if($request->payment_type == 'tiền mặt') {
            echo "<script>
            alert('Đặt hàng thành công. Cảm ơn bạn đã mua hàng');
            window.location='http://localhost/DOAN2020/public/';
            </script>";
        }
        else {
            // tổng tiền, mã ngân hàng, id hóa đơn.
            $url = $this->create($total, $request->bank_code, $bill->id);
            return redirect($url);
        }
        
    }
    public function updateCart(Request $request) {
        Cart::update($request->rowId, $request->qty);
    }
    // thanh toán online VNPAY
    public function create($total, $bank_code, $id_bill)
    {
        $vnp_TmnCode = "Y4U88XFK"; //Mã website tại VNPAY 
        $vnp_HashSecret = "DTHXNFNBUMNKFKQOZVHTXUXNUQUUXMTV"; //Chuỗi bí mật
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost/DOAN2020/public/return";
        $vnp_TxnRef = $id_bill; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán hóa đơn phí dich vụ";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $total * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = $bank_code;
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
           // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }
        return $vnp_Url;
    }
    public function return(Request $request)
    {
        if($request->vnp_ResponseCode == "00") {
            //insert
            return redirect()->route('order_history_detail',$request->vnp_TxnRef);
        }
        else {
            return redirect()->route('v_cart')->with('error', 'Thanh toán không thành công!. Vui lòng thử lại hoặc chọn hình thức thanh toán khác.');
            // Bảng mã lỗi
            // 00  Giao dịch thành công
            // 01  Giao dịch đã tồn tại
            // 02  Merchant không hợp lệ (kiểm tra lại vnp_TmnCode)
            // 03  Dữ liệu gửi sang không đúng định dạng
            // 04  Khởi tạo GD không thành công do Website đang bị tạm khóa
            // 05  Giao dịch không thành công do: Quý khách nhập sai mật khẩu quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch
            // 13  Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.
            // 07  Giao dịch bị nghi ngờ là giao dịch gian lận
            // 09  Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.
            // 10  Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần
            // 11  Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.
            // 12  Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa.
            // 51  Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.
            // 65  Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá hạn mức giao dịch trong ngày.
            // 08  Giao dịch không thành công do: Hệ thống Ngân hàng đang bảo trì. Xin quý khách tạm thời không thực hiện giao dịch bằng thẻ/tài khoản của Ngân hàng này.
            // 99  Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)
        }
    }
}
