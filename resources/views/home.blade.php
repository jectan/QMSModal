@extends('layouts.app',[
    'page' => 'Dashboard',
    'title' => 'Dashboard'
])

@section('content')
<!-- Preloader -->
<div class="row">
  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box small-box bg-info">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #17a2b8">
      <i class="fas fa-ticket-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Tickets</span>
        <span class="info-box-number total"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box small-box bg-danger">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #dc3545">
      <i class="fas fa-ticket-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">New Tickets</span>
        <span class="info-box-number created"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box small-box bg-primary">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #007bff">
      <i class="fas fa-ticket-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Assigned</span>
        <span class="info-box-number assigned"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box small-box bg-warning">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #ffc107">
      <i class="fas fa-ticket-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Working</span>
        <span class="info-box-number working"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box small-box bg-secondary">
      <span class="info-box-icon elevation-1" style="background-color: white; color: gray">
      <i class="fas fa-ticket-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">For Closing</span>
        <span class="info-box-number for-closing"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-2">
    <div class="info-box small-box bg-success">
      <span class="info-box-icon elevation-1" style="background-color: white; color: green">
      <i class="fas fa-ticket-alt"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Closed</span>
        <span class="info-box-number closed"></span>
      </div>
    </div>
  </div>
</div>

<div class="row">
   <!-- PROGRESS -->
   <div class="col-md-3">
        <div class="info-box mb-3" style="background-color: white; color: #007bff">
            <span class="info-box-icon"><i class="fas fa-ticket-alt"></i></span>
            <div class="info-box-content"  style="text-align: center">
              <span class="info-box-text" style="color:black">Tickets <br> w/ Ratings</span>
              <span class="info-box-number">{{ $total_Ratings }}</span>
            </div>
        </div>
        <!--5 Star-->
        <div class="card" style="margin-top: -5%">
          <div class="card-body">
            <div class="progress-group">
              <span class="progress-text">5 star Ratings</span>
              <span class="progress-number float-right"><b>{{ $five_star }}</b>({{ $five_star > 0 ? round((($five_star/$total_Ratings) * 100),2) : 0 }}%)</span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-aqua" style="width: {{  $total_Ratings!=0 ?  ($five_star/$total_Ratings)*100 : ' '}}%; height: 10px"></div>
              </div>
            </div>
          <!--4 Star-->
            <div class="progress-group">
              <span class="progress-text">4 star Ratings</span>
              <span class="progress-number float-right"><b>{{ $four_star }}</b>({{ $four_star > 0 ? round((($four_star/$total_Ratings) * 100),2) : 0 }}%)</span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-aqua" style="width: {{  $total_Ratings!=0 ? ($four_star/$total_Ratings)*100 : ' '}}%; height: 10px"></div>
              </div>
            </div>
          <!--3 Star-->
          <div class="progress-group">
            <span class="progress-text">3 star Ratings</span>
            <span class="progress-number float-right"><b>{{ $three_star }}</b>({{ $three_star > 0 ? round((($three_star/$total_Ratings) * 100),2) : 0 }}%)</span>

            <div class="progress sm">
              <div class="progress-bar progress-bar-aqua" style="width: {{  $total_Ratings!=0 ? ($three_star/$total_Ratings)*100 : ' '}}%; height: 10px"></div>
            </div>
          </div>
          <!--2 Star-->
            <div class="progress-group">
              <span class="progress-text">2 star Ratings</span>
              <span class="progress-number float-right"><b>{{ $two_star }}</b>({{ $two_star > 0 ? round((($two_star/$total_Ratings) * 100),2) : 0 }}%)</span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-aqua" style="width: {{ $total_Ratings!=0 ? ($two_star/$total_Ratings)*100 : ' '}}%; height: 10px"></div>
              </div>
            </div>
           <!--1 Star-->
            <div class="progress-group">
              <span class="progress-text">1 star Ratings</span>
              <span class="progress-number float-right"><b>{{ $one_star }}</b>({{ $one_star > 0 ? round((($one_star/$total_Ratings) * 100),2) : 0 }}%)</span>

              <div class="progress sm">
                <div class="progress-bar progress-bar-aqua" style="width: {{ $total_Ratings!=0 ? ($one_star/$total_Ratings)*100 : ' ' }}%; height: 10px"></div>
              </div>
            </div>

          </div>
        </div>
   </div>
