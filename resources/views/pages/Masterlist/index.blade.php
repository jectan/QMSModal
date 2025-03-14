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
        <span class="info-box-text">Quality Manual</span>
        <span class="info-box-number total">5</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-dblue">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Quality Procedure</span>
        <span class="info-box-number total">5</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-dblue">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Procedure Manuals</span>
        <span class="info-box-number total">33</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box small-box bg-dblue">
      <span class="info-box-icon elevation-1" style="background-color: white; color: #1f488f">
      <i class='fas fa-file-alt' style='font-size:40px;'></i></span>
      <div class="info-box-content ">
        <span class="info-box-text word-wrap">Forms,Templates & Guidelines</span>
        <span class="info-box-number total">103</span>
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
                  @if(Auth::user()->role->id!=4)
                  <li class="nav-item">
                    <a class="nav-link active" id="created" data-toggle="pill" href="#created-content" role="tab" aria-controls="created-content" aria-selected="true">Quality Manual</a>
                  </li>
                  @endif
                  <li class="nav-item">
                          <a class="nav-link {{Auth::user()->role->id == 4 ? 'active' : 'null'}}" id="assigned" data-toggle="pill" href="#assigned-content" role="tab" aria-controls="assigned-content" aria-selected="false">Quality Procedure</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="working" data-toggle="pill" href="#working-content" role="tab" aria-controls="working-content" aria-selected="false">Procedure Manuals</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="for-closing" data-toggle="pill" href="#for-closing-content" role="tab" aria-controls="for-closing-content" aria-selected="false">Forms, Templates & Guidelines</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="set-content">
                  @if(Auth::user()->role->id!=4)
                  <div class="tab-pane fade show active " id="created-content" role="tabpanel" aria-labelledby="created-tab">
                    @include('pages.masterlist.qualitymanuals')
                  </div>
                 @endif
                 <div class="tab-pane fade {{Auth::user()->role->id == 4 ? 'show active' : 'null'}}" id="assigned-content" role="tabpanel" aria-labelledby="assigned-tab">
                 @include('pages.masterlist.qualityprocedures')
                </div>
                  <div class="tab-pane fade" id="working-content" role="tabpanel" aria-labelledby="working-tab">
                  
                  </div>
                  <div class="tab-pane fade" id="for-closing-content" role="tabpanel" aria-labelledby="for-closing-tab">
                   
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
        url: "{{ url('/documents/computeTotal')}}",
        dataType: 'json',
        success:function(res)
        {
          $(".total").html(res['total_ticket']);
          $(".assigned").html(res['assigned']);
          $(".working").html(res['working']);
          $(".for-closing").html(res['for-closing']);
          $(".closed").html(res['closed']);
          $(".feedback").html(res['feedback']);
        }
      });
    });

</script>
@endsection