@extends('admin.layout.app')
@section('title', 'QL Sản phẩm')
@section('content_admin')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>QL Sản phẩm</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <a href="{{ route('g_add_product') }}" class="btn btn-info">Thêm</a> | 
                <a href="{{ route('g_export_product') }}" class="btn btn-warning">Xuất excel</a> |
                <a href="javascript:void(0)" class="btn btn-danger" style="" id="product_delete">Xóa</a>
                <!-- SEARCH FORM -->
      			    <form class="form-inline ml-3 float-right">
      			      <div class="input-group input-group-sm">
      			        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" id="product_search">
      			        {{-- <div class="input-group-append">
      			          <button class="btn btn-navbar" type="button" style="border: 0.5px solid #ccc">
      			            <i class="fas fa-search"></i>
      			          </button>
      			        </div> --}}
      			      </div>
                  <div class="input-group input-group-sm">
                      <select class="form-control" id="product_category">
                        <option value="">--Danh mục--</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" style="font-weight: bold!important">{{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="input-group input-group-sm">
                      <select class="form-control" id="product_status">
                        <option value="">--Trạng thái--</option>
                        <option value="{{ Config::get('constants.STATUS_ACTIVE') }}" style="font-weight: bold!important">Đang hiển thị</option>
                        <option value="{{ Config::get('constants.STATUS_UNACTIVE') }}" style="font-weight: bold!important">Đang ẩn</option>
                        <option value="{{ Config::get('constants.STATUS_SELL_OUT') }}" style="font-weight: bold!important">Hết hàng</option>
                      </select>
                    </div>
      			      	<div class="input-group input-group-sm">
                      <select class="form-control" id="product_hot">
                        <option value="">--Hot--</option>
                        <option value="{{ Config::get('constants.HOT_PRODUCT') }}" style="font-weight: bold!important">HOT</option>
                        <option value="{{ Config::get('constants.UNHOT_PRODUCT') }}" style="font-weight: bold!important">Không hot</option>
                      </select>
                    </div>
      			    </form>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              	<table class="table table-hover">
        				  <thead>
        				    <tr>
                      <th scope="col"></th>
        					    <th scope="col">STT</th>
        					    <th scope="col">Tên</th>
        					    <th scope="col" style="width: 100px; height: 100px">Ảnh</th>
        					    <th scope="col">Mô tả</th>
                      <th scope="col">Danh mục</th>
                      <th scope="col">Giá gốc</th>
                      <th scope="col">Giá khuyến mãi</th>
        					    <th scope="col">Trạng thái</th>
                      <th scope="col">Sản phẩm hot</th>
        					    <th scope="col">Ngày tạo</th>
        					    <th scope="col">Thao tác</th>
        				    </tr>
        				  </thead>
        				  <tbody id="product_table">
        				  	@foreach ($products as $key => $product)
        				        <tr>
                          <th scope="row"><input class="form-check-input" type="checkbox" value="{{ $product->id }}" id="{{ $product->id }}"></th>
        					      	<th scope="row">{{ $key+1 }}</th>
        					      	<td>{{ $product->name }}</td>
                          <td><img src="images/{{ $product->image }}" width="200px" height="150px"></td>
                          <td>{!! $product->description !!}</td>
                          <td>{{ $product->category->name }}</td>
                          <td>{{ number_format($product->unit_price) }}</td>
                          <td>{{ number_format($product->promotional_price) }}</td>
        					      	@if ($product->status == Config::get('constants.STATUS_ACTIVE'))
        					      	<td>✔ Đang hiển thị</td>
                          @elseif ($product->status == Config::get('constants.STATUS_SELL_OUT'))
                          <td><span style="color:red">〤 Hết hàng</span></td>
        					      	@else
        					      	<td><span style="color:red">✘ Đang ẩn</span></td>
        					      	@endif
                          @if ($product->is_hot == Config::get('constants.HOT_PRODUCT'))
                          <td><span style="color:red">» HOT «</span></td>
                          @else
                          <td>Không hot</td>
                          @endif
        					      	<td>{{ $product->created_at }}</td>
        					      	<td>
                          	<a href="admin/ql-san-pham/sua/{{ $product->id }}"><i class="fas fa-edit"></i></a>&emsp;<a href="javascript:void(0)" onclick="ajaxDeleteProduct({{ $product->id }})"><i class="fas fa-trash"></i></a>
        	                </td>
        				    	</tr>
        				    @endforeach
        				  </tbody>
				      </table>
            </div>
				<div class="card-footer">
          <div class="row">
            <div class="col-md-4" id="count_product">Show {{ $products->count() }} of {{ $products->total() }} result</div>
            <div class="col-md-8">    
              <ul class="pagination float-right">
                {{-- Nguyên tắc : (currentPage - 1) * LIMIT --}}
                <li class="page-item"><a class="page-link" href="admin/ql-san-pham?page=1">First</a></li>
                {{-- nếu page hiện tại > page đầu thì hiện nút privew --}}
                @if($products->currentPage() > 1)
                    <li class="page-item"><a class="page-link" href="admin/ql-san-pham?page={{ $products->currentPage() - 1 }}">Previous</a></li>
                @endif
                    <li class="page-item active"><a class="page-link" href="admin/ql-danh-muc?page={{ $products->currentPage() }}">{{ $products->currentPage() }}</a></li>
                @if($products->currentPage() < $products->lastPage())
                    <li class="page-item"><a class="page-link" href="admin/ql-san-pham?page={{ $products->currentPage() + 1 }}">{{ $products->currentPage() + 1 }}</a></li>
                    @if($products->currentPage() < $products->lastPage() - 2)
                    <li class="page-item"><a class="page-link" href="admin/ql-san-pham?page={{ $products->currentPage() + 2 }}">{{ $products->currentPage() + 2 }}</a></li>
                    @endif
                    @if($products->currentPage() < $products->lastPage() - 3)
                    <li class="page-item"><a class="page-link" href="admin/ql-san-pham?page={{ $products->currentPage() + 3 }}">{{ $products->currentPage() + 3 }}</a></li>
                    @endif
                @endif
                {{-- nếu page hiện tại < page cuối thì hiện nút next --}}
                @if($products->currentPage() < $products->lastPage())
                    <li class="page-item"><a class="page-link" href="admin/ql-san-pham?page={{ $products->currentPage() + 1 }}">Next</a>
                @endif
                <li class="page-item"><a class="page-link" href="admin/ql-san-pham?page={{ $products->lastPage() }}">Last</a></li>
              </ul>
            </div>
          </div>
        </div>
              <!-- /.card-body -->
      </div>
            <!-- /.card -->
    </div>
          <!-- /.col -->
  </div>
        <!-- /.row -->
   </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@stop