<!-- CHARTS -->
  <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <div class="card-title"><h6>Tickets Resolved</h6></div>
        </div>
        <div class="card-body">
            <canvas id="chartbyResolve" style="width: 40px; height: 43px; color: #6cbcbc"></canvas><br/>
        </div>
      </div>
  </div>

  <div class="col-md-3">
    <div class="card">
      <div class="card-header">
        <div class="card-title"><h6>Tickets Assigned</h6></div>
      </div>
      <div class="card-body">
          <canvas id="chartbyAssigned" style="width: 40px; height: 43px; color: #6cbcbc"></canvas><br/>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card">
      <div class="card-header">
        <div class="card-title"><h6>Tickets Feedback</h6></div>
      </div>
      <div class="card-body">
          <canvas id="chartbyFeedback" style="width: 40px; height: 43px; color: #6cbcbc"></canvas><br/>
      </div>
    </div>
  </div>

</div>

  <div class="row">
    <!--Top Ten Offices Tables-->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h6 class="card-title">Top Offices with High Ratings</h6>
        </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm" id="topRatings" style="font-size: 14px">
                  <thead>
                          <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 35%">Office</th>
                            <th style="width: 20%">Total 5 Stars</th>
                            <th style="width: 20%">Total 4 Stars</th>
                            <th style="width: 20%">Total Processed</small></th>
                        </tr>
                    </thead>
                </table>
            </div>
      </div>
    </div>

    <!--Top Low  Offices Tables-->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h6 class="card-title">Top Offices with Low Ratings</h6>
        </div>
            <div class="card-body table-responsive">
              <table class="table table-striped table-sm" id="lowRatings" style="font-size: 14px">
                <thead>
                        <tr>
                          <th style="width: 5%">#</th>
                          <th style="width: 35%">Office</th>
                          <th style="width: 20%">Total 2 Stars</th>
                          <th style="width: 20%">Total 1 Stars</th>
                          <th style="width: 20%">Total Processed</small></th>
                      </tr>
                  </thead>
                </table>
        </div>
      </div>
    </div>
</div>
<div class="row">
   <!--Top 5 Offices Unresolve Tables-->
   <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h6 class="card-title">Top Offices with Unresolved</h6>
      </div>
          <div class="card-body table-responsive">
            <table class="table table-striped table-sm" id="topFiveUnresolved" style="font-size: 14px">
              <thead>
                      <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 35%">Office</th>
                        <th style="width: 20%">Total Unresolved</th>
                        <th style="width: 20%">Total Processed</small></th>
                    </tr>
                </thead>
              </table>
          </div>
     </div>
  </div>
  <!--Top 5 Offices Resolved within 1 day and 3 days Tables-->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h6 class="card-title">Top Offices with Resolved</h6>
      </div>
          <div class="card-body table-responsive">
            <table class="table table-striped table-sm" id="topFiveResolved" style="font-size: 14px">
              <thead>
                      <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 35%">Office</th>
                        <th style="width: 20%">within 1 Day</th>
                        <th style="width: 20%">within 3 Days</small></th>
                        <th style="width: 20%">Total Processed</small></th>
                    </tr>
                </thead>
              </table>
          </div>
     </div>
  </div>
</div>
<div class="row">
  <!--Top 5 Offices Resolved within 1 day and 3 days Tables-->
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h6 class="card-title">Top Offices with Resolved >3 Days</h6>
      </div>
          <div class="card-body table-responsive">
            <table class="table table-striped table-sm" id="topLowResolved" style="font-size: 14px">
              <thead>
                      <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 20%">Office</th>
                        <th style="width: 15%">within 5 Day</th>
                        <th style="width: 15%">within 7 Days</small></th>
                        <th style="width: 15%">within 30 Days</small></th>
                        <th style="width: 15%"> >30 Days</small></th>
                        <th style="width: 15%">Total Processed</small></th>
                    </tr>
                </thead>
              </table>
          </div>
     </div>
  </div>
