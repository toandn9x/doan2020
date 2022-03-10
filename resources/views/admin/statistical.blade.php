@extends('admin.layout.app')
@section('title', 'Thống kê')
@section('content_admin')
<div class="content-wrapper">
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Thống kê</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <form action="{{ route('g_statistical') }}" method="get">
          <div class="col-md-12">
            <input type="month" value="{{ date('Y-m') }}" id="date" name="month">
            <input type="submit" value="submit" class="">
          </div>
          </form>
        </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-8">
            <h3>Thống kê tháng: <span id="" style="color:red">{{ $month }}</span></h3>
            <canvas id="myChart" width="100%"></canvas>
          </div>
          <div class="col-md-4">
            <br><br><br>
            <table>
              <tr>
                <td><h4>* Người dùng mới: </h4></td>
                <td><h4> {{ $user }}</h4></td>
              </tr>
               <tr>
                <td><h4>* Số khách hàng mới: </h4></td>
                <td><h4> {{ $customer }}</h4></td>
              </tr>
              <tr>
                <td><h4>* Sản phẩm mới: </h4></td>
                <td><h4> {{ $product }} / {{ $total_prod }}</h4></td>
              </tr>
              <tr>
                <td><h4>* Tổng số đơn: </h4></td>
                <td><h4> {{ $bill }} / {{ $total_bill }}</h4></td>
              </tr>
              <tr>
                <td><h4>* Số đơn hàng đã giao:  </h4></td>
                <td><h4> {{ $bill_delivered }}</h4></td>
              </tr>
               <tr>
                <td><h4>* Số đơn hàng bị chối:  </h4></td>
                <td><h4> {{ $bill_reject }}</h4></td>
              </tr>
              <tr>
                <td><h4>* Tổng doanh thu: </h4></td>
                <td><h4> {{ number_format($total) }} đ</h4></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </section>
</div>
@stop
@section('script')
<script>
  // $('#date').on('change', function() {
  //   alert($(this).val());
  // })
// chartJs
var ctx = document.getElementById('myChart').getContext('2d');
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        <?php
        for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN,$month,date('Y')); $i ++) {
          echo $i.',';
        }
        ?>
        ],
        datasets: [{
            label: 'doanh thu theo ngày',
            data: [
            <?php
            foreach($data_sale as $key => $value) {
              echo $value.',';
            }
            ?>
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            pointBackgroundColor: 'rgba(242, 38, 19, 1)',
            pointBorderColor : 'rgba(242, 38, 19, 1)',
        }],
        // option

    },
});
</script>
@stop