@section('script')
<script>
$(document).ready(function(){
  // ajax tìm kiếm
	  $('#product_search').on('keyup',function(){
        load_ajax();
    })
    $('#product_category').on('change',function(){
        load_ajax();
    })
    $('#product_status').on('change',function(){
        load_ajax();
    })
    $('#product_hot').on('change',function(){
        load_ajax();
    })
    function load_ajax(){
      $.ajax({
          url : '{{ route('g_search_product') }}',
          type : 'get',
          data : {
                'key'           : $('#product_search').val(),
                'id_category'   : $('#product_category').val(),
                'status'        : $('#product_status').val(),
                'hot'           : $('#product_hot').val()
                },
          success : function (data){
              console.log(data);
              $('tbody').html(data);
              $('#count_product').html('Tìm thấy <b>' + $('#product_table').find('tr').length + '</b> Kết quả');
          }
      });
    }
});
</script>
<script>
  // ajax xóa user
  function ajaxDeleteProduct(id) {
    //alert(id);
    $.confirm({
      title: 'Xác Nhận!',
      content: 'Bạn có chắc chắn muốn xóa (những) sản phẩm khoản này ?',
      type: 'red',
      typeAnimated: true,
      buttons: {
          tryAgain: {
              text: 'Ok',
              btnClass: 'btn-red',
              action: function(){
                $.ajax({
                    url : '{{ route('p_delete_product') }}',
                    type : 'post',
                    data : {
                          'id': id,
                          },
                    success : function (data){
                      console.log(data);
                      if(data == "success") {
                        toastr.success('Xóa sản phẩm thành công!');
                        setTimeout(function(){location.reload(); }, 1000);
                      }
                      else {
                        toastr.error('Xóa không thành công, vui lòng chọn một sản phẩm!');
                      }
                    }
                });
              }
          },
          close: function () {
          }
      }
    });
  }
</script>
<script>
// xóa hàng loạt
$('#product_delete').click(function(){
  let listId = '';
  $.each($("input[type=checkbox]:checked"), function(){
    listId += $(this).val() + ',';
  });
  listId = listId.substring(0, listId.length - 1);
  ajaxDeleteProduct(listId);
})
</script>
@stop