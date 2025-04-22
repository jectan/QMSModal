@extends('layouts.app',[
    'page' => 'Masterlist',
    'title' => 'Masterlist'
])

@section('content')
<section class="content">

<div class="row"> 
  <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box small-box bg-dblue">
        <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
        <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><strong>Quality Manual</strong></span>
          <span class="info-box-number qm"></span>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box small-box bg-dblue">
        <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
        <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><strong>Quality Procedure</strong></span>
          <span class="info-box-number qp"></span>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box small-box bg-dblue">
        <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
        <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><strong>Procedure Manuals</strong></span>
          <span class="info-box-number pm"></span>
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

<div class="card">
  <div class="card-header">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h4 class="h2" style="padding-left: 5px">List of Documents</h4>
      </div>
  </div>
  <div class="card card-primary card-tabs" style="margin: 10px">
      <div class="card-header p-0 pt-1 bg-dblue">
          <ul class="nav nav-tabs" id="settings-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="qualityManual" data-toggle="pill" href="#quality-manual" role="tab" aria-controls="quality-manual" aria-selected="true">Quality Manual</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" id="qualityProcedure" data-toggle="pill" href="#quality-procedure" role="tab" aria-controls="quality-procedure" aria-selected="false">Quality Procedure</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" id="operationsManual" data-toggle="pill" href="#operations-manual" role="tab" aria-controls="operations-manual" aria-selected="false">Operations Manual</a>
              </li>
            <li class="nav-item">
              <a class="nav-link " id="procedureManual" data-toggle="pill" href="#procedure-manual" role="tab" aria-controls="procedure-manual" aria-selected="false">Procedure Manuals</a>
            </li>
            <li class="nav-item">
              <a class="nav-link " id="formsTemplatesGuidelines" data-toggle="pill" href="#forms-templates-guidelines" role="tab" aria-controls="forms-templates-guidelines" aria-selected="false">Forms, Templates & Guidelines</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="set-content">
            <div class="tab-pane fade show active" id="quality-manual" role="tabpanel" aria-labelledby="quality-manual">
              @include('pages.masterlist.qualitymanuals')
            </div>
            <div class="tab-pane fade" id="quality-procedure" role="tabpanel" aria-labelledby="quality-procedure">
              @include('pages.masterlist.qualityprocedures')
            </div>
            <div class="tab-pane fade" id="operations-manual" role="tabpanel" aria-labelledby="operations-manual">
              @include('pages.masterlist.operationsmanual')
            </div>
            <div class="tab-pane fade" id="procedure-manual" role="tabpanel" aria-labelledby="procedure-manual">
              @include('pages.masterlist.proceduremanuals')
            </div>
            <div class="tab-pane fade" id="forms-templates-guidelines" role="tabpanel" aria-labelledby="forms-templates-guidelines">
              @include('pages.masterlist.formstemplatesguidelines')
            </div>
            <div class="tab-pane fade" id="closed-content" role="tabpanel" aria-labelledby="closed-tab">
              
            </div>
            <div class="tab-pane fade" id="with-feedback-content" role="tabpanel" aria-labelledby="with-feedback-tab">
              
            </div>
            <div class="tab-pane fade" id="cancelled-content" role="tabpanel" aria-labelledby="cancelled-tab">
          
            </div>
          </div>
        </div>
    </div>
</div>
    
</section>

<script>
  $(document).ready(function(){
    //  COUNT TOTAL
      $.ajax({
        type: 'GET',
        url: "{{ url('/masterlist/computeTotal')}}",
        dataType: 'json',
        success:function(res)
        {
          $(".qp").html(res['qp']);
          $(".qm").html(res['qm']);
          $(".pm").html(res['pm']);
          $(".ftg").html(res['ftg']);
        }
      });
  });

  // displayRequest function
  function displayRequest(requestID) {
      window.location.href = "{{ url('/masterlist/view') }}/" + requestID;
  }

</script>
@endsection