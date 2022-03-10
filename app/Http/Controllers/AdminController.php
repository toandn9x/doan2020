<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Config;
use App\Exports\UsersExport;
use App\Exports\CategoryExport;
use App\Exports\ProductsExport;
use App\Exports\BillsExport;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Post;
use App\Models\Slide;



class AdminController extends Controller
{
    // dashboard //
    public function adminIndex(Request $req) {
        // thống kê
        // nếu submit form thì lấy theo ngày tháng submit
        // không thì lấy theo tgian hiện tại
        if($req->date) {
            $date = explode("-", $req->date);
            $day =  $date[2];
            $month = $date[1];
            $year = $date[0];
        }
        else {
            $day =  date('d');
            $month = date('m');
            $year = date('Y');
        }
        $products   = Product::all()->count();
        $bills      = Bill::all();
        $users      = User::all()->count();
        $total = Bill::where('note', 'LIKE', 'đã giao hàng')
                ->whereMonth('created_at', '=', date('m'))
                ->sum('total_price');
        // data chartJs
        $bill_receive       = Bill::where('note', 'LIKE', 'đã tiếp nhận')->whereDay('updated_at', '=', $day)->whereMonth('created_at', $month)->count();
        $bill_handling      = Bill::where('note', 'LIKE', 'đang xử lý')->whereDay('updated_at', '=', $day)->whereMonth('created_at', $month)->count();
        $bill_transport     = Bill::where('note', 'LIKE', 'đang vận chuyển')->whereDay('updated_at', '=', $day)->whereMonth('created_at', $month)->count();
        $bill_delivered     = Bill::where('note', 'LIKE', 'đã giao hàng')->whereDay('updated_at', '=', $day)->whereMonth('created_at', $month)->count();
        $bill_reject        = Bill::where('note', 'LIKE', 'từ chối nhận')->whereDay('updated_at', '=', $day)->whereMonth('created_at', $month)->count();
        $data = array(['bill_receive' => $bill_receive, 'bill_handling' => $bill_handling,'bill_transport' => $bill_transport, 'bill_delivered' => $bill_delivered, 'bill_reject' => $bill_reject]);
    	return view('admin.index', ['products' => $products, 'bills' => $bills, 'users' => $users, 'totals' => $total, 'data' => $data, 'day' => $day, 'month' => $month, 'year' => $year]);
    }

