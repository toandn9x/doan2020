<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Config;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Customer;
use App\Models\PasswordResets;
use App\Models\Post;
use App\Models\Slide;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    // share view menu //
    public function __construct() {
        // get menu
        $categories = Category::where('status', Config::get('constants.STATUS_ACTIVE'))
            ->orderBy('order', 'asc')
            ->get();
        // get thông tin liên hệ
        $post = Post::where('description', 'info')->first();
        // get slider
        $slides = Slide::where('status', Config::get('constants.STATUS_ACTIVE'))
            ->orderBy('id', 'DESC')
            ->get();
        view()->share(['categories' => $categories, 'post' => explode("|",$post->content), 'img' => $post->image, 'slides' => $slides]);
    }
    public function index() {
        $products_hot   = Product::where('status', Config::get('constants.STATUS_ACTIVE'))
            ->where('is_hot', Config::get('constants.HOT_PRODUCT'))
            ->limit(8)
            ->inRandomOrder()
            ->get();
        return view('index', ['products_hot' => $products_hot]);
    }
    public function getLogin() {
    	return view('login');
    }
    public function postLogin(Request $request) {
    	if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    		//$id = Auth::id();
    		$user = Auth::user();
    		if($user->level == Config::get('constants.ADMIN_LEVEL')) {
    			return redirect()->route('admin_index')->with('success','Đăng nhập thành công');
    		}
    		else {
    			return redirect()->route('index');
    		}
		}
		else {
        	return redirect()->route('g_login')->with('err', 'Tài khoản hoặc mật khẩu không chính xác');
        }
    }
    public function postRegister(Request $request) {
    	$request->validate([
            'email'     => 'unique:users',
            'password'  => 'same:repass',
            ], [
            'email.unique'  => 'Email đã tồn tại, vui lòng nhập email khác',
            'password.same' => 'Mật khẩu nhập lại không trùng nhau',
        ]);
        $user = new User;
        $user->name     = $request->username;
        $user->email    = $request->email;
        $user->status   = Config::get('constants.STATUS_ACTIVE');
        $user->password = bcrypt($request->password);
        $user->level    = Config::get('constants.USER_LEVEL');
        if(!$user->save()) {
            return redirect()->route('g_login')->with('err2', 'Có lỗi, vui lòng thử lại');
        }
        return redirect()->route('g_login')->with('success', 'Đăng ký thành công, vui lòng đăng nhập');
    }
    public function logOut() {
        Auth::logout();
        return redirect()->route('index');
    }
    // quy trình quên mk:
    // -   hiển thị form nhập email
    // -   kiểm tra email có tồn tại trong db hay không
    // -   email tồn tại thì thêm email và token vào bảng password reset 
    // -   gửi link trang reset mật khẩu kèm token vào email
    // -   ở trang view đổi mật khẩu, dựa vào token để kiểm tra email có tồn tại hay không? nếu
    // tồn tại email thì submit token lên để xử lý
    // -    Dựa vào token để lấy ra email cần dổi mật khẩu, update mk mới
    public function getResetPass() {
        return view('reset_pass');
    }
    public function postResetPass(Request $request) {
        //Tạo token và gửi đường link reset vào email nếu email tồn tại
        $result = User::where('email', $request->email)->first();
        if($result) {
            // nếu có email thì cập nhật email và token vào bảng password_resets
            $resetPassword = PasswordResets::firstOrCreate(['email'=>$request->email, 'token'=>Str::random(60)]);
            $token = PasswordResets::where('email', $request->email)->first();
            // hiển thị link hoặc gọi hàm gửi email
            echo "Click to reset password <a href=".url('doi-mat-khau')."?token=".$token->token.">".url('check-token')."/".$token->token."</a>"; //send it to email
        } else {
            return redirect()->route('g_reset_pass')->with('err', 'Email không có trong hệ thống, vui lòng kiểm tra lại');
        }
    }
    public function getRecoverPass(Request $request) {
        // Check token valid or not
        $token = $request->token;
        // kiểm tra nếu có email thì gửi token của email đó sang view ( ko gủi trực tiếp email vì lý do bảo mật )
        $result = PasswordResets::where('token', $token)->first();
        if($result){
            return view('recover_pass', ['token' => $result->token]);
        } else {
            return redirect()->route('g_login');
        }       
    }
    public function postRecoverPass(Request $request) {       
        // Check password confirm
        if($request->password == $request->repass){
            // Check email with token
            $result = PasswordResets::where('token', $request->token)->first();
            // Update new password 
            User::where('email', $result->email)->update(['password'=>bcrypt($request->password)]);
            // Delete token
            PasswordResets::where('token', $request->token)->delete();
            return redirect()->route('g_login')->with('success', 'Đổi mật khẩu thành công. Bạn có thể đăng nhập !');
        } else {
            return redirect('/doi-mat-khau?token='.$request->token)->with('err', 'Nhập mật khẩu không chính xác!');
        }
    }
    // Màn chi tiết sản phẩm
    public function productDetail($id) {
        $product = Product::find($id);
        // lấy ra các sản phẩm khác cùng loại (cùng id_category)
        $other_product = Product::where('status', Config::get('constants.STATUS_ACTIVE'))
            ->where('id', '!=', $id)
            ->where('id_category', $product->id_category)
            ->get();
        if(isset($product) && ($product->status == Config::get('constants.STATUS_ACTIVE') || $product->status == Config::get('constants.STATUS_SELL_OUT'))) {
            return view('product_detail', ['product' => $product, 'other_product' => $other_product]);
        }
        else return "Sản phẩm không tồn tại!";
        
    }
    public function product($id) {
        $category = Category::find($id);
        if(isset($category)) {
            $products = Product::with('category')
                ->where('id_category', $id)
                ->orderBy('id', 'DESC')
                ->paginate(16);
            return view('product', ['products' => $products, 'category' => $category]);
        }
        else return "Danh mục không tồn tại";
        
    }
    public function search(Request $request) {
        $products = Product::where('name', 'LIKE', '%'.$request->key.'%')
            ->orWhere('unit_price', 'LIKE', '%'.$request->key.'%')
            ->orderBy('id', 'DESC')
            ->paginate(16);
        // ktra kq tồn tại ?
        if($products->isNotEmpty()) {
            $isset = 1;
        }
        else $isset = 0;
        return view('search', ['products' => $products, 'isset' => $isset, 'key' => $request->key]);
    }
    // lịch sử dơn hàng
    public function orderHistory() {
        $id = Auth::id();
        $bills = Bill::where('id_user', $id)
            ->orderBy('id', 'DESC')
            ->paginate(25);
        return view('order_history', ['bills' => $bills]);
    }
    // chi tiết hóa đơn
    public function orderHistoryDetail(Request $request) {
        $bill_info = Bill::find($request->id);
        if(!$bill_info) die();
        $customer_info = Customer::find($bill_info->id_customer);
        $bill_detail = BillDetail::where('id_bill', $request->id)->get();
        foreach ($bill_detail as $key => $detail) {
            $product = Product::find($detail->id_product);
            $data[] = array($product->name, $detail->quantity, $detail->unit_price, $detail->quantity * $detail->unit_price);
        }
        return view('order_history_detail', ['customer_info' => $customer_info, 'bill_info' => $bill_info, 'data' => $data]);
    }

    // chức năng đký nhận tin khuyến mãi
    public function postGetDiscount(Request $request) {
        // check tồn tại
        $user_email = Post::where('description', $request->email)->first();
        if($user_email) die();
        $post = new Post();
        $post->description = $request->email;
        $post->content = 'Khách hàng đăng ký nhận thông tin khuyến mãi';
        $post->status = 2;
        $post->save();
        echo 'success';
    }
    // hiển thị các sp giảm giá
    public function getSale() {
        $products = Product::where('promotional_price', '!=', NULL)
        ->where('promotional_price', '!=', 0)
        ->orderBy('id', 'DESC')
        ->paginate(25);
        return view('best_sale', ['products' => $products]);
    }

    // hiển thị thông tin liên hệ
    public function getContact() {
        return view('about');
    }
}
