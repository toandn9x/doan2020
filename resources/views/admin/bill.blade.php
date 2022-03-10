@extends('admin.layout.app')
@section('title', 'QL đơn hàng')
@section('content_admin')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>QL Đơn hàng</h1>
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
                <a href="{{ route('g_export_bill') }}" class="btn btn-warning">Xuất excel</a> |
                <a href="javascript:void(0)" class="btn btn-danger" style="" id="bill_delete">Xóa</a>
                <!-- SEARCH FORM -->
      			    <form class="form-inline ml-3 float-right">
      			      <div class="input-group input-group-sm">
      			        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" id="bill_search">
      			        {{-- <div class="input-group-append">
      			          <button class="btn btn-navbar" type="button" style="border: 0.5px solid #ccc">
      			            <i class="fas fa-search"></i>
      			          </button>
      			        </div> --}}
      			      </div>
                  <div class="input-group input-group-sm">
                    <select class="form-control" id="bill_customer">
                      <option value="">--Tên KH--</option>
                      @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="input-group input-group-sm">
                    <select class="form-control" id="bill_user">
                      <option value="">--Tên tài khoản--</option>
                      @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="input-group input-group-sm">
                    <select class="form-control" id="bill_status">
                      <option value="">--Trạng thái--</option>
                      <option value="đã tiếp nhận">Đã tiếp nhận</option>
                      <option value="đang xử lý">Đang xử lý</option>
                      <option value="đang vận chuyển">Đang vận chuyển</option>
                      <option value="đã giao hàng">Đã giao hàng</option>
                      <option value="từ chối nhận">Từ chối nhận</option>
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
        					    <th scope="col">Tên KH</th>
        					    <th scope="col">Tên tài khoản</th>
                      <th scope="col">Địa chỉ</th>
                      <th scope="col">Số điện thoại</th>
        					    <th scope="col">Tổng tiền</th>
        					    <th scope="col">Hình thức thanh toán</th>
        					    <th scope="col">Hình thức vận chuyển</th>
        					    <th scope="col" class="col-md-4">Trạng thái</th>
                      <th scope="col">Ngày tạo</th>
                      <th scope="col">Ngày cập nhật</th>
                      <th scope="col">Thao tác</th>
        				    </tr>
        				  </thead>
        				  <tbody id="bill_table">
        				  	@foreach ($bills as $key => $bill)
        				        <tr>
                          <th scope="row"><input class="form-check-input" type="checkbox" value="{{ $bill->id }}" id="{{ $bill->id }}"></th>
        					      	<th scope="row">{{ $key+1 }}</th>
        					      	<td>{{ \App\Models\Customer::find($bill->id_customer)->name }}</td>
        					      	<td>{{ \App\Models\User::find($bill->id_user)->name }}</td>
                          <td>{{ \App\Models\Customer::find($bill->id_customer)->address }}</td>
                          <td>{{ \App\Models\Customer::find($bill->id_customer)->phone }}</td>
        					      	<td>{{ number_format($bill->total_price) }}</td>
                          <td>{{ $bill->payment }}</td>
                          <td>{{ $bill->type_transport }}</td>
                          <td>
                            <select class="form-control bill_note" data-id="{{ $bill->id }}">
                              <option value="đã tiếp nhận" 
                              @if($bill->note == 'đã tiếp nhận')
                              selected
                              @endif
                              >Đã tiếp nhận</option>
                              <option value="đang xử lý"
                              @if($bill->note == 'đang xử lý')
                              selected
                              @endif
                              >Đang xử lý</option>
                              <option value="đang vận chuyển"
                              @if($bill->note == 'đang vận chuyển')
                              selected
                              @endif
                              >Đang vận chuyển</option>
                              <option value="đã giao hàng"
                              @if($bill->note == 'đã giao hàng')
                              selected
                              @endif
                              >Đã giao hàng</option>
                              <option value="từ chối nhận"
                              @if($bill->note == 'từ chối nhận')
                              selected
                              @endif
                              >Từ chối nhận</option>
                            </select>
                          </td>
                          <td>{{ $bill->created_at }}</td>
                          <td>{{ $bill->updated_at }}</td>
        					      	<td>
                            <a href="javascript:void(0)" onclick="showModal({{$bill->id}})"><i class="fas fa-eye"></i></a>&emsp;
                          	<a href="javascript:void(0)" onclick="ajaxDeleteBill({{ $bill->id }})"><i class="fas fa-trash"></i></a>
        	                </td>
        				    	</tr>
        				    @endforeach
        				  </tbody>
				        </table>
            </div>
				<div class="card-footer">
        	<div class="row">
            <div class="col-md-4" id="count_bill">Show {{ $bills->count() }} of {{ $bills->total() }} result</div>
            <div class="col-md-8">  	
            	<ul class="pagination float-right">
            		{{-- Nguyên tắc : (currentPage - 1) * LIMIT --}}
            		<li class="page-item"><a class="page-link" href="admidon-hang?page=1">First</a></li>
            		{{-- nếu page hiện tại > page đầu thì hiện nút privew --}}
            		@if($bills->currentPage() > 1)
			    	        <li class="page-item"><a class="page-link" href="admin/ql-don-hang?page={{ $bills->currentPage() - 1 }}">Previous</a></li>
			          @endif
			              <li class="page-item active"><a class="page-link" href="admin/ql-don-hang?page={{ $bills->currentPage() }}">{{ $bills->currentPage() }}</a></li>
			          @if($bills->currentPage() < $bills->lastPage())
				            <li class="page-item"><a class="page-link" href="admin/ql-don-hang?page={{ $bills->currentPage() + 1 }}">{{ $bills->currentPage() + 1 }}</a></li>
                    @if($bills->currentPage() < $bills->lastPage() - 2)
				            <li class="page-item"><a class="page-link" href="admin/ql-don-hang?page={{ $bills->currentPage() + 2 }}">{{ $bills->currentPage() + 2 }}</a></li>
                    @endif
                    @if($bills->currentPage() < $bills->lastPage() - 3)
				            <li class="page-item"><a class="page-link" href="admin/ql-don-hang?page={{ $bills->currentPage() + 3 }}">{{ $bills->currentPage() + 3 }}</a></li>
                    @endif
				        @endif
  							{{-- nếu page hiện tại < page cuối thì hiện nút next --}}
  							@if($bills->currentPage() < $bills->lastPage())
  								  <li class="page-item"><a class="page-link" href="admin/ql-don-hang?page={{ $bills->currentPage() + 1 }}">Next</a>
  							@endif
			          <li class="page-item"><a class="page-link" href="admin/ql-don-hang?page={{ $bills->lastPage() }}">Last</a></li>
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
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
     <table class="table" id="bill_detail">
        <thead>
          <tr>
            <td>Mã Đơn Hàng: <b id="bill_code"></b></td>
          </tr>
          <tr>
            <th>#</th>
            <th>Tên SP</th>
            <th>Ảnh</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>

          </tr>
        </thead>
        <tbody>
          {{-- append html  --}}
        </tbody>
      </table>
    </div>
  </div>
