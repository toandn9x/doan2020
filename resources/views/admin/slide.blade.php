@extends('admin.layout.app')
@section('title', 'QL Slide')
@section('content_admin')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>QL Slide</h1>
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
                <a href="{{ route('g_add_slide') }}" class="btn btn-info">Thêm</a> | 
                <a href="javascript:void(0)" class="btn btn-danger" style="" id="slide_delete">Xóa</a>
              </div>
              <div class="card-body">
              	<table class="table table-hover">
        				  <thead>
        				    <tr>
                      <th scope="col"></th>
        					    <th scope="col">STT</th>
        					    <th scope="col">Ảnh</th>
        					    <th scope="col">Liên kết</th>
        					    <th scope="col">Trạng thái</th>
        					    <th scope="col">Ngày tạo</th>
                      <th scope="col">Sửa</th>
        				    </tr>
        				  </thead>
        				  <tbody id="slide_table">
        				  	@foreach ($slides as $key => $slide)
        				        <tr>
                          <th scope="row"><input class="form-check-input" type="checkbox" value="{{ $slide->id }}" id="{{ $slide->id }}"></th>
        					      	<th scope="row">{{ $key+1 }}</th>
        					      	<td class="example"><img src="images/{{ $slide->image }}" width="200px" height="100px" style="cursor: pointer;"></td>
        					      	<td>{{ $slide->link }}</td>
                          @if ($slide->status == Config::get('constants.STATUS_ACTIVE'))
                          <td>✔ Đang hiển thị</td>
                          @else <td><span style="color:red">✘ Đang ẩn</span></td>
                          @endif
        					      	<td>{{ $slide->created_at }}</td>
                          <td><a href="{{ route('g_edit_slide', $slide->id) }}">Sửa</a></td>
        				    	</tr>
        				    @endforeach
        				  </tbody>
				        </table>
            </div>
				<div class="card-footer">
        	<div class="row">
            <div class="col-md-4" id="count_slide">Show {{ $slides->count() }} of {{ $slides->total() }} result</div>
            <div class="col-md-8">  	
            	<ul class="pagination float-right">
            		{{-- Nguyên tắc : (currentPage - 1) * LIMIT --}}
            		<li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page=1">First</a></li>
            		{{-- nếu page hiện tại > page đầu thì hiện nút privew --}}
            		@if($slides->currentPage() > 1)
			    	        <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $slides->currentPage() - 1 }}">Previous</a></li>
			          @endif
			              <li class="page-item active"><a class="page-link" href="admin/ql-tai-khoan?page={{ $slides->currentPage() }}">{{ $slides->currentPage() }}</a></li>
			          @if($slides->currentPage() < $slides->lastPage())
				            <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $slides->currentPage() + 1 }}">{{ $slides->currentPage() + 1 }}</a></li>
                    @if($slides->currentPage() < $slides->lastPage() - 2)
				            <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $slides->currentPage() + 2 }}">{{ $slides->currentPage() + 2 }}</a></li>
                    @endif
                    @if($slides->currentPage() < $slides->lastPage() - 3)
				            <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $slides->currentPage() + 3 }}">{{ $slides->currentPage() + 3 }}</a></li>
                    @endif
				        @endif
  							{{-- nếu page hiện tại < page cuối thì hiện nút next --}}
  							@if($slides->currentPage() < $slides->lastPage())
  								  <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $slides->currentPage() + 1 }}">Next</a>
  							@endif
			          <li class="page-item"><a class="page-link" href="admin/ql-tai-khoan?page={{ $slides->lastPage() }}">Last</a></li>
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
  function ajaxDeleteSlide(id) {
    //alert(id);
    $.confirm({
      title: 'Xác Nhận!',
      content: 'Bạn có chắc chắn muốn xóa (những) ảnh này ?',
      type: 'red',
      typeAnimated: true,
      buttons: {
          tryAgain: {
              text: 'Ok',
              btnClass: 'btn-red',
              action: function(){
                $.ajax({
                    url : '{{ route('p_delete_slide') }}',
                    type : 'post',
                    data : {
                          'id': id,
                          },
                    success : function (data){
                      console.log(data);
                      if(data == "success") {
                        toastr.success('Xóa slide thành công!');
                        setTimeout(function(){location.reload(); }, 1000);
                      }
                      else {
                        toastr.error('Xóa không thành công, vui lòng chọn một slide!');
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
$('#slide_delete').click(function(){
  let listId = '';
  $.each($("input[type=checkbox]:checked"), function(){
    listId += $(this).val() + ',';
  });
  listId = listId.substring(0, listId.length - 1);
  ajaxDeleteSlide(listId);
})
</script>
<script>
  $('.example').mtfpicviewer({
    selector: 'img',
    attrSelector: 'src',
    
  });
</script>
@stop