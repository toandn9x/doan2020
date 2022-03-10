@extends('admin.layout.app')
@section('title', 'QL Khách hàng')
@section('content_admin')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>QL Khách hàng</h1>
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
              <div class="card-header">| 
                <a href="{{ route('g_export_customer') }}" class="btn btn-warning">Xuất excel</a> |
                <a href="javascript:void(0)" class="btn btn-danger" style="" id="customer_delete">Xóa</a>
                <!-- SEARCH FORM -->
      			    <form class="form-inline ml-3 float-right">
      			      <div class="input-group input-group-sm">
      			        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" id="customer_search" style="border:1px solid #000">
      			        {{-- <div class="input-group-append">
      			          <button class="btn btn-navbar" type="button" style="border: 0.5px solid #ccc">
      			            <i class="fas fa-search"></i>
      			          </button>
      			        </div> --}}
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
        					    <th scope="col">Email</th>
        					    <th scope="col">Địa chỉ</th>
        					    <th scope="col">Số điện thoại</th>
                      <th scope="col">Ghi chú</th>
        					    <th scope="col">Ngày tạo</th>
        					    <th scope="col">Thao tác</th>
        				    </tr>
        				  </thead>
        				  <tbody id="customer_table">
        				  	@foreach ($customers as $key => $customer)
        				        <tr>
                          <th scope="row"><input class="form-check-input" type="checkbox" value="{{ $customer->id }}" id="{{ $customer->id }}"></th>
        					      	<th scope="row">{{ $key+1 }}</th>
        					      	<td>{{ $customer->name }}</td>
        					      	<td>{{ $customer->email }}</td>
        					      	<td>{{ $customer->address }}</td>
                          <td>{{ $customer->phone }}</td>
                          <td>{{ $customer->note }}</td>
        					      	<td>{{ $customer->created_at }}</td>
        					      	<td>|
                          	<a href="javascript:void(0)" onclick="ajaxDeleteCustomer({{ $customer->id }})"><i class="fas fa-trash"></i></a> | 
        	                </td>
        				    	</tr>
        				    @endforeach
        				  </tbody>
				        </table>
            </div>
				<div class="card-footer">
        	<div class="row">
            <div class="col-md-4" id="count_customer">Show {{ $customers->count() }} of {{ $customers->total() }} result</div>
            <div class="col-md-8">  	
            	<ul class="pagination float-right">
            		{{-- Nguyên tắc : (currentPage - 1) * LIMIT --}}
            		<li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page=1">First</a></li>
            		{{-- nếu page hiện tại > page đầu thì hiện nút privew --}}
            		@if($customers->currentPage() > 1)
			    	        <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $customers->currentPage() - 1 }}">Previous</a></li>
			          @endif
			              <li class="page-item active"><a class="page-link" href="admin/ql-tai-khoan?page={{ $customers->currentPage() }}">{{ $customers->currentPage() }}</a></li>
			          @if($customers->currentPage() < $customers->lastPage())
				            <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $customers->currentPage() + 1 }}">{{ $customers->currentPage() + 1 }}</a></li>
                    @if($customers->currentPage() < $customers->lastPage() - 2)
				            <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $customers->currentPage() + 2 }}">{{ $customers->currentPage() + 2 }}</a></li>
                    @endif
                    @if($customers->currentPage() < $customers->lastPage() - 3)
				            <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $customers->currentPage() + 3 }}">{{ $customers->currentPage() + 3 }}</a></li>
                    @endif
				        @endif
  							{{-- nếu page hiện tại < page cuối thì hiện nút next --}}
  							@if($customers->currentPage() < $customers->lastPage())
  								  <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $customers->currentPage() + 1 }}">Next</a>
  							@endif
			          <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $customers->lastPage() }}">Last</a></li>
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
	$('#customer_search').on('keyup',function(){
        load_ajax();
    })
    $('#customer_status').on('change',function(){
        load_ajax();
    })
    $('#customer_level').on('change',function(){
        load_ajax();
    })
    function load_ajax(){
      $.ajax({
          url : '{{ route('g_search_customer') }}',
          type : 'get',
          data : {
                 'key' : $('#customer_search').val(),
                },
          success : function (data){
              //console.log(data);
              $('tbody').html(data);
              $('#count_customer').html('Tìm thấy <b>' + $('#customer_table').find('tr').length + '</b> Kết quả');
          }
      });
    }
});
</script>
<script>
  // ajax xóa customer
  function ajaxDeleteCustomer(id) {
    //alert(id);
    $.confirm({
      title: 'Xác Nhận!',
      content: 'Bạn có chắc chắn muốn xóa (những) tài khoản này ?',
      type: 'red',
      typeAnimated: true,
      buttons: {
          tryAgain: {
              text: 'Ok',
              btnClass: 'btn-red',
              action: function(){
                $.ajax({
                    url : '{{ route('p_delete_customer') }}',
                    type : 'get',
                    data : {
                          'id': id,
                          },
                    success : function (data){
                      console.log(data);
                      if(data == "success") {
                        toastr.success('Xóa tài khoản thành công!');
                        setTimeout(function(){location.reload(); }, 1000);
                      }
                      else {
                        toastr.error('Xóa không thành công, vui lòng chọn một tài khoản!');
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
$('#customer_delete').click(function(){
  let listId = '';
  $.each($("input[type=checkbox]:checked"), function(){
    listId += $(this).val() + ',';
  });
  listId = listId.substring(0, listId.length - 1);
  ajaxDeleteCustomer(listId);
})
</script>
@stop