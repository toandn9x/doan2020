@extends('admin.layout.app')
@section('title', 'QL Danh mục')
@section('content_admin')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>QL Danh mục</h1>
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
                <a href="{{ route('g_add_category') }}" class="btn btn-info">Thêm</a> | 
                <a href="{{ route('g_export_category') }}" class="btn btn-warning">Xuất excel</a> |
                <a href="javascript:void(0)" class="btn btn-danger" style="" id="category_delete">Xóa</a>
                <!-- SEARCH FORM -->
      			    <form class="form-inline ml-3 float-right">
      			      <div class="input-group input-group-sm">
      			        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" id="category_search">
      			        {{-- <div class="input-group-append">
      			          <button class="btn btn-navbar" type="button" style="border: 0.5px solid #ccc">
      			            <i class="fas fa-search"></i>
      			          </button>
      			        </div> --}}
      			      </div>
      			      	<div class="input-group input-group-sm">
                      <select class="form-control" id="category_status">
                        <option value="">--Trạng thái--</option>
                        <option value="{{ Config::get('constants.STATUS_ACTIVE') }}" style="font-weight: bold!important">Đang hiển thị</option>
                        <option value="{{ Config::get('constants.STATUS_UNACTIVE') }}" style="font-weight: bold!important">Đang ẩn</option>
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
        					    <th scope="col">Trạng thái</th>
                      <th scope="col">Thứ tự hiển thị</th>
        					    <th scope="col">Ngày tạo</th>
        					    <th scope="col">Thao tác</th>
        				    </tr>
        				  </thead>
        				  <tbody id="category_table">
        				  	@foreach ($categories as $key => $category)
        				        <tr>
                          <th scope="row"><input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="{{ $category->id }}"></th>
        					      	<th scope="row">{{ $key+1 }}</th>
        					      	<td>{{ $category->name }}</td>
        					      	<td><img src="images/{{ $category->image }}" width="200px" height="150px"></td>
                          <td>{!! $category->description !!}</td>
        					      	@if ($category->status == Config::get('constants.STATUS_ACTIVE'))
        					      	<td>✔ Đang hiển thị</td>
        					      	@else
        					      	<td><span style="color:red">✘ Đang ẩn</span></td>
        					      	@endif
                          <td>{{ $category->order }}</td>
        					      	<td>{{ $category->created_at }}</td>
        					      	<td>
                          	<a href="admin/ql-danh-muc/sua/{{ $category->id }}"><i class="fas fa-edit"></i></a>--<a href="javascript:void(0)" onclick="ajaxDeleteCategory({{ $category->id }})"><i class="fas fa-trash"></i></a>
        	                </td>
        				    	</tr>
        				    @endforeach
        				  </tbody>
				      </table>
            </div>
				<div class="card-footer">
        	<div class="row">
            <div class="col-md-4" id="count_category">Show {{ $categories->count() }} of {{ $categories->total() }} result</div>
            <div class="col-md-8">  	
            	<ul class="pagination float-right">
            		{{-- Nguyên tắc : (currentPage - 1) * LIMIT --}}
            		<li class="page-item"><a class="page-link" href="admin/ql-danh-muc?page=1">First</a></li>
            		{{-- nếu page hiện tại > page đầu thì hiện nút privew --}}
            		@if($categories->currentPage() > 1)
			    	        <li class="page-item"><a class="page-link" href="admin/ql-danh-muc?page={{ $categories->currentPage() - 1 }}">Previous</a></li>
			          @endif
			              <li class="page-item active"><a class="page-link" href="admin/ql-danh-muc?page={{ $categories->currentPage() }}">{{ $categories->currentPage() }}</a></li>
			          @if($categories->currentPage() < $categories->lastPage())
				            <li class="page-item"><a class="page-link" href="admin/ql-danh-muc?page={{ $categories->currentPage() + 1 }}">{{ $categories->currentPage() + 1 }}</a></li>
                    @if($categories->currentPage() < $categories->lastPage() - 2)
				            <li class="page-item"><a class="page-link" href="admin/ql-danh-muc?page={{ $categories->currentPage() + 2 }}">{{ $categories->currentPage() + 2 }}</a></li>
                    @endif
                    @if($categories->currentPage() < $categories->lastPage() - 3)
				            <li class="page-item"><a class="page-link" href="admin/ql-danh-muc?page={{ $categories->currentPage() + 3 }}">{{ $categories->currentPage() + 3 }}</a></li>
                    @endif
				        @endif
  							{{-- nếu page hiện tại < page cuối thì hiện nút next --}}
  							@if($categories->currentPage() < $categories->lastPage())
  								  <li class="page-item"><a class="page-link" href="admin/ql-danh-muc?page={{ $categories->currentPage() + 1 }}">Next</a>
  							@endif
			          <li class="page-item"><a class="page-link" href="admin/ql-danh-muc?page={{ $categories->lastPage() }}">Last</a></li>
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
	  $('#category_search').on('keyup',function(){
        load_ajax();
    })
    $('#category_status').on('change',function(){
        load_ajax();
    })
    function load_ajax(){
      $.ajax({
          url : '{{ route('g_search_category') }}',
          type : 'get',
          data : {
                'key' : $('#category_search').val(),
                'status' : $('#category_status').val(),
                },
          success : function (data){
              console.log(data);
              $('tbody').html(data);
              $('#count_category').html('Tìm thấy <b>' + $('#category_table').find('tr').length + '</b> Kết quả');
          }
      });
    }
});
</script>
<script>
  // ajax xóa user
  function ajaxDeleteCategory(id) {
    //alert(id);
    $.confirm({
      title: 'Xác Nhận!',
      content: 'Bạn có chắc chắn muốn xóa (những) danh mục này ?',
      type: 'red',
      typeAnimated: true,
      buttons: {
          tryAgain: {
              text: 'Ok',
              btnClass: 'btn-red',
              action: function(){
                $.ajax({
                    url : '{{ route('p_delete_category') }}',
                    type : 'post',
                    data : {
                          'id': id,
                          },
                    success : function (data){
                      console.log(data);
                      if(data == "success") {
                        toastr.success('Xóa danh mục thành công!');
                        setTimeout(function(){location.reload(); }, 1000);
                      }
                      else {
                        toastr.error('Xóa không thành công, vui lòng chọn một danh mục!');
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
$('#category_delete').click(function(){
  let listId = '';
  $.each($("input[type=checkbox]:checked"), function(){
    listId += $(this).val() + ',';
  });
  listId = listId.substring(0, listId.length - 1);
  ajaxDeleteCategory(listId);
})
</script>
@stop