@extends('admin.layout.app')
@section('title', 'QL liên hệ')
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
            <h1>QL thông tin liên hệ</h1>
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
              {{-- in thông báo lưu thành công ! --}}
              @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
              @endif
              <form role="form" method="post" action="{{ route('p_contact') }}" enctype="multipart/form-data">
                @csrf
                <table class="table">
                  <tr>
                      <th scope="row">Giới thiệu về cửa hàng</th>
                      <td>
                        <div class="form-group">
                          <textarea class="textarea" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid rgb(221, 221, 221); padding: 10px; display: none;" name="introduce">{{ $post[0] }}</textarea>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Địa Chỉ</th>
                      <td>
                        <div class="form-group">
                          <input type="text" class="form-control" id="" placeholder="Enter address" name="address" value="{{ $post[1] }}">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Email</th>
                      <td>
                        <div class="form-group">
                          <input type="email" class="form-control" id="" placeholder="Enter address" name="email" value="{{ $post[3] }}">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Số điện thoại</th>
                      <td>
                        <div class="form-group">
                          <input type="text" class="form-control" id="" placeholder="Enter phone number" name="phone" value="{{ $post[2] }}">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Logo</th>
                      <td>
                        <div class="form-group">
                            <div class="input-group">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="exampleInputFile" name = "logo_img">
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                              </div>
                              <div class="input-group-append">
                                <span class="input-group-text" id="">Upload</span>
                              </div>
                            </div>
                        </div>
                        <hr>
                        <p><b>Logo hiện tại: </b></p>
                        <img src="images/{{ $img }}" width="200px" height="150px">
                      </td>
                    </tr>
                    <tr>
                      <th scope="row"><button type="submit" class="btn btn-primary">Submit</button>&emsp;
                      <a href="{{ route('admin_index') }}" class="btn btn-warning">Quay Lại</a>
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