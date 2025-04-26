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
        <span class="info-box-text"><strong>Registered Documents</strong></span>
        <span class="info-box-number register"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-danger">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #dc3545">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><strong>For Review</strong></span>
        <span class="info-box-number review"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-warning">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #ffc107">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><strong>For Approval</strong></span>
        <span class="info-box-number approval"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-secondary">
      <span class="info-box-icon elevation-1" style="background-color: white; color: gray">
      <i class='fas fa-file-alt' style='font-size:48px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><strong>Archived Documents</strong></span>
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
        <span class="info-box-text"><strong>Quality Procedure</strong></span>
        <span class="info-box-number qpt"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-dblue">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><strong>Procedure Manuals</strong></span>
        <span class="info-box-number pmt"></span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-dblue">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content ">
        <span class="info-box-text word-wrap"><strong>Forms & Templates</strong></span>
        <span class="info-box-number ftg"></span>
      </div>
    </div>
  </div>
</div>


  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h6 class="card-title"><strong>Quality Manuals & Procedures</strong></h6>
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

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h6 class="card-title"><strong>ORD Procedure Manuals</strong></h6>
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

   <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h6 class="card-title"><strong>TOD Procedure Manuals</strong></h6>
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

  <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h6 class="card-title"><strong>AFD Procedure Manuals</strong></h6>
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
          ],
            language: {
                emptyTable: 'There are no registered Quality Manuals & Procedures'
            }
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
          ],
            language: {
                emptyTable: 'There are no registered ORD Procedure Manuals'
            }
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
          ],
            language: {
                emptyTable: 'There are no registered TOD Procedure Manuals'
            }
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
          ],
            language: {
                emptyTable: 'There are no registered AFD Procedure Manuals'
            }
      });

    });
  </script>
@endsection