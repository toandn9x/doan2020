@extends('admin.layout.app')
@section('title', 'Thêm slide')
@section('content_admin')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Thêm slide</h1>
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
              @if (session('err'))
                <div class="alert alert-danger">
                    {{ session('err') }}
                </div>
              @endif
              {{-- in thông báo lưu thành công ! --}}
              @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
              @endif
              <form role="form" method="post" action="{{ route('p_add_slide') }}" enctype="multipart/form-data">
                @csrf
                <table class="table">
                    <tr>
                      <th scope="row">Liên kết khi click</th>
                      <td>
                        <div class="form-group">
                          <input type="text" class="form-control" id="" aria-describedby="emailHelp" placeholder="Enter link" name="link">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Trạng thái</th>
                      <td>
                        <div class="form-group">
                          <select class="form-control" id="" name="slide_status">
                            <option value="{{ Config::get('constants.STATUS_ACTIVE') }}">✔ Hiển thị</option>
                            <option value="{{ Config::get('constants.STATUS_UNACTIVE') }}">✘ Tạm ẩn</option>
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
                                <input type="file" class="custom-file-input" id="exampleInputFile" name = "slide_img">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                              </div>
                              <div class="input-group-append">
                                <span class="input-group-text" id="">Upload</span>
                              </div>
                            </div>
                        </div>
                      </td>
                    <tr>
                      <th scope="row"><button type="submit" class="btn btn-primary">Submit</button>&emsp;
                      <a href="{{ route('g_slider') }}" class="btn btn-warning">Quay Lại</a>
                      </th>
                      <td>
                      </td>
                    </tr>
                </table>
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