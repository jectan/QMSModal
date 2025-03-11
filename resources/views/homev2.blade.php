@extends('layouts.app',[
    'page' => 'Dashboard',
    'title' => 'Dashboard'
])

@section('content')
<!-- Preloader -->
<div class="row">

<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-success">
      <span class="info-box-icon elevation-1" style="background-color: white; color: green">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Registered Documents</span>
        <span class="info-box-number closed">33</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-danger">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #dc3545">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">For Review</span>
        <span class="info-box-number created">10</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-warning">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #ffc107">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">For Approval</span>
        <span class="info-box-number working">5</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-secondary">
      <span class="info-box-icon elevation-1" style="background-color: white; color: gray">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Obsolete Documents</span>
        <span class="info-box-number for-closing">20</span>
      </div>
    </div>
  </div>
</div>

 <!-- Count on Doc Type-->
<div class="row">
  <div class="col-12 col-sm-6 col-md-1"> 
  </div>
  
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-info">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #17a2b8">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Quality Procedure</span>
        <span class="info-box-number total">5</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-info">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #17a2b8">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Procedure Manuals</span>
        <span class="info-box-number total">33</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-info">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #17a2b8">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content ">
        <span class="info-box-text word-wrap">Forms,Templates & Guidelines</span>
        <span class="info-box-number total">103</span>
      </div>
    </div>
  </div>
</div>


  <div class="row">
    <!--Top Ten Offices Tables-->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h6 class="card-title">Quality Manuals & Procedures</h6>
        </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm" id="QMQPs" style="font-size: 14px">
                  <thead>
                    <tr>
                      <th style="width: 20%">Doc Ref Code</th>
                      <th style="width: 70%">Title</th>
                      <th style="width: 10%">Rev No.</th>
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
          <h6 class="card-title">ORD Procedure Manuals</h6>
        </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm" id="QMQPs" style="font-size: 14px">
                  <thead>
                    <tr>
                      <th style="width: 20%">Doc Ref Code</th>
                      <th style="width: 70%">Title</th>
                      <th style="width: 10%">Rev No.</th>
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
          <h6 class="card-title">TOD Procedure Manuals</h6>
        </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm" id="QMQPs" style="font-size: 14px">
                  <thead>
                    <tr>
                      <th style="width: 20%">Doc Ref Code</th>
                      <th style="width: 70%">Title</th>
                      <th style="width: 10%">Rev No.</th>
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
          <h6 class="card-title">AFD Procedure Manuals</h6>
        </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-sm" id="QMQPs" style="font-size: 14px">
                  <thead>
                    <tr>
                      <th style="width: 20%">Doc Ref Code</th>
                      <th style="width: 70%">Title</th>
                      <th style="width: 10%">Rev No.</th>
                      </tr>
                  </thead>
                </table>
            </div>
      </div>
    </div>
</div>

  
<!--script>
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
      var cData = JSON.parse(`<?php //echo $resolve_data; ?>`);
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
      var cData = JSON.parse(`<?php //echo $assigned_data; ?>`);
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
        var cData = JSON.parse(`<?php //echo $feedback_data; ?>`);
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
</script-->

@endsection