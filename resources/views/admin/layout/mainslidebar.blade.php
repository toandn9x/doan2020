<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="https://www.facebook.com/toandnt/" class="brand-link">
      <img src="admin_lib/dist/img/Logo-Thuy_Loi.png" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">58TH2 - ĐNT</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="admin_lib/dist/img/author.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ route('g_user') }}" class="d-block">hello: <b>{{ Auth::user()->name }}</b></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="{{ route('admin_index') }}" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="fas fa-users-cog"></i>
              <p>
                Admin
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('g_user') }}" class="nav-link">
                  <i class="fas fa-user"></i>
                  <p>QL tài khoản</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('g_customer') }}" class="nav-link">
                  <i class="fas fa-list"></i>
                  <p>DS khách hàng</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('g_discount') }}" class="nav-link">
                  <i class="fas fa-clipboard-list"></i>
                  <p>DSDK nhận KM</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('g_contact') }} " class="nav-link">
                  <i class="fas fa-cogs"></i>
                  <p>Web setting</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="pages/tables/jsgrid.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Quản lý comment</p>
                </a>
              </li> --}}
              
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('g_category') }}" class="nav-link">
              <i class="fas fa-list"></i>
              <p>
                Danh mục sản phẩm
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('g_product') }}" class="nav-link">
              <i class="fas fa-pizza-slice"></i>
              <p>
                Tất cả sản phẩm
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('g_slider') }}" class="nav-link">
              <i class="fas fa-stream"></i>
              <p>
                Silde quảng bá
                {{-- <span class="right badge badge-danger">New</span> --}}
              </p>
            </a>
          </li>
         {{--  <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Bài viết giới thiệu
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                tin tức
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li> --}}
          <li class="nav-item">
            <a href="{{ route('g_bill') }}" class="nav-link">
              <i class="fas fa-file-medical-alt"></i>
              <p>
                Quản lý đơn hàng
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('g_statistical') }}" class="nav-link">
              <i class="fas fa-hand-holding-usd"></i>
              <p>
                Thống kê
                {{-- <span class="right badge badge-danger">New</span> --}}
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>