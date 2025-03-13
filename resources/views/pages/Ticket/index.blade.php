@extends('layouts.app',[
    'page' => 'Manage Documents',
    'title' => 'Manage Documents'
])

@section('content')
<section class="content">
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


  <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h4 class="h2" style="padding-left: 5px">List of Documents</h4>
                <div class="btn-toolbar mb-2 mb-md-0">
                  <div class="btn-group mr-2">
                      <div class="container-login100-form-btn">
                          <div class="wrap-login100-form-btn">
                              <div class="login100-form-bgbtn"></div>
                              <button class="login100-form-btn" onclick="location.href='{{ URL('/ticket') }}'"><i class="fa fa-plus pr-2"></i> New Request</button>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
        </div>
        <div class="card card-primary card-tabs" style="margin: 10px">
            <div class="card-header p-0 pt-1 bg-dblue">
                <ul class="nav nav-tabs" id="settings-tab" role="tablist">
                  @if(Auth::user()->role->id!=4)
                  <li class="nav-item">
                    <a class="nav-link active" id="created" data-toggle="pill" href="#created-content" role="tab" aria-controls="created-content" aria-selected="true">Requested</a>
                  </li>
                  @endif
                  <li class="nav-item">
                          <a class="nav-link {{Auth::user()->role->id == 4 ? 'active' : 'null'}}" id="assigned" data-toggle="pill" href="#assigned-content" role="tab" aria-controls="assigned-content" aria-selected="false">For Review</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="working" data-toggle="pill" href="#working-content" role="tab" aria-controls="working-content" aria-selected="false">For Approval</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="for-closing" data-toggle="pill" href="#for-closing-content" role="tab" aria-controls="for-closing-content" aria-selected="false">For Registration</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="set-content">
                  @if(Auth::user()->role->id!=4)
                  <div class="tab-pane fade show active " id="created-content" role="tabpanel" aria-labelledby="created-tab">
                    @include('pages.ticket.requested-document')
                  </div>
                 @endif
                 <div class="tab-pane fade {{Auth::user()->role->id == 4 ? 'show active' : 'null'}}" id="assigned-content" role="tabpanel" aria-labelledby="assigned-tab">
                  @include('pages.ticket.assigned')
                </div>
                  <div class="tab-pane fade" id="working-content" role="tabpanel" aria-labelledby="working-tab">
                    @include('pages.ticket.working')
                  </div>
                  <div class="tab-pane fade" id="for-closing-content" role="tabpanel" aria-labelledby="for-closing-tab">
                    @include('pages.ticket.for-closing')
                  </div>
                  <div class="tab-pane fade" id="closed-content" role="tabpanel" aria-labelledby="closed-tab">
                    @include('pages.ticket.closed')
                  </div>
                  <div class="tab-pane fade" id="with-feedback-content" role="tabpanel" aria-labelledby="with-feedback-tab">
                    @include('pages.ticket.with-feedback')
                  </div>
                  <div class="tab-pane fade" id="cancelled-content" role="tabpanel" aria-labelledby="cancelled-tab">
                    @include('pages.ticket.cancelled')
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
        url: "{{ url('/ticket/computeTotal')}}",
        dataType: 'json',
        success:function(res)
        {
          $(".register").html(res['register']);
          $(".review").html(res['review']);
          $(".approval").html(res['approval']);
          $(".archive").html(res['archive']);
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