    // Quản lý tài khoản  //
    public function getUser() {
    	$users = DB::table('users')->orderBy('id', 'DESC')->paginate(15);
    	//dd($users);
    	return view('admin.user', ['users' => $users]);
    }
    public function getAddUser() {
    	return view('admin.user_add');
    }
    public function postAddUser(Request $request) {
    	$request->validate([
            'email'     => 'unique:users',
            'password'  => 'same:repass',
            ], [
            'email.unique'  => 'Email đã tồn tại, vui lòng nhập email khác',
            'password.same' => 'Mật khẩu nhập lại không trùng nhau',
        ]);
        $user = new User;
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->status   = Config::get('constants.STATUS_ACTIVE');
        $user->password = bcrypt($request->password);
        $user->level 	= $request->level;
        if(!$user->save()) {
            return redirect()->back()->with('err', 'Có lỗi, vui lòng thử lại');
        }
        return redirect()->back()->with('success', 'Thêm tài khoản thành công!');
    }
    // tìm kiếm
    public function searchUser(Request $request) {
        $key = $request->key;
        $output = '';
        $users = DB::table('users')
           ->where('status', 'LIKE', '%'.$request->status.'%')
           ->where('level', 'LIKE', '%'.$request->level.'%')
           ->where(function ($query) use ($key) {
               $query->where('name', 'LIKE', '%'.$key.'%')
                     ->orWhere('email', 'LIKE', '%'.$key.'%')
                     ->orWhere('created_at', 'LIKE', '%'.$key.'%');
           })
           ->get();
        if ($users) {
            foreach ($users as $key => $user) {
                $user->status == Config::get('constants.STATUS_ACTIVE') ? $user->status = "✔ Đang hoạt động" : $user->status = "<span style='color:red'>✘ Huỷ kích hoạt</span>";
                $user->level == Config::get('constants.ADMIN_LEVEL') ? $user->level = "<span style='color:red'>Quản trị viên</span>" : $user->level = "Người dùng";
                $output .= '<tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="'.$user->id.'" id="'.$user->id.'"></th>
                <th scope="row">' . ($key + 1) . '</th>
                <td>' . $user->name . '</td>
                <td>' . $user->email . '</td>
                <td>' . $user->status . '</td>
                <td>' . $user->level . '</td>
                <td>' . $user->created_at . '</td>
                <td>| 
                    <a href="admin/ql-tai-khoan/sua/'.$user->id.'"><i class="fas fa-edit"></i></a> |
                    <a href="javascript:void(0)" onclick=ajaxDeleteUser('.$user->id.')><i class="fas fa-trash"></i></a> | 
                    </td>
                </tr>';
            }
            echo $output;
        }
    }
    // end tìm kiếm
    public function getEditUser($id) {
       $user = User::find($id);
       if(isset($user)) {
           return view('admin.user_edit', ['user' => $user]);
       }
       else return redirect()->back();
    }
    public function postEditUser(Request $request) {
        // nếu người dùng đổi email thì validate email
        if($request->email != $request->current_email) {
            $request->validate([
            'email'     => 'unique:users',
            ], [
            'email.unique'  => 'Email ' .$request->email. ' đã tồn tại, vui lòng nhập email khác',
            ]);
        }
        // nếu đổi mật khẩu mới thì mã hóa. mật khẩu cũ không cần vì đã được mã hóa sẵn
        if($request->password != $request->current_password) {
            $request->password = bcrypt($request->password);
        }
        $user = User::find($request->id);
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->status   = $request->status;
        $user->password = $request->password;
        $user->level    = $request->level;
        if(!$user->save()) {
            return redirect()->back()->with('err', 'Có lỗi, vui lòng thử lại');
        }
        return redirect()->back()->with('success', 'Cập nhật tài khoản thành công!');

    }
    public function postDeleteUser(Request $request) {
        $arrId = explode(",",$request->id);
        foreach ($arrId as $id) {
            if($id == '' || $id == NULL) {
                return 'error';
                die();
            }
            $user = User::find($id);
            $user->delete();
        }
        return "success";
    }
    public function exportUser() {
        return Excel::download(new UsersExport, date('d-m-Y').'_users.xlsx');
    }
    // end/
    // QL danh mục/////////////////////////
    ///////////////////////////////////////

