@extends('pages.feedback.index')
@section('content')
    <div class="body1">
        <div class="body2">
            <form class="form-style-9" style="width: 1500px;">
                @csrf
                <div class="row justify-content-md-center" style="margin-bottom: 2%">
                    <div class="col-md-6" style="width: 100%">
                        <img class="img-fluid" src="/img/track-form.png" alt="">
                    </div>
                </div>
                <div class="card" style="padding: 5%">
                    <div class="row">
                        <span class="number" style="margin-bottom: 20px">1</span><h4>Basic Information</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Ticket No.</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="field-style form-control row-margin" disabled=""
                                style="background: white;" value="{{$ticket->ticket_no}}">  
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Full Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="field-style form-control row-margicn" disabled=""
                                style="background: white;"value="{{$ticket->caller->firstname.' '.$ticket->caller->middlename.' '.$ticket->caller->lastname}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div>
                                <label>Contact No.</label>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="field-style form-control row-margin" disabled=""
                                    style="background: white;"value="{{$ticket->caller->contact_no}}">  
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label>Email Address</label>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="field-style form-control row-margin" disabled=""
                                    style="background: white;"value="{{$ticket->caller->email}}"> 
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div>
                                <label>Address</label>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="field-style form-control row-margin" disabled=""
                                    style="background: white;"value="{{$ticket->caller->address}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <label>Barangay</label>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="field-style form-control row-margin" disabled=""
                                    style="background: white;"value="{{$ticket->caller->barangay->name}}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="padding: 5%">
                    <div class="row">
                        <span class="number" style="margin-bottom: 20px">1</span><h4>Ticket</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Ticket Status</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="field-style form-control row-margin" disabled=""
                                style="background: white;"value="{{$ticket->status}}">
                        </div>
                    </div>
                    {{-- //logs --}}
                    {{-- <div class="row">
                        <div class="col-md-4">
                            <a class="button8 btn-sm float-right mr-1" style="background-color: #2368d4" data-toggle="modal" data-target="#mdl-timeline-track"><i class="fas fa-pen-alt"></i>&nbsp;Status Log</a>

                        </div>
                    </div> --}}
                </div>
                <div id="Asiign Ticket">

                </div>
                <div id="Ticket Action">
                {{-- time-line-logs --}}

                </div>
                <div class="row">
                    <span class="number" style="margin-bottom: 20px"></span><h4>Ticket Log</h4>
                </div>
                {{-- time line logs --}}
                <div class="timeline" style="
                                        height: 450px;
                                        overflow-x: auto;
                                    ">
                        <!-- timeline time label -->
                        @php
                            $date_current = '';
                        
                        @endphp
                        
                        @foreach ($ticket->logs as $log)
                            @if ($date_current != date('F j, Y', strtotime($log->created_at)))
                                <div class="time-label">
                                    <span class="bg-primary">{{ date('F j, Y', strtotime($log->created_at)) }}</span>
                                </div>
                            @endif
                            @php
                                $date_current = date('F j, Y', strtotime($log->created_at));
                            @endphp

                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <div>
                                <i class="fas fa-pen-alt bg-pink"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i>
                                        {{ date('g:i a', strtotime($log->created_at)) }}</span>
                                    <h3 class="timeline-header"><a    
                                        href="#">{{ $log->assignedby ? $log->assignedby->staff->fullName : '' }}</a></h3>
                                    <div class="timeline-body">
                                        <label>Status:&nbsp;&nbsp;</label>{{ $log->status }}<br>
                                        <small>{!! $log->remarks !!}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- END timeline item -->
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                {{-- time-line-logs --}}
              

            </form>
        </div>
    </div>

@endsection
