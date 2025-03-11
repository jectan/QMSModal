@extends('layouts.app',[
    'page' => 'Tickets',
    'title' => ''
])

@section('content')
<section class="content">
  <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h4 class="h2" style="padding-left: 5px">List of Documents</h4>
            </div>
        </div>
        <div class="card card-primary card-tabs" style="margin: 10px">
          @if(Auth::user()->role->id==4)
          <div class="row">
            <div class="col-12 col-sm-6 col-md-2">
              <div class="info-box small-box" >
                <span class="info-box-icon elevation-1" style="background-color: #17a2b8; color: white">
                <i class="fas fa-ticket-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Total Tickets</span>
                  <span class="info-box-number total"></span>
                </div>
              </div>
            </div>
          
            <div class="col-12 col-sm-6 col-md-2">
              <div class="info-box small-box">
                <span class="info-box-icon elevation-1" style="background-color: #dc3545; color: white">
                <i class="fas fa-ticket-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">New Tickets</span>
                  <span class="info-box-number assigned"></span>
                </div>
              </div>
            </div>
                  
            <div class="col-12 col-sm-6 col-md-2">
              <div class="info-box small-box">
                <span class="info-box-icon elevation-1" style="background-color: #ffc107; color: white">
                <i class="fas fa-ticket-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Working</span>
                  <span class="info-box-number working"></span>
                </div>
              </div>
            </div>
          
            <div class="col-12 col-sm-6 col-md-2">
              <div class="info-box small-box ">
                <span class="info-box-icon elevation-1" style="background-color: gray; color: white">
                <i class="fas fa-ticket-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">For Closing</span>
                  <span class="info-box-number for-closing"></span>
                </div>
              </div>
            </div>
          
            <div class="col-12 col-sm-6 col-md-2">
              <div class="info-box small-box">
                <span class="info-box-icon elevation-1" style="background-color: green; color: white">
                <i class="fas fa-ticket-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Closed</span>
                  <span class="info-box-number closed"></span>
                </div>
              </div>
            </div>

            <!-- <div class="col-12 col-sm-6 col-md-2">
              <div class="info-box small-box ">
                <span class="info-box-icon elevation-1" style="background-color:  #007bff; color:white">
                <i class="fas fa-ticket-alt"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">With Feedback</span>
                  <span class="info-box-number feedback"></span>
                 
                </div>
              </div>
            </div>
          </div> -->
          @endif
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="settings-tab" role="tablist">
                  @if(Auth::user()->role->id!=4)
                  <li class="nav-item">
                    <a class="nav-link active" id="created" data-toggle="pill" href="#created-content" role="tab" aria-controls="created-content" aria-selected="true">Created</a>
                  </li>
                  @endif
                  <li class="nav-item">
                          <a class="nav-link {{Auth::user()->role->id == 4 ? 'active' : 'null'}}" id="assigned" data-toggle="pill" href="#assigned-content" role="tab" aria-controls="assigned-content" aria-selected="false">Assigned</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link " id="working" data-toggle="pill" href="#working-content" role="tab" aria-controls="working-content" aria-selected="false">Working</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="for-closing" data-toggle="pill" href="#for-closing-content" role="tab" aria-controls="for-closing-content" aria-selected="false">For Closing</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="closed" data-toggle="pill" href="#closed-content" role="tab" aria-controls="closed-content" aria-selected="false">Closed</a>
                  </li>
                  <!-- <li class="nav-item">
                    <a class="nav-link " id="with-feedback" data-toggle="pill" href="#with-feedback-content" role="tab" aria-controls="with-feedback-content" aria-selected="false">With Feedback</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link " id="cancelled" data-toggle="pill" href="#cancelled-content" role="tab" aria-controls="cancelled-content" aria-selected="false">Cancelled</a>
                  </li> -->
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="set-content">
                  @if(Auth::user()->role->id!=4)
                  <div class="tab-pane fade show active " id="created-content" role="tabpanel" aria-labelledby="created-tab">
                    @include('pages.ticket.created-tickets')
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
              <!-- /.card -->
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