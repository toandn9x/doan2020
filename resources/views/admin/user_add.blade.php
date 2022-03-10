@extends('admin.layout.app')
@section('title', 'Thêm tài khoản')
@section('content_admin')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Thêm tài khoản</h1>
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
              <form role="form" method="post" action="{{ route('p_add_user') }}">
                @csrf
                <table class="table">
                    <tr>
                      <th scope="row">Địa chỉ email(<span style="color: red">*</span>)</th>
                      <td>
                        <div class="form-group">
                          <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" required name="email">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Mật khẩu(<span style="color: red">*</span>)</th>
                      <td>
                        <div class="form-group">
                          <input type="password" class="form-control" id="pass" placeholder="Enter password" name="password" minlength="6">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Nhập lại mật khẩu(<span style="color: red">*</span>)</th>
                      <td>
                        <div class="form-group">
                          <input type="password" class="form-control" id="repass" placeholder="Confirm password" name="repass" minlength="6">
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Tên đăng nhập(<span style="color: red">*</span>)</th>
                      <td>
                        <div class="form-group">
                          <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required >
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">Loại tài khoản</th>
                      <td>
                        <div class="form-group">
                          <select class="form-control" id="" name="level">
                            <option value="{{ Config::get('constants.USER_LEVEL') }}">Người dùng</option>
                            <option value="{{ Config::get('constants.ADMIN_LEVEL') }}">Tài khoản admin</option>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <th scope="row"><button type="submit" class="btn btn-primary">Submit</button>&emsp;
                      <a href="{{ route('g_user') }}" class="btn btn-warning">Quay Lại</a>
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