</div>

  
<script>
  $(document).ready(function(){
    //  COUNT TOTAL
      $.ajax({
        type: 'GET',
        url: "{{ url('/dashboard/computeTotal')}}",
        dataType: 'json',
        success:function(res)
        {
          $(".total").html(res['totalTickets']);
          $(".created").html(res['created']);
          $(".assigned").html(res['assigned']);
          $(".working").html(res['working']);
          $(".for-closing").html(res['for-closing']);
          $(".closed").html(res['closed']);
        }
      });

      $('#topRatings').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ url('/dashboard/topTenHighOffices') }}" ,
              columns: [
                  { data: 'DT_RowIndex' },
                  { data: null,
                    render: function (data, type, row) {
                          var name = row.office;
                          return name;
                    }
                  },
                  { data: 'total_five_star' },
                  { data: 'total_four_star' },
                  { data: 'total_processed' },
                ],
              order: [[4, 'desc']],
              createdRow: (row, data, dataIndex, cells) => {
                  $(cells[4]).css('background-color', "#b8f6f0");
                  $(cells[4]).css('font-weight', "bold");
              },
              //properties
              "responsive": true,  "destroy": true, "lengthChange": false, "autoWidth": false, "searching": false, "info" : false,"ordering" : true,
              paging: true
      });

      $('#lowRatings').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ url('/dashboard/topTenLowOffices') }}" ,
              columns: [
                  { data: 'DT_RowIndex' },
                  { data: null,
                    render: function (data, type, row) {
                          var name = row.office;
                          return name;
                    }
                  },
                  { data: 'total_two_star' },
                  { data: 'total_one_star' },
                  { data: 'total_processed' },
                ],
              order: [[4, 'desc']],
              createdRow: (row, data, dataIndex, cells) => {
                  $(cells[4]).css('background-color', "#b8f6f0");
                  $(cells[4]).css('font-weight', "bold");
              },
              //properties
              "responsive": true,  "destroy": true, "lengthChange": false, "autoWidth": false, "searching": false, "info" : false,"ordering" : true,
              paging: true
      });


      $('#topFiveUnresolved').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ url('/dashboard/topFiveUnresolved') }}" ,
              columns: [
                  { data: 'DT_RowIndex' },
                  { data: null,
                    render: function (data, type, row) {
                          var name = row.office;
                          return name;
                    }
                  },
                  { data: 'total_unresolved' },
                  { data: 'total_processed' },
                ],
              order: [[3, 'desc']],
              createdRow: (row, data, dataIndex, cells) => {
                  $(cells[3]).css('background-color', "#b8f6f0");
                  $(cells[3]).css('font-weight', "bold");
              },
              //properties
              "responsive": true,  "destroy": true, "lengthChange": false, "autoWidth": false, "searching": false, "info" : false,"ordering" : true,
              paging: true
      });

      $('#topFiveResolved').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ url('/dashboard/topFiveResolved') }}" ,
              columns: [
                  { data: 'DT_RowIndex' },
                  { data: null,
                    render: function (data, type, row) {
                          var name = row.office;
                          return name;
                    }
                  },
                  { data: 'one_day' },
                  { data: 'three_days' },
                  { data: 'total_processed' },
                ],
              order: [[4, 'desc']],
              createdRow: (row, data, dataIndex, cells) => {
                  $(cells[4]).css('background-color', "#b8f6f0");
                  $(cells[4]).css('font-weight', "bold");
              },
              //properties
              "responsive": true,  "destroy": true, "lengthChange": false, "autoWidth": false, "searching": false, "info" : false,"ordering" : true,
              paging: true
      });
      
      $('#topLowResolved').DataTable({
              processing: true,
              serverSide: true,
              ajax: "{{ url('/dashboard/topLowResolved') }}" ,
              columns: [
                  { data: 'DT_RowIndex' },
                  { data: null,
                    render: function (data, type, row) {
                          var name = row.office;
                          return name;
                    }
                  },
                  { data: 'five_days' },
                  { data: 'seven_days' },
                  { data: 'thirty_days' },
                  { data: 'more_than' },
                  { data: 'total_processed' },
                ],
              // order: [[6, 'desc']],
              // createdRow: (row, data, dataIndex, cells) => {
              //     $(cells[6]).css('background-color', "#b8f6f0");
              //     $(cells[6]).css('font-weight', "bold");
              // },
              //properties
              "responsive": true,  "destroy": true, "lengthChange": false, "autoWidth": false, "searching": false, "info" : false,"ordering" : true,
              paging: true
      });

        // CHARTS
      //=====================================CHART BY Resolved =============================
      var cData = JSON.parse(`<?php echo $resolve_data; ?>`);
      console.log(cData);
      var ctx = $("#chartbyResolve");
      var data = {
        labels: cData.label_type,
        datasets: [
          {
            label: "Tickets Count",
            data: cData.data_type,
            backgroundColor: [
              "#28a745", //green
              "#007bff", //blue
              "#6f42c1", //purple
              "#ffc107", //yellow
              "#fd7e14", //orange
              "#dc3545", //red
            ],
            borderColor: [
              "#ffffff",
            ],
            borderWidth: [1, 1, 1, 1, 1,1,1]
          }
        ]
      };
 
      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "Generated by days",
          fontSize: 14,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 14
          }
        },
        tooltips: {
          mode: "label",
          callbacks: {
            label: function(tooltipItem, data) {
              var allData = data.datasets[tooltipItem.datasetIndex].data;
              var tooltipLabel = data.labels[tooltipItem.index];
              var tooltipData = allData[tooltipItem.index];
              var total = 0;
              for (var i in allData) {
                total += allData[i];
              }
              var tooltipPercentage = Math.round((tooltipData / total) * 100);
              return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
            }
          }
        },
        plugins: {
          // Change options for ALL labels of THIS CHART
          // datalabels: {
          //   color: "#ffffff",
          //   align: "center"
          // }
          datalabels: false
        }
      };

      //create Pie Chart class object
      var chart1 = new Chart(ctx, {
        type: "doughnut",
        data: data,
        options: options
      });

       //=====================================CHART BY Assigned =============================
      var cData = JSON.parse(`<?php echo $assigned_data; ?>`);
      console.log(cData);
      var ctx = $("#chartbyAssigned");
      var data = {
        labels: cData.label_type,
        datasets: [
          {
            label: "Tickets Count",
            data: cData.data_type,
            backgroundColor: [
              "#007bff", //blue
              "#fd7e14", //orange
              "#dc3545", //red
            ],
            borderColor: [
              "#ffffff",
            ],
            borderWidth: [1, 1, 1, 1, 1,1,1]
          }
        ]
      };
 
      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "Generated by days",
          fontSize: 14,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 14
          }
        },
        tooltips: {
          mode: "label",
          callbacks: {
            label: function(tooltipItem, data) {
              var allData = data.datasets[tooltipItem.datasetIndex].data;
              var tooltipLabel = data.labels[tooltipItem.index];
              var tooltipData = allData[tooltipItem.index];
              var total = 0;
              for (var i in allData) {
                total += allData[i];
              }
              var tooltipPercentage = Math.round((tooltipData / total) * 100);
              return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
            }
          }
        },
        plugins: {
          // Change options for ALL labels of THIS CHART
          // datalabels: {
          //   color: "#ffffff",
          //   align: "center"
          // }
          datalabels: false
        }
      };

      //create Pie Chart class object
      var chart1 = new Chart(ctx, {
        type: "doughnut",
        data: data,
        options: options
      });

        //=====================================CHART BY Feedback =============================
        var cData = JSON.parse(`<?php echo $feedback_data; ?>`);
      console.log(cData);
      var ctx = $("#chartbyFeedback");
      var data = {
        labels: cData.label_type,
        datasets: [
          {
            label: "Tickets Count",
            data: cData.data_type,
            backgroundColor: [
              "#17a2b8", //teal
              "#6f42c1", //purple
              "#ffc107", //yellow
            ],
            borderColor: [
              "#ffffff",
            ],
            borderWidth: [1, 1, 1, 1, 1,1,1]
          }
        ]
      };
 
      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "Generated by days",
          fontSize: 14,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 14
          }
        },
        tooltips: {
          mode: "label",
          callbacks: {
            label: function(tooltipItem, data) {
              var allData = data.datasets[tooltipItem.datasetIndex].data;
              var tooltipLabel = data.labels[tooltipItem.index];
              var tooltipData = allData[tooltipItem.index];
              var total = 0;
              for (var i in allData) {
                total += allData[i];
              }
              var tooltipPercentage = Math.round((tooltipData / total) * 100);
              return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
            }
          }
        },
        plugins: {
          // Change options for ALL labels of THIS CHART
          // datalabels: {
          //   color: "#ffffff",
          //   align: "center"
          // }
          datalabels: false
        }
      };

      //create Pie Chart class object
      var chart1 = new Chart(ctx, {
        type: "doughnut",
        data: data,
        options: options
      });

   
     
  });
</script>

@endsection