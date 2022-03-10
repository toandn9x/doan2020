@extends('admin.layout.app')
@section('title', 'QL Email')
@section('content_admin')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>QL Email</h1>
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
                <a href="javascript:void(0)" class="btn btn-danger" style="" id="discount_delete">Xóa</a>
                <a href="javascript:void(0)" class="btn btn-success" style="" id="discount_email">Gửi email</a>
                <!-- SEARCH FORM -->
      			    <form class="form-inline ml-3 float-right">
      			      <div class="input-group input-group-sm">
      			        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" id="discount_search">
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
        					    <th scope="col">Email</th>
        					    <th scope="col">Ngày tạo</th>
        				    </tr>
        				  </thead>
        				  <tbody id="discount_table">
        				  	@foreach ($discounts as $key => $discount)
                    <tr>
                          <th scope="row"><input class="form-check-input" type="checkbox" value="{{ $discount->id }}" id="{{ $discount->id }}"></th>
                          <th scope="row">{{ $key+1 }}</th>
                          <td>{{ $discount->description }}</td>
                          <td>{{ $discount->created_at }}</td>
                          </td>
                      </tr>
                    @endforeach
        				  </tbody>
				        </table>
            </div>
				<div class="card-footer">
        	<div class="row">
            <div class="col-md-4" id="count_discount">Show {{ $discounts->count() }} of {{ $discounts->total() }} result</div>
            <div class="col-md-8">  	
            	<ul class="pagination float-right">
            		{{-- Nguyên tắc : (currentPage - 1) * LIMIT --}}
            		<li class="page-item"><a class="page-link" href="admin/ds-khuyen-mai?page=1">First</a></li>
            		{{-- nếu page hiện tại > page đầu thì hiện nút privew --}}
            		@if($discounts->currentPage() > 1)
			    	        <li class="page-item"><a class="page-link" href="admin/ds-khuyen-mai?page={{ $discounts->currentPage() - 1 }}">Previous</a></li>
			          @endif
			              <li class="page-item active"><a class="page-link" href="admin/ds-khuyen-mai?page={{ $discounts->currentPage() }}">{{ $discounts->currentPage() }}</a></li>
			          @if($discounts->currentPage() < $discounts->lastPage())
				            <li class="page-item"><a class="page-link" href="admin/ds-khuyen-mai?page={{ $discounts->currentPage() + 1 }}">{{ $discounts->currentPage() + 1 }}</a></li>
                    @if($discounts->currentPage() < $discounts->lastPage() - 2)
				            <li class="page-item"><a class="page-link" href="admin/ds-khuyen-mai?page={{ $discounts->currentPage() + 2 }}">{{ $discounts->currentPage() + 2 }}</a></li>
                    @endif
                    @if($discounts->currentPage() < $discounts->lastPage() - 3)
				            <li class="page-item"><a class="page-link" href="admin/ds-khuyen-mai?page={{ $discounts->currentPage() + 3 }}">{{ $discounts->currentPage() + 3 }}</a></li>
                    @endif
				        @endif
  							{{-- nếu page hiện tại < page cuối thì hiện nút next --}}
  							@if($discounts->currentPage() < $discounts->lastPage())
  								  <li class="page-item"><a class="page-link" href="admin/ds-khuyen-mai?page={{ $discounts->currentPage() + 1 }}">Next</a>
  							@endif
			          <li class="page-item"><a class="page-link" href="admin/ds-khuyen-mai?page={{ $discounts->lastPage() }}">Last</a></li>
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
  $('#discount_search').on('keyup',function(){
        load_ajax();
    })
    function load_ajax(){
      $.ajax({
          url : '{{ route('g_search_discount') }}',
          type : 'get',
          data : {

                 'key' : $('#discount_search').val(),
                },
          success : function (data){
              //console.log(data);
              $('tbody').html(data);
              $('#count_discount').html('Tìm thấy <b>' + $('#discount_table').find('tr').length + '</b> Kết quả');
          }
      });
    }
});

</script>
<script>
  // ajax xóa discount
  function ajaxDeleteDiscount(id) {
    //alert(id);
    $.confirm({
      title: 'Xác Nhận!',
      content: 'Bạn có chắc chắn muốn xóa (những) email này ?',
      type: 'red',
      typeAnimated: true,
      buttons: {
          tryAgain: {
              text: 'Ok',
              btnClass: 'btn-red',
              action: function(){
                $.ajax({
                    url : '{{ route('p_delete_discount') }}',
                    type : 'post',
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
$('#discount_delete').click(function(){
  let listId = '';
  $.each($("input[type=checkbox]:checked"), function(){
    listId += $(this).val() + ',';
  });
  listId = listId.substring(0, listId.length - 1);
  ajaxDeleteDiscount(listId);
})
</script>
@stop