</div>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
@stop
@section('script')
<script>
$(document).ready(function(){
  // ajax tìm kiếm
	$('#bill_search').on('keyup',function(){
        load_ajax();
    })
    $('#bill_status').on('change',function(){
        load_ajax();
    })
    $('#bill_customer').on('change',function(){
        load_ajax();
    })
    $('#bill_user').on('change',function(){
        load_ajax();
    })
    function load_ajax(){
      $.ajax({
          url : '{{ route('g_search_bill') }}',
          type : 'get',
          data : {
                 'key' : $('#bill_search').val(),
                 'status' : $('#bill_status').val(),
                 'id_customer' : $('#bill_customer').val(),
                 'id_user'  : $('#bill_user').val(),
                },
          success : function (data){
              //console.log(data);
              $('#bill_table').html(data);
              $('#count_bill').html('Tìm thấy <b>' + $('#bill_table').find('tr').length + '</b> Kết quả');
          }
      });
    }
});
</script>
<script>
  // ajax xóa
  function ajaxDeleteBill(id) {
    //alert(id);
    $.confirm({
      title: 'Xác Nhận!',
      content: 'Bạn có chắc chắn muốn xóa (những) đơn hàng này ?',
      type: 'red',
      typeAnimated: true,
      buttons: {
          tryAgain: {
              text: 'Ok',
              btnClass: 'btn-red',
              action: function(){
                $.ajax({
                    url : '{{ route('p_delete_bill') }}',
                    type : 'get',
                    data : {
                          'id': id,
                          },
                    success : function (data){
                      console.log(data);
                      if(data == "success") {
                        toastr.success('Xóa đơn hàng thành công!');
                        setTimeout(function(){location.reload(); }, 1000);
                      }
                      else {
                        toastr.error('Xóa không thành công, vui lòng chọn một đơn hàng!');
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
$('#bill_delete').click(function(){
  let listId = '';
  $.each($("input[type=checkbox]:checked"), function(){
    listId += $(this).val() + ',';
  });
  listId = listId.substring(0, listId.length - 1);
  ajaxDeleteBill(listId);
})
</script>
{{-- showw modal --}}
<script>
  function showModal(id) {
    $.ajax({
        url : '{{ route('p_show_bill') }}',
        type : 'post',
        data : {
          'id_bill' : id
        },
        success : function (data){
          $('#bill_code').html('#'+id);
          $('#bill_detail tbody').append(data);
          $('.modal').modal('show');
        }
    });
    //
  }
  // xóa data khi click ngoài modal
  $('.modal').on('hidden.bs.modal', function () { 
    $(".modal").find("tr:gt(1)").remove();
  });
  // update status
  $('.bill_note').on('change', function() {
    let id_bill = ($(this).data("id"));
    let status = ($(this).val());
    $.ajax({
        url : '{{ route('p_update') }}',
        type : 'post',
        data : {
          'id_bill' : id_bill,
          'status' : status
        },
        success : function (data){
          toastr.success('Cập nhật trạng thái thành công!');
          //setTimeout(function(){location.reload(); }, 1000);
        }
    });
  })
</script>
@stop