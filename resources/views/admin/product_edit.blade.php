@extends('admin.layout.app')
@section('title', 'Sửa sản phẩm')
@section('content_admin')
<style>
  th {
    width: 250px;
  }
</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sửa sản phẩm</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-9">
            <div class="card card-primary">
              {{-- check lỗi validate --}}
              @if ($errors->any())
              @foreach ($errors->all() as $error)
                  <div class="alert alert-danger">{{ $error }}</div>
              @endforeach
              @endif
              {{-- check lỗi không lưu được bản ghi trong DB --}}
              @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
              @endif
              {{-- in thông báo lưu thành công ! --}}
              @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
              @endif
              <form role="form" method="post" action="{{ route('p_edit_product') }}" enctype="multipart/form-data">
                @csrf
                <table class="table">
                    <tr>
                      <th scope="row">Tên Sản Phẩm(<span style="color: red">*</span>)</th>
                      <td>
                        <div class="form-group">
                          <input type="text" class="form-control" id="product_name" placeholder="Enter product name" required name="product_name" minlength="6" value="{{ $products->name }}">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Mô tả</th>
                      <td>
                        <div class="form-group">
                          <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px; display: none;" name="product_description">{{ $products->description }}</textarea>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Danh mục(<span style="color: red">*</span>)</th>
                      <td>
                        <div class="form-group">
                          <select class="form-control" id="" name="product_category" required>
                            <option value="">--Chọn danh mục--</option>
                            @foreach($categories as $category)
                              <option value="{{ $category->id }}"
                                @if($category->id == $products->id_category)
                                selected
                                @endif 
                                >{{ $category->name }}</option>
                            @endforeach
                        </select>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Giá gốc(<span style="color: red">*</span>)</th>
                      <td>
                        <div class="form-group">
                          <input type="number" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 231 && event.keyCode !== 109" class="form-control" id="product_unit_price" placeholder="Enter unit price" required name="product_unit_price" value="{{ $products->unit_price }}">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Giá khuyến mãi</th>
                      <td>
                        <div class="form-group">
                          <input type="number" onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 231 && event.keyCode !== 109" class="form-control" id="product_promotion_price" placeholder="Enter promotion price" name="product_promotion_price" value="{{ $products->promotional_price }}">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Sản phẩm hot</th>
                      <td>
                        <div class="form-group">
                          <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" name="is_hot" value="{{ Config::get('constants.HOT_PRODUCT') }}"
                            @if($products->is_hot == Config::get('constants.HOT_PRODUCT'))
                            checked
                            @endif
                            >
                            <label class="custom-control-label" for="customSwitch1">Hot</label>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Trạng thái</th>
                      <td>
                        <div class="form-group">
                          <select class="form-control" id="" name="product_status">
                            <option value="{{ Config::get('constants.STATUS_ACTIVE') }}"
                            @if($products->status == Config::get('constants.STATUS_ACTIVE'))
                            selected
                            @endif
                            >✔ Hiển thị</option>
                            <option value="{{ Config::get('constants.STATUS_UNACTIVE') }}"
                            @if($products->status == Config::get('constants.STATUS_UNACTIVE'))
                            selected
                            @endif
                            >✘ Tạm ẩn</option>
                            <option value="{{ Config::get('constants.STATUS_SELL_OUT') }}"
                            @if($products->status == Config::get('constants.STATUS_SELL_OUT'))
                            selected
                            @endif
                            >〤 Hết hàng</option>
                        </select>
                      </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Ảnh</th>
                      <td>
                        <div class="form-group">
                            <div class="input-group">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="exampleInputFile" name = "product_img">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                              </div>
                              <div class="input-group-append">
                                <span class="input-group-text" id="">Upload</span>
                              </div>
                            </div>
                        </div>
                        <hr>
                        <p><b>Ảnh cũ: </b></p>
                        <img src="images/{{ $products->image }}" width="200px" height="150px">
                      </td>
                    </tr>
                    <tr>
                      <th scope="row"><button type="submit" class="btn btn-primary">Submit</button>&emsp;
                      <a href="{{ route('g_product') }}" class="btn btn-warning">Quay Lại</a>
                      </th>
                      <td>
                      </td>
                    </tr>
                </table>
                <input type="hidden" name="id" value="{{ $products->id }}">
              </form>
            </div>
          </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    </section>
  </div>
    <!-- /.content -->
    @stop
@section('script')
<script>
  // check giá khuyến mãi phải nhỏ hơn giá gốc !
$('#product_promotion_price').change(function() {
    $(this).attr("max", $('#product_unit_price').val());
});
</script>
@stop