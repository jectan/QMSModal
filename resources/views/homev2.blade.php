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
        <span class="info-box-number register"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-danger">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #dc3545">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">For Review</span>
        <span class="info-box-number review"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-warning">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #ffc107">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">For Approval</span>
        <span class="info-box-number approval"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-secondary">
      <span class="info-box-icon elevation-1" style="background-color: white; color: gray">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Archived Documents</span>
        <span class="info-box-number archive"></span>
      </div>
    </div>
  </div>
</div>

 <!-- Count on Doc Type-->
<div class="row">
  <div class="col-12 col-sm-6 col-md-1"> 
  </div>
  
  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-dblue">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Quality Procedure</span>
        <span class="info-box-number qpt"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-dblue">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Procedure Manuals</span>
        <span class="info-box-number pmt"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-dblue">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content ">
        <span class="info-box-text word-wrap">Forms,Templates & Guidelines</span>
        <span class="info-box-number ftg"></span>
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
                      <th style="width: 25%">Doc Ref Code</th>
                      <th style="width: 60%">Title</th>
                      <th style="width: 15%">Rev No.</th>
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
                <table class="table table-striped table-sm" id="OPMs" style="font-size: 14px">
                  <thead>
                    <tr>
                      <th style="width: 25%">Doc Ref Code</th>
                      <th style="width: 60%">Title</th>
                      <th style="width: 15%">Rev No.</th>
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
                <table class="table table-striped table-sm" id="TPMs" style="font-size: 14px">
                  <thead>
                    <tr>
                      <th style="width: 25%">Doc Ref Code</th>
                      <th style="width: 60%">Title</th>
                      <th style="width: 15%">Rev No.</th>
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
                <table class="table table-striped table-sm" id="APMs" style="font-size: 14px">
                  <thead>
                    <tr>
                      <th style="width: 25%">Doc Ref Code</th>
                      <th style="width: 60%">Title</th>
                      <th style="width: 15%">Rev No.</th>
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
          $(".register").html(res['register']);
          $(".review").html(res['review']);
          $(".approval").html(res['approval']);
          $(".archive").html(res['archive']);
          $(".qpt").html(res['qpt']);
          $(".pmt").html(res['pmt']);
          $(".ftg").html(res['ftg']);
        }
      });

      //LoadDataTable1
      $('#QMQPs').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ url('/dashboard/data-request/1') }}", // Fetch data for docTypeID = 1
          pageLength: 10, // Set default page limit to 10
          lengthChange: false, // Hide "Show entries" dropdown
          columns: [
              { data: 'docRefCode', name: 'docRefCode' },
              { data: 'docTitle', name: 'docTitle' },
              { data: 'currentRevNo', name: 'currentRevNo' }
          ]
      });

      //LoadDataTable2
      $('#OPMs').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ url('/dashboard/data-request/2') }}", // Fetch data for docTypeID = 1
          pageLength: 10, // Set default page limit to 10
          lengthChange: false, // Hide "Show entries" dropdown
          columns: [
              { data: 'docRefCode', name: 'docRefCode' },
              { data: 'docTitle', name: 'docTitle' },
              { data: 'currentRevNo', name: 'currentRevNo' }
          ]
      });

      //LoadDataTable3
      $('#TPMs').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ url('/dashboard/data-request/3') }}", // Fetch data for docTypeID = 1
          pageLength: 10, // Set default page limit to 10
          lengthChange: false, // Hide "Show entries" dropdown
          columns: [
              { data: 'docRefCode', name: 'docRefCode' },
              { data: 'docTitle', name: 'docTitle' },
              { data: 'currentRevNo', name: 'currentRevNo' }
          ]
      });

      //LoadDataTable4
      $('#APMs').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ url('/dashboard/data-request/4') }}", // Fetch data for docTypeID = 1
          pageLength: 10, // Set default page limit to 10
          lengthChange: false, // Hide "Show entries" dropdown
          columns: [
              { data: 'docRefCode', name: 'docRefCode' },
              { data: 'docTitle', name: 'docTitle' },
              { data: 'currentRevNo', name: 'currentRevNo' }
          ]
      });

    });
  </script>
@endsection