    public function getCategory() {
        $categories = DB::table('categories')->orderBy('id', 'DESC')->paginate(15);
        return view('admin.category', ['categories' => $categories]);
    }
    public function getAddCategory() {
        return view('admin.category_add');
    }
    public function postAddCategory(Request $request) {
        $order = Category::all()->count();
        $name = Category::where('name', $request->category_name)->first();
        if(isset($name)) {
            return redirect()->back()->with('error', 'Tên danh mục đã tồn tại!');
        }
        $category = new Category;
        $category->name         = $request->category_name;
        $category->description  = $request->category_description;
        $category->status       = $request->category_status;
        $category->order        = $order + 1;
        if ($request->hasFile('category_img')) {
            $file       = $request->file('category_img');
            $name       = $file->getClientOriginalName();
            $extension  = $file->extension();
            $newname    = rand()."_".date("d_m_Y")."_".$name;
            $file->move("images", $newname);
            $category->image = $newname;
        }
        else $category->image = "";
        $category->save();
        return redirect()->back()->with('success', 'Thêm danh mục thành công!');
    }
    public function getEditCategory($id) {
        $category = Category::find($id);
        if(isset($category)) {
           return view('admin.category_edit', ['category' => $category]);
        }
        else return redirect()->back();
    }
    public function postEditCategory(Request $request) {
        // nếu đổi tên thì check tên mới có trùng trong db không ?
        if($request->category_name != $request->current_name) {
            $name = Category::where('name', $request->category_name)->first();
            if(isset($name)) {
                return redirect()->back()->with('error', 'Tên danh mục đã tồn tại!');
            }
        }
        $category = Category::find($request->id);
        $category->name         = $request->category_name;
        $category->description  = $request->category_description;
        $category->status       = $request->category_status;
        $category->order        = $request->category_order;
        if(isset($category->image) && $category->image != '' && $category->image != NULL) {
            $old_file_path      = "images/".$category->image;
        }
        else $old_file_path = '';
        if($request->hasFile("category_img")) {
            $file       = $request->file('category_img');
            $name       = $file->getClientOriginalName();
            $extension  = $file->extension();
            $newname    = rand()."_".date("d_m_Y")."_".$name;
            $file->move("images", $newname);
            // nếu tồn tại file cũ thì xóa
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
            $category->image = $newname;
        }
        $category->save();
        return redirect()->back()->with('success', 'Sửa danh mục thành công!');
    }
    public function searchCategory(Request $request) {
        $key = $request->key;
        $output = '';
        $categories = DB::table('categories')
           ->where('status', 'LIKE', '%'.$request->status.'%')
           ->where(function ($query) use ($key) {
               $query->where('name', 'LIKE', '%'.$key.'%')
                     ->orWhere('description', 'LIKE', '%'.$key.'%')
                     ->orWhere('created_at', 'LIKE', '%'.$key.'%');
           })
           ->get();
        if ($categories) {
            foreach ($categories as $key => $category) {
                $category->status == Config::get('constants.STATUS_ACTIVE') ? $category->status = "✔ Đang hiển thị" : $category->status = "<span style='color:red'>✘ Đang ẩn</span>";
                $output .= '<tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="'.$category->id.'" id="'.$category->id.'"></th>
                <th scope="row">' . ($key + 1) . '</th>
                <td>' . $category->name . '</td>
                <td><img src="images/' . $category->image . '" style="width: 200px; height: 150px"></td>
                <td>' . $category->description . '</td>
                <td>' . $category->status . '</td>
                <td>' . $category->order . '</td>
                <td>' . $category->created_at . '</td>
                <td>| 
                    <a href="admin/ql-danh-muc/sua/'.$category->id.'"><i class="fas fa-edit"></i></a> |
                    <a href="javascript:void(0)" onclick=ajaxDeleteCategory('.$category->id.')><i class="fas fa-trash"></i></a> | 
                    </td>
                </tr>';
            }
            echo $output;
        }
    }
    public function postDeleteCategory(Request $request) {
        
        $arrId = explode(",",$request->id);
        foreach ($arrId as $id) {
            if($id == '' || $id == NULL) {
                return 'error';
                die();
            }
            $category = Category::find($id);
            $category->delete();
        }
        return "success";
    }
    public function exportCategory() {
        return Excel::download(new CategoryExport, date('d-m-Y').'_categories.xlsx');
    }
    /////////////////////////
    // Quản lý sản phẩm /////
    /////////////////////////

