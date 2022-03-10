@extends('admin.layout.app')
@section('title', 'Sửa danh mục')
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
            <h1>Sửa danh mục</h1>
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
              <form role="form" method="post" action="{{ route('p_edit_category') }}" enctype="multipart/form-data">
                @csrf
                <table class="table">
                    <tr>
                      <th scope="row">Tên Danh Mục(<span style="color: red">*</span>)</th>
                      <td>
                        <div class="form-group">
                          <input type="text" class="form-control" id="category_name" placeholder="Enter category name" required name="category_name" minlength="6" value="{{ $category->name }}">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Mô tả</th>
                      <td>
                        <div class="form-group">
                          <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px; display: none;" name="category_description">{{ $category->description }}</textarea>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Trạng thái</th>
                      <td>
                        <div class="form-group">
                          <select class="form-control" id="" name="category_status">
                            <option value="{{ Config::get('constants.STATUS_ACTIVE') }}"
                            @if($category->status == Config::get('constants.STATUS_ACTIVE'))
                              selected
                            @endif
                            >✔ Hiển thị</option>
                            <option value="{{ Config::get('constants.STATUS_UNACTIVE') }}"
                            @if($category->status == Config::get('constants.STATUS_UNACTIVE'))
                            selected
                            @endif
                            >✘ Tạm ẩn</option>
                        </select>
                      </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Thứ tự hiển thị</th>
                      <td>
                        <div class="form-group">
                          <input type="number" class="form-control" name="category_order" value="{{ $category->order }}">
                      </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Ảnh</th>
                      <td>
                        <div class="form-group">
                            <div class="input-group">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="exampleInputFile" name = "category_img">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                              </div>
                              <div class="input-group-append">
                                <span class="input-group-text" id="">Upload</span>
                              </div>
                            </div>
                        </div>
                        <hr>
                        <p><b>Ảnh cũ: </b></p>
                        <img src="images/{{ $category->image }}" width="200px" height="150px">
                      </td>
                    </tr>
                    <tr>
                      <th scope="row"><button type="submit" class="btn btn-primary">Submit</button>&emsp;
                      <a href="{{ route('g_category') }}" class="btn btn-warning">Quay Lại</a>
                      </th>
                      <td>
                      </td>
                    </tr>
                </table>
                <input type="hidden" name="id" value="{{ $category->id }}">
                <input type="hidden" name="current_name" value="{{ $category->name }}">
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