    public function getProduct() {
        $categories = Category::where('status', Config::get('constants.STATUS_ACTIVE'))->get();
        $products = Product::with('category')->orderBy('id', 'DESC')->paginate(15);
        return view('admin.product', ['products' => $products, 'categories' => $categories]);
    }
    public function getAddProduct() {
        $categories = Category::where('status', Config::get('constants.STATUS_ACTIVE'))->get();
        return view('admin.product_add', ['categories' => $categories]);
    }
    public function postAddProduct(Request $request) {
        if($request->is_hot == '' || $request->is_hot == NULL) $request->is_hot = 0;
        $products = new Product;
        $products->id_category          = $request->product_category;
        $products->name                 = $request->product_name;
        $products->description          = $request->product_description;
        $products->unit_price           = $request->product_unit_price;
        $products->promotional_price    = $request->product_promotion_price;
        $products->status               = $request->product_status;
        $products->is_hot               = $request->is_hot;
        if ($request->hasFile('product_img')) {
            $file       = $request->file('product_img');
            $name       = $file->getClientOriginalName();
            $extension  = $file->extension();
            $newname    = rand()."_".date("d_m_Y")."_".$name;
            $file->move("images", $newname);
            $products->image = $newname;
        }
        else $products->image = "";
        $products->save();
        return redirect()->back()->with('success', 'Thêm sản phẩm thành công!');
    }
    public function getEditProduct($id) {
        $categories = Category::where('status', Config::get('constants.STATUS_ACTIVE'))
        ->get();
        $products = Product::find($id);
        if(isset($products)) {
           return view('admin.product_edit', ['products' => $products, 'categories' => $categories]);
        }
        else return redirect()->back();
    }
    public function postEditProduct(Request $request) {
        // //nếu đổi tên thì check tên mới có trùng trong db không ?
        // if($request->category_name != $request->current_name) {
        //     $name = Category::where('name', $request->category_name)->first();
        //     if(isset($name)) {
        //         return redirect()->back()->with('error', 'Tên danh mục đã tồn tại!');
        //     }
        // }
        $product = Product::find($request->id);
        $product->name                 = $request->product_name;
        $product->description          = $request->product_description;
        $product->unit_price           = $request->product_unit_price;
        $product->promotional_price    = $request->product_promotion_price;
        $product->status               = $request->product_status;
        $product->is_hot               = $request->is_hot;
        if(isset($product->image) && $product->image != '' && $product->image != NULL) {
            $old_file_path      = "images/".$product->image;
        }
        else $old_file_path = '';
        if ($request->hasFile('product_img')) {
            $file       = $request->file('product_img');
            $name       = $file->getClientOriginalName();
            $extension  = $file->extension();
            $newname    = rand()."_".date("d_m_Y")."_".$name;
            $file->move("images", $newname);
            // nếu tồn tại file cũ thì xóa
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
            $product->image = $newname;
        }
        $product->save();
        return redirect()->back()->with('success', 'Sửa sản phẩm thành công!');
    }
    public function searchProduct(Request $request) {
        $key = $request->key;
        $output = '';
        $products = Product::with('category')
            ->where('id_category', 'LIKE', '%'.$request->id_category.'%')
            ->where('status', 'LIKE', '%'.$request->status.'%')
            ->where('is_hot', 'LIKE', '%'.$request->hot.'%')
            ->where(function ($query) use ($key) {
               $query->where('name', 'LIKE', '%'.$key.'%')
                     ->orWhere('description', 'LIKE', '%'.$key.'%')
                     ->orWhere('unit_price', 'LIKE', '%'.$key.'%')
                     ->orWhere('promotional_price', 'LIKE', '%'.$key.'%')
                     ->orWhere('created_at', 'LIKE', '%'.$key.'%');
            })
            ->get();
        if ($products) {
            foreach ($products as $key => $product) {
                if($product->status == Config::get('constants.STATUS_ACTIVE')) {
                    $product->status = "✔ Đang hiển thị";
                }
                elseif($product->status == Config::get('constants.STATUS_UNACTIVE')) {
                    $product->status = "<span style='color:red'>✘ Đang ẩn</span>";
                }
                else {
                    $product->status = "<span style='color:red'>〤 Hết hàng</span>";
                }
                $product->is_hot == Config::get('constants.HOT_PRODUCT') ? $product->is_hot = "<span style='color:red'>» HOT «</span>" : $product->is_hot = "Không hot";
                $output .= '<tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="'.$product->id.'" id="'.$product->id.'"></th>
                <th scope="row">' . ($key + 1) . '</th>
                <td>' . $product->name . '</td>
                <td><img src="images/' . $product->image . '" style="width: 200px; height: 150px"></td>
                <td>' . $product->description . '</td>
                <td>' . $product->category->name . '</td>
                <td>' . number_format($product->unit_price) . '</td>
                <td>' . number_format($product->promotional_price) . '</td>
                <td>' . $product->status . '</td>
                <td>' . $product->is_hot . '</td>
                <td>' . $product->created_at . '</td>
                <td>
                    <a href="admin/ql-san-pham/sua/'.$product->id.'"><i class="fas fa-edit"></i></a>&emsp;
                    <a href="javascript:void(0)" onclick=ajaxDeleteProduct('.$product->id.')><i class="fas fa-trash"></i></a>
                    </td>
                </tr>';
            }
            echo $output;
        }
    }
    public function postDeleteProduct(Request $request) {
        
        $arrId = explode(",",$request->id);
        foreach ($arrId as $id) {
            if($id == '' || $id == NULL) {
                return 'error';
                die();
            }
            $product = Product::find($id);
            $product->delete();
        }
        return "success";
    }
    public function exportProduct() {
        return Excel::download(new ProductsExport, date('d-m-Y').'_products.xlsx');
    }
    //end
    /////////////////////////
    // Quản lý đơn hàng /////
    /////////////////////////
    public function getBill() {
        $bills = DB::table('bills')->orderBy('id', 'DESC')->paginate(15);
        $customers = Customer::all();
        $users     = User::all();
        return view('admin.bill', ['bills' => $bills, 'customers' => $customers, 'users' => $users]);
    }
    public function searchBill(Request $request) {
        $key = $request->key;
        $output = '';
        $bills = DB::table('bills')
            ->where('note', 'LIKE', '%'.$request->status.'%')
            ->where('id_customer', 'LIKE', '%'.$request->id_customer.'%')
            ->where('id_user', 'LIKE', '%'.$request->id_user.'%')
            ->where(function ($query) use ($key) {
               $query->where('total_price', 'LIKE', '%'.$key.'%')
                     ->orWhere('payment', 'LIKE', '%'.$key.'%')
                     ->orWhere('type_transport', 'LIKE', '%'.$key.'%')
                     ->orWhere('created_at', 'LIKE', '%'.$key.'%')
                     ->orWhere('updated_at', 'LIKE', '%'.$key.'%');
            })
            ->get();
        if ($bills) {
            foreach ($bills as $key => $bill) {
                $user = User::find($bill->id_user);
                $customer = Customer::find($bill->id_customer);
            switch ($bill->note) {
                case 'đã tiếp nhận':
                    $select = '
                    <select class="form-control bill_note" data-id="'.$bill->id.'">
                      <option value="đã tiếp nhận" selected>Đã tiếp nhận</option>
                      <option value="đang xử lý">Đang xử lý</option>
                      <option value="đang vận chuyển">Đang vận chuyển</option>
                      <option value="đã giao hàng">Đã giao hàng</option>
                      <option value="từ chối nhận">Từ chối nhận</option>
                    </select>
                    <script>
                        $(".bill_note").on("change", function() {
                            let id_bill = ($(this).data("id"));
                            let status = ($(this).val());
                            $.ajax({
                                url : "admin/ql-don-hang/update",
                                type : "post",
                                data : {
                                  "id_bill" : id_bill,
                                  "status" : status
                                },
                                success : function (data){
                                  toastr.success("Cập nhật trạng thái thành công!");
                                  return;
                                }
                            });
                          })
                    </script>
                    ';
                   break;
                case 'đang xử lý':
                    $select = '
                    <select class="form-control bill_note" data-id="'.$bill->id.'">
                      <option value="đã tiếp nhận">Đã tiếp nhận</option>
                      <option value="đang xử lý" selected>Đang xử lý</option>
                      <option value="đang vận chuyển">Đang vận chuyển</option>
                      <option value="đã giao hàng">Đã giao hàng</option>
                      <option value="từ chối nhận">Từ chối nhận</option>
                    </select>
                    <script>
                        $(".bill_note").on("change", function() {
                            let id_bill = ($(this).data("id"));
                            let status = ($(this).val());
                            $.ajax({
                                url : "admin/ql-don-hang/update",
                                type : "post",
                                data : {
                                  "id_bill" : id_bill,
                                  "status" : status
                                },
                                success : function (data){
                                  toastr.success("Cập nhật trạng thái thành công!");
                                  return;
                                }
                            });
                          })
                    </script>
                    ';
                   break;
                   case 'đang vận chuyển':
                    $select = '
                    <select class="form-control bill_note" data-id="'.$bill->id.'">
                      <option value="đã tiếp nhận" >Đã tiếp nhận</option>
                      <option value="đang xử lý">Đang xử lý</option>
                      <option value="đang vận chuyển" selected>Đang vận chuyển</option>
                      <option value="đã giao hàng">Đã giao hàng</option>
                      <option value="từ chối nhận">Từ chối nhận</option>
                    </select>
                    <script>
                        $(".bill_note").on("change", function() {
                            let id_bill = ($(this).data("id"));
                            let status = ($(this).val());
                            $.ajax({
                                url : "admin/ql-don-hang/update",
                                type : "post",
                                data : {
                                  "id_bill" : id_bill,
                                  "status" : status
                                },
                                success : function (data){
                                  toastr.success("Cập nhật trạng thái thành công!");
                                  return;
                                }
                            });
                          })
                    </script>
                    ';
                   break;
                   case 'đã giao hàng':
                    $select = '
                    <select class="form-control bill_note" data-id="'.$bill->id.'">
                      <option value="đã tiếp nhận">Đã tiếp nhận</option>
                      <option value="đang xử lý">Đang xử lý</option>
                      <option value="đang vận chuyển">Đang vận chuyển</option>
                      <option value="đã giao hàng" selected>Đã giao hàng</option>
                      <option value="từ chối nhận">Từ chối nhận</option>
                    </select>
                    <script>
                        $(".bill_note").on("change", function() {
                            let id_bill = ($(this).data("id"));
                            let status = ($(this).val());
                            $.ajax({
                                url : "admin/ql-don-hang/update",
                                type : "post",
                                data : {
                                  "id_bill" : id_bill,
                                  "status" : status
                                },
                                success : function (data){
                                  toastr.success("Cập nhật trạng thái thành công!");
                                  return;
                                }
                            });
                          })
                    </script>
                    ';
                   break;
                   case 'từ chối nhận':
                    $select = '
                    <select class="form-control bill_note" data-id="'.$bill->id.'">
                      <option value="đã tiếp nhận">Đã tiếp nhận</option>
                      <option value="đang xử lý">Đang xử lý</option>
                      <option value="đang vận chuyển">Đang vận chuyển</option>
                      <option value="đã giao hàng">Đã giao hàng</option>
                      <option value="từ chối nhận" selected>Từ chối nhận</option>
                    </select>
                    <script>
                        $(".bill_note").on("change", function() {
                            let id_bill = ($(this).data("id"));
                            let status = ($(this).val());
                            $.ajax({
                                url : "admin/ql-don-hang/update",
                                type : "post",
                                data : {
                                  "id_bill" : id_bill,
                                  "status" : status
                                },
                                success : function (data){
                                  toastr.success("Cập nhật trạng thái thành công!");
                                  return;
                                }
                            });
                          })
                    </script>
                    ';
                default:
                    break;
            }
                $output .= '<tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="'.$bill->id.'" id="'.$bill->id.'"></th>
                <th scope="row">' . ($key + 1) . '</th>
                <td>' . $customer->name . '</td>
                <td>' . $user->name . '</td>
                <td>' . $customer->address . '</td>
                <td>' . $customer->phone . '</td>
                <td>' . number_format($bill->total_price) . '</td>
                <td>' . $bill->payment . '</td>
                <td>' . $bill->type_transport . '</td>
                <td>
                    '.$select.'
                </td>
                <td>' . $bill->created_at . '</td>
                <td>
                    <a href="javascript:void(0)" onclick="showModal('.$bill->id.')"><i class="fas fa-eye"></i></a>&emsp;
                    <a href="javascript:void(0)" onclick=ajaxDeleteBill('.$bill->id.')><i class="fas fa-trash"></i></a>
                    </td>
                </tr>';
            }
            echo $output;
        }
    }
    // Show chi tiết đơn hàng
    public function showBill(Request $request) {
        $bill_detail = BillDetail::where('id_bill', $request->id_bill)->get();
        foreach ($bill_detail as $key => $detail) {
            $product = Product::find($detail->id_product);
            echo "
                <tr>
                    <td>".($key + 1)."</td>
                    <td>".$product->name."</td>
                    <td><img src='images/$product->image' style='width:100px; height:50px'></td>
                    <td>".number_format($detail->quantity)."</td>
                    <td>".number_format($detail->unit_price)."</td>
                    <td>".number_format($detail->quantity * $detail->unit_price)."</td>
                </tr>
            ";
        }
        
    }
    public function postDeleteBill(Request $request) {
        $arrId = explode(",",$request->id);
        foreach ($arrId as $id) {
            if($id == '' || $id == NULL) {
                return 'error';
                die();
            }
            $bill = Bill::find($id);
            $bill->delete();
            $bill_detail = BillDetail::where('id_bill', $id)->delete();
        }
        return "success";
    }
    public function exportBill() {
        return Excel::download(new BillsExport, date('d-m-Y').'_bills.xlsx');
    }
    public function ajaxUpdateStatus(Request $request) {
        $bill = Bill::find($request->id_bill);
        $bill->note = $request->status;
        $bill->save();
    }
    // End
    ////////////////////////
    // QL khách hàng ///////
    ///////////////////////
    public function getCustomer() {
        $customers = DB::table('customers')->orderBy('id', 'DESC')->paginate(15);
        return view('admin.customer', ['customers' => $customers]);
    }
    // tìm kiếm
    public function searchCustomer(Request $request) {
        $key = $request->key;
        $output = '';
        $customers = DB::table('customers')
           ->where('name', 'LIKE', '%'.$key.'%')
           ->orWhere('email', 'LIKE', '%'.$key.'%')
           ->orWhere('address', 'LIKE', '%'.$key.'%')
           ->orWhere('phone', 'LIKE', '%'.$key.'%')
           ->orWhere('note', 'LIKE', '%'.$key.'%')
           ->orWhere('created_at', 'LIKE', '%'.$key.'%')
           ->get();
        if ($customers) {
            foreach ($customers as $key => $customer) {
                $output .= '<tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="'.$customer->id.'" id="'.$customer->id.'"></th>
                <th scope="row">' . ($key + 1) . '</th>
                <td>' . $customer->name . '</td>
                <td>' . $customer->email . '</td>
                <td>' . $customer->address . '</td>
                <td>' . $customer->phone . '</td>
                <td>' . $customer->note . '</td>
                <td>' . $customer->created_at . '</td>
                <td>|
                    <a href="javascript:void(0)" onclick=ajaxDeleteCustomer('.$customer->id.')><i class="fas fa-trash"></i></a> | 
                    </td>
                </tr>';
            }
            echo $output;
        }
    }
    public function postDeleteCustomer(Request $request) {
        $arrId = explode(",",$request->id);
        foreach ($arrId as $id) {
            if($id == '' || $id == NULL) {
                return 'error';
                die();
            }
            $customer = Customer::find($id);
            $customer->delete();
            $bill = Bill::where('id_customer', $id)->delete();
        }
        return "success";
    }
    public function exportCustomer() {
        return Excel::download(new CustomersExport, date('d-m-Y').'_customers.xlsx');
    }
    // end/
    // Thống kê
    public function statistical(Request $request) {
        //
        if(isset($request->month) && $request->month != '') {
            $now_month = (int)explode("-",$request->month)[1];
        }
        else {
            $now_month = date('m');
        }
        // đếm số tài khoản mới theo tháng 
        $user       = User::whereMonth('created_at', '=', $now_month)->count();
        $customer   = Customer::whereMonth('created_at', '=', $now_month)->count();
        $product    = Product::whereMonth('created_at', '=', $now_month)->count();
        $total_prod = Product::all()->count();
        $bill       = Bill::whereMonth('created_at', '=', $now_month)->count();
        $total_bill = Bill::all()->count();
        $bill_delivered = Bill::whereMonth('updated_at', '=', $now_month)
            ->where('note', 'LIKE', 'đã giao hàng')->count();
        $bill_reject = Bill::whereMonth('updated_at', '=', $now_month)
            ->where('note', 'LIKE', 'từ chối nhận')->count();
        $total = Bill::where('note', 'LIKE', 'đã giao hàng')
                ->sum('total_price');
        // data chartJs
        // get number day of month
        $d=cal_days_in_month(CAL_GREGORIAN,$now_month,date('Y'));
        // select sum(total_price) from bills where month = $month
        // and where day = $i
        $data_sale = array();
        for($i = 1; $i <= $d; $i++) {
            $data_sale[$i] = Bill::where('note', 'LIKE', 'đã giao hàng')
                ->whereDay('updated_at', '=', $i)
                ->whereMonth('created_at', '=', $now_month)
                ->sum('total_price');
        }
        //dd($data_sale);
        return view('admin.statistical',['user' => $user, 'customer' => $customer, 'product' => $product, 'total_prod' => $total_prod, 'bill' => $bill, 'total_bill' => $total_bill, 'bill_delivered' => $bill_delivered, 'bill_reject' => $bill_reject, 'data_sale' => $data_sale, 'total' => $total, 'month' => $now_month
            ]);
    }
    // thông tin liên hệ
    public function contact() {
        $post = Post::where('description', 'info')->first();
        return view('admin.contact', ['post' => explode("|",$post->content), 'img' => $post->image]);
    }
    public function postContact(Request $request) {
        $string_data = $request->introduce.'|'.$request->address.'|'.$request->phone.'|'.$request->email;
        $post = Post::where('description', 'info')->first();
        // nếu có trường info thì update lại thông tin
        if($post) {
            if(isset($post->image) && $post->image != '' && $post->image != NULL) {
                $old_file_path      = "images/".$post->image;
            }
            else $old_file_path = '';
            $post->content = $string_data;
            if ($request->hasFile('logo_img')) {
                $file       = $request->file('logo_img');
                $name       = $file->getClientOriginalName();
                $extension  = $file->extension();
                $newname    = rand()."_".date("d_m_Y")."_".$name;
                $file->move("images", $newname);
                // nếu tồn tại file cũ thì xóa
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
                $post->image = $newname;
            }
            $post->save();
        }
        // không thì thêm mới
        else {
            $post = new Post();
            $post->description = 'info';
            $post->content = $string_data;
            if ($request->hasFile('logo_img')) {
                $file       = $request->file('logo_img');
                $name       = $file->getClientOriginalName();
                $extension  = $file->extension();
                $newname    = rand()."_".date("d_m_Y")."_".$name;
                $file->move("images", $newname);
                $post->image = $newname;
            }
            $post->save();
        }
        return redirect()->back()->with('success', 'Chỉnh sửa thông tin thành công!');
    }
    // ds khuyến mãi
    public function discount() {
        $discount = Post::where("status", 2)->orderBy('id', 'DESC')->paginate(15);
        return view('admin.discount', ['discounts' => $discount]);
    }
    public function searchDiscount(Request $request) {
        $key = $request->key;
        $output = '';
        $discounts = DB::table('posts')
           ->where('description', 'LIKE', '%'.$request->key.'%')
           ->where('status', 2)
           ->get();
        if ($discounts) {
            foreach ($discounts as $key => $discount) {
                $output .= '<tr>
                <th scope="row"><input class="form-check-input" type="checkbox" value="'.$discount->id.'" id="'.$discount->id.'"></th>
                <th scope="row">' . ($key + 1) . '</th>
                <td>' . $discount->description . '</td>
                <td>' . $discount->created_at . '</td>';
            }
            echo $output;
        }
    
    }
    public function deleteDiscount(Request $request) {
        $arrId = explode(",",$request->id);
        foreach ($arrId as $id) {
            if($id == '' || $id == NULL) {
                return 'error';
                die();
            }
            $post = Post::find($id);
            $post->delete();
        }
        return "success";
    }
    //QL slider




    public function slider() {
        $slides = DB::table('slides')->orderBy('id', 'DESC')->paginate(15);
        //dd($users);
        return view('admin.slide', ['slides' => $slides]);
    }
    public function getAddSlide() {
        return view('admin.slide_add');
    }
    public function postAddSlide(Request $request) {
        $slide = new Slide();
        $slide->link    = $request->link;
        $slide->status  = $request->slide_status;
        if ($request->hasFile('slide_img')) {
            $file       = $request->file('slide_img');
            $name       = $file->getClientOriginalName();
            $extension  = $file->extension();
            $newname    = rand()."_".date("d_m_Y")."_".$name;
            $file->move("images", $newname);
            $slide->image = $newname;
        }
        else $slide->image = "";
        $slide->save();
        return redirect()->back()->with('success', 'Thêm slide thành công!');
    }
    public function getEditSlide($id) {
        $slide = Slide::find($id);
        if(isset($slide)) {
           return view('admin.slide_edit', ['slide' => $slide]);
        }
       else return redirect()->back();
    }
    public function postEditSlide(Request $request) {
        $slide = Slide::find($request->id);
        $slide->link            = $request->link;
        $slide->status          = $request->slide_status;
        if(isset($slide->image) && $slide->image != '' && $slide->image != NULL) {
            $old_file_path      = "images/".$slide->image;
        }
        else $old_file_path = '';
        if ($request->hasFile('slide_img')) {
            $file       = $request->file('slide_img');
            $name       = $file->getClientOriginalName();
            $extension  = $file->extension();
            $newname    = rand()."_".date("d_m_Y")."_".$name;
            $file->move("images", $newname);
            // nếu tồn tại file cũ thì xóa
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
            $slide->image = $newname;
        }
        $slide->save();
        return redirect()->back()->with('success', 'Sửa slide thành công!');
    }
    public function postDeleteSlide(Request $request) {
        $arrId = explode(",",$request->id);
        foreach ($arrId as $id) {
            if($id == '' || $id == NULL) {
                return 'error';
                die();
            }
            $slide = Slide::find($id);
            $slide->delete();
        }
        return "success";
    }
    
}
