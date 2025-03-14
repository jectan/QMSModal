@extends('layouts.app',[
'page' => 'Ticket',
'title' => ''
])
@section('content')
    <div class="row" style="margin-bottom: 4px;">
        <div class="col-md-12">
            <a class="btn btn-secondary btn-sm float-right mr-1" onclick="location.href='{{ URL('/documents') }}'"><i
                    class="fas fa-chevron-left"></i>&nbsp;Back</a>
        </div>
    </div>
    <div class="card">
        <div class="card-header card-head">
            <h1 class="card-title"><i class="fas fa-ticket-alt"></i>&nbsp;Ticket</h1>



            @if ($ticket->status != 'cancelled')
                <a class="button8 btn-sm float-right mr-1" style="background-color: #2368d4" data-toggle="modal"
                    data-target="#mdl-timeline"><i class="fas fa-pen-alt"></i>&nbsp;Status Log</a>
                @if ($ticket->status == 'pending' || $ticket->status == 'created')
                    <a class="button8 btn-sm float-right mr-1" style="background-color: #28a745" data-toggle="modal"
                        data-target="#assignTicket-modal"><i class="fas fa-user-check"></i>&nbsp;Assign</a>
                    {{-- <a class="button8 btn-sm float-right mr-1" style="background-color: #dc3545"><i class="fas fa-trash"></i>&nbsp;Delete</a> --}}
                @endif
                @if ($ticket->status == 'created')
                    <a class="button8 btn-sm float-right mr-1" style="background-color: #ffd43b"
                        onclick="location.href='/documents/show/{{ $ticket->id }}'"><i
                            class="fas fa-edit"></i>&nbsp;Update</a>

                    <a class="button8 btn-sm float-right mr-1" style="background-color: #5d5d5d"><i
                            class="fas fa-ban"></i>&nbsp;Cancel</a>
                @endif
                @if (Auth::user()->role->id == 1 || Auth::user()->role->id == 3)

                    @if ($ticket->status == 'for-closing')
                        <a class="button8 btn-sm float-right mr-1" style="background-color: #0cc521" {{-- onclick="location. --}}
                            href='/documents/close/{{ $ticket->id }}'><i class="fas fa-check-square"></i>&nbsp;Close</a>

                    @endif

                @endif
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <input type="hidden" name="id" id="id" value="{{ $ticket->id }}">
                    <h4 class="border-bottom">Ticket No. : {{ $ticket->ticket_no }}</h4>
                    <div class="row m-0">
                        <h4>Status:&nbsp;</h4>
                        @if ($ticket->status == 'created')
                            <span class="badge badge-pill badge-success createdStatus">{{ $ticket->status }}</span>
                        @elseif( $ticket->status == 'assigned')
                            <span class="badge badge-pill badge-success assignedStatus">{{ $ticket->status }}</span>
                        @elseif( $ticket->status == 'working')
                            <span class="badge badge-pill badge-success workingStatus">{{ $ticket->status }}</span>
                        @elseif( $ticket->status == 'for-closing')
                            <span class="badge badge-pill badge-success forClosingStatus">{{ $ticket->status }}</span>
                        @elseif( $ticket->status == 'closed')
                            <span class="badge badge-pill badge-success closedStatus">{{ $ticket->status }}</span>
                        @elseif( $ticket->status == 'with-feedback')
                            <span class="badge badge-pill badge-success withFeedbackStatus">{{ $ticket->status }}</span>
                        @elseif( $ticket->status == 'closed')
                            <span class="badge badge-pill badge-success cancelledStatus">{{ $ticket->status }}</span>
                        @elseif( $ticket->status == 'cancelled')
                            <span class="badge badge-pill badge-success cancelledStatus">{{ $ticket->status }}</span>
                        @endif
                    </div>
                    <br>
                    <div>
                        <label class="m-0 col-md-12">Caller Name:</label>
                        <h4 class="col-md-12">{{ $ticket->caller ? $ticket->caller->fullname : ' ' }}</h2>
                            <br>
                            <label class="m-0 col-md-12">Contact Number:</label>
                            <h4 class="col-md-12">{{ $ticket->caller ? $ticket->caller->contact_no : ' ' }}</h2>
                                <br>
                                <label class="m-0 col-md-12">Email:</label>
                                <h4 class="col-md-12">{{ $ticket->caller ? $ticket->caller->email : ' ' }}</h4>
                                <br>
                                <label class="m-0 col-md-12">Address:</label>
                                <h4 class="col-md-12">{{ $ticket->caller ? $ticket->caller->address : ' ' }}</h4>
                                <br>
                                <label class="m-0 col-md-12">Call Type:</label>
                                <h4 class="col-md-12">{{ $ticket->callerType ? $ticket->callerType->name : ' ' }}
                                </h4>
                                <br>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="card-body">
                        <div class="row mt-2">
                            <label>Call Time and Date:&nbsp;</label>
                            <h5> {{ $ticket->call_datetime }}</h5>
                        </div>
                        <br>
                        <div class="row">
                            <label for="">Call Details </label>

                            <span class="field-style form-control control">{!! $ticket->call_details !!}</span>

                        </div>
                    </div>
                </div>
            </div>
            @if ($ticket->updated_by_id == '')
                <p>Created By : {{ $ticket->created_by_id ? $ticket->createdBy->staff->fullname : ' ' }} on
                    {{ $ticket->created_at }}</p>
            @else
                <p>Created By : {{ $ticket->created_by_id ? $ticket->createdBy->staff->fullname : ' ' }} on
                    {{ $ticket->created_at }}</p>
                <p>Updated By : {{ $ticket->updated_by_id ? $ticket->updatedBy->staff->fullname : ' ' }} on
                    {{ $ticket->updated_at }}</p>
            @endif
        </div>
    </div>
    @if (Auth::user()->role->id == 1 || Auth::user()->role->id == 2)
        <div class="card">
            <div class="card-header card-head">
                <h1 class="card-title"><i class="fas fa-briefcase"></i>&nbsp;Assigned Offices</h1>
            </div>

            <div class="card-body">
                <div class="col-md-12 userofficestbl">
                    <table class="table table-sm" id="assigned-offices-dt" style="font-size: 14px">
                        <thead>
                            <tr>
                                <th style="width: 40%">Office</th>
                                <th style="width: 30%">Assigned By</th>
                                <th>Assigned At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        @if ($ticket->rating)
            <div class="card">
                <div class="card-header card-head">
                    <h1 class="card-title"><i class="fas fa-briefcase"></i>&nbsp;Feedback</h1>
                </div>

                <div class="card-body">
                    <label for="">Comment:</label>
                    <div class="field-style form-control control" style="word-break: break-all;">
                        {!! $ticket->feedback !!}
                    </div>
                    <div class="star-rating">
                        <div class="star-input">
                            @for ($i = 0; $i < 5 - $ticket->rating; $i++)
                                <label for="rating" class="fas fa-star" style="color:none"></label>
                            @endfor
                            @for ($i = 0; $i < $ticket->rating; $i++)
                                <label for="rating" class="fas fa-star" style="color:#ff7bad"></label>

                            @endfor

                            @switch($ticket->rating)
                                @case(1)
                                    <label style="   font-size: xx-large;
                                    text-align: center;
                                    width: 100%;
                                    color: #ff7bad;"><i class="fas fa-angry"></i>Very Unsatisfied</label>
                                @break
                                @case(2)
                                    <label style="   font-size: xx-large;
                                    text-align: center;
                                    width: 100%;
                                    color: #ff7bad;">Unsatisfied&nbsp;<i class="fas fa-frown"></i></label>
                                @break
                                @case(3)
                                    <label style="   font-size: xx-large;
                                    text-align: center;
                                    width: 100%;
                                    color: #ff7bad;">OK&nbsp;<i class="fas fa-meh"></i></label>
                                @break
                                @case(4)
                                    <label style="   font-size: xx-large;
                                    text-align: center;
                                    width: 100%;
                                    color: #ff7bad;">Satisfied&nbsp;<i class="fas fa-smile"></i></label>
                                @break
                                @case(5)
                                    <label style="   font-size: xx-large;
                                    text-align: center;
                                    width: 100%;
                                    color: #ff7bad;">Very Satisfied&nbsp;<i class="fas fa-laugh-beam"></i></label>
                                @break

                                @default

                            @endswitch



                        </div>
                    </div>

                </div>

            </div>
        @endif
        @foreach ($ticket->actions as $action)
            <div class="modal" tabindex="-1" id="mdl-timeline-office{{ $action->id }}" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Assessment Log</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="timeline" style="
                                                height: 450px;
                                                overflow-x: auto;
                                            ">
                                <!-- timeline time label -->
                                @php
                                    $date_current = '';
                                    
                                @endphp
                                @if ($action->logs)
                                    @foreach ($action->logs as $log)
                                        @if ($date_current != date('F j, Y', strtotime($log->created_at)))
                                            <div class="time-label">
                                                <span
                                                    class="bg-primary">{{ date('F j, Y', strtotime($log->created_at)) }}</span>
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
                                                        href="#">{{ $log->assignedby ? $log->assignedby->staff->fullName : '' }}</a>
                                                </h3>
                                                <div class="timeline-body">
                                                    {{ $log->status }}<br>
                                                    <small>{!! $log->remarks !!}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                @endif
                                <!-- END timeline item -->
                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    @if (Auth::user()->role->id == 4)

        <div class="card">
            <div class="card-header card-head">
                <h1 class="card-title"><i class="fas fa-clipboard-list"></i>&nbsp;Office Working Details</h1>
                @if ($assigned_office_action->status == 'pending')
                    <a class="btn btn-warning btn-sm float-right mr-1" data-toggle="modal" data-target="#action-modal"><i
                            class="fas fa-tools"></i>&nbsp;Start Working</a>
                @endif
                @if ($assigned_office_action->status == 'working')
                    <a class="btn btn-success btn-sm float-right mr-1" data-toggle="modal" data-target="#resolved-modal"><i
                            class="fas fa-check-circle"></i>&nbsp;Resolved</a>
                    <a class="btn btn-danger btn-sm float-right mr-1" data-toggle="modal"
                        data-target="#unresolvable-modal"><i class="fas fa-times-circle"></i>&nbsp;Unresolvable</a>
                @endif

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ml-2">
                            <div class="row">
                                <h4>Status:&nbsp;</h4>
                                @if ($assigned_office_action->status == 'working')
                                    <span class="badge badge-pill badge-success workingStatus">Working</span>
                                @elseif($assigned_office_action->status == 'resolved')
                                    <span class="badge badge-pill badge-success createdStatus">Resolved</span>
                                @elseif($assigned_office_action->status == 'unresolved')
                                    <span class="badge badge-pill badge-success cancelledStatus">Unresolve</span>
                                @endif
                            </div>
                            <div class="card" style="padding: 20px;">
                                <div>
                                    <label class="m-0 col-md-12">Work Started:</label>
                                    @if ($assigned_office_action->status == 'working' || $assigned_office_action->status == 'resolved' || $assigned_office_action->status == 'unresolved')
                                        <h4 class="col-md-12">{{ $assigned_office_action->created_at }}</h4>
                                    @endif
                                </div>
                                <br>
                                <div>
                                    <label class="m-0 col-md-12">Estimated date Time Resolve </label>
                                    <h4 class="col-md-12">{{ $assigned_office_action->estimated_date }}</h4>
                                </div>
                                <br>
                                <div>
                                    <label class="m-0 col-md-12">Remarks:</label>
                                    <h4 class="col-md-12">{{ $assigned_office_action->remarks }}</h4>
                                </div>
                            </div>

                            <div class="card" style="padding: 20px;">
                                <div>
                                    <label class="m-0 col-md-12">Actual Date Time Resolved:/Unresolved</label>
                                    <h4 class="col-md-12">
                                        {{ $assigned_office_action->actual_date ? $assigned_office_action->actual_date : 'N/A' }}
                                    </h4>
                                </div>
                                <br>
                                <div>
                                    <label class="m-0 col-md-12">Work Done:</label>
                                    <h4 class="col-md-12">{!! $assigned_office_action->work_done !!}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-header card-head">
            <h1 class="card-title"><i class="fas fa-ticket-alt"></i>&nbsp;Feedback</h1>
            <div class="card-body">
                
            </div>
        </div>
    </div>

    <!--ASSIGN TICKET MODAL -->
    <div class="modal fade" id="assignTicket-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                {{-- <form id="asiign-form" name="assign-form" class="form-horizontal" method="POST"> --}}
                {{-- @csrf --}}
                <div class="modal-header card-head">
                    <h4 class="modal-title" id="office-modal-title">Assign Ticket</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="/documents/assign/add" id="AccountForm" name="AccountForm">
                        @csrf
                        <div class="form-group row" id="user_offices">
                            <label for="name" class="col-md-3">Offices<span class="require">*</span></label>
                            <div class="input-group col-md-9">
                                <input id="ticket_id" type="hidden" value=" {{ $ticket->id }}" name="ticket_id" />
                                <select name="office_id" id="office_id" class="form-control">
                                    <option value="" disabled selected>Select Office</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->name }}</option>

                                    @endforeach
                                </select>

                                <div class="input-group-append">
                                    <button type="button" onclick="addUserOffice()" id="addbtn"
                                        class="btn btn-default btn-flat"><i class="fa fa-plus pr-2"></i></button>
                                </div>
                                <div class="col-md-12 userofficestbl">
                                    <table class="table table-sm" id="user-offices" style="font-size: 14px">
                                        <thead>
                                            <tr>
                                                <th style="width: 80%">Name</th>
                                                <th style="width: 10%">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-default"
                        onclick="location.href='/documents/view/{{ $ticket->id }}'" data-dismiss="modal">Okay</button>

                </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>
    <!--TICKET ACTION MODAL -->
    <div class="modal fade" id="action-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form method="POST" action="/documents/update/working" id="ActionForm" name="ActionForm">
                    @csrf
                    <div class="modal-header card-head">
                        <h4 class="modal-title" id="action-modal-title">Ticket Action</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" value="{{ $ticket->id }}" name="ticket_id" />
                        <span><label class="m-0 col-md-12">Estimated Date/Time Resolve</label></span>
                        <div class="row">
                            <div class="input-group col-md-4">
                                <input type="datetime-local" class="form-control " value="" name="estimated_date">
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <span><label class="m-0 col-md-12">Remarks</label></span>
                            <textarea id="remarks" rows="8" name="remarks" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Ok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--TICKET CANCEL MODAL -->
    <div class="modal fade" id="cancel-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form id="cancel-form" name="cancel-form" class="form-horizontal" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{ $ticket->id }}">
                    <div class="modal-header card-head">
                        <h4 class="modal-title" id="cancel-modal-title">Cancel Ticket</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="m-0 col-md-12">Remarks <span class="require">*</span></label>
                            <textarea id="remarks" rows="6" name="remarks" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="cancel-btn">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Unresolvable MODAL -->
    <div class="modal fade" id="unresolvable-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form method="POST" action="/documents/update/processed" id="ActionForm" name="ActionForm">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                    <input type="hidden" name="status" value="unresolved">
                    <div class="modal-header card-head">
                        <h4 class="modal-title" id="unresolvable-modal-title">Unresolve Ticket</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <div class="form-group  col-md-12">
                                <label>Work Done:<span class="require">*</span></label>
                                <textarea class="workDone" name="work_done"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Ok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--  Assessment Log admin -->
    <div class="modal" tabindex="-1" id="mdl-timeline" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #e8e8ff">
                    <h5 class="modal-title">Status Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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
                                    <span
                                        class="bg-primary">{{ date('F j, Y', strtotime($log->created_at)) }}</span>
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
                                            href="#">{{ $log->assignedby ? $log->assignedby->staff->fullName : '' }}</a>
                                    </h3>
                                    <div class="timeline-body">
                                        <label>Ticket Status:&nbsp;&nbsp;</label>{{ $log->status }}<br>
                                        {{-- @if ($log->office_id != '')
                                        <label>Assigned to:&nbsp;&nbsp;</label> {{$log->getOffice ? $log->getOffice->name : '' }}<br> 
                           
                                        @endif --}}

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

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!--resolved MODAL -->
    <div class="modal fade" id="resolved-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 50%;">
            <div class="modal-content">
                <form method="POST" action="/documents/update/processed" id="ActionForm" name="ActionForm">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                    <input type="hidden" name="status" value="resolved">
                    <div class="modal-header card-head">
                        <h4 class="modal-title" id="unresolvable-modal-title">Resolved</h4>
                    </div>
                    <div class="modal-body">
                        <span><label class="m-0 col-md-12"></label></span>
                        <div class="form-group">
                            <div class="form-group  col-md-12">
                                <label>Work Done:<span class="require">*</span></label>
                                <textarea class="workDone" name="work_done"></textarea>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Ok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.callDetails').summernote('disable');
            $('.workDone').summernote();
            $('.feedbacku').summernote('disable');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Shows Datatable of Office Model
            let id = $('#id').val();

            $('#user-offices').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('ticket/show-assign') }}/" + id,
                // ajax: "{{ url('/offices') }}",
                columns: [{
                        data: 'office',
                        name: 'office'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                order: [
                    [0, 'asc']
                ],
                paging: false,
                lengthChange: false,
                searching: false,
                autoWidth: false,
                responsive: true,
                info: false,
                sorting: false
            });

            $('#assigned-offices-dt').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/documents/update/assigned') }}/" + id,

                columns: [{
                        data: 'office',
                        name: 'office'
                    },
                    {
                        data: 'assignedBy',
                        name: 'Assigned By'
                    },
                    {
                        data: 'assignedAt',
                        name: 'Assigned At'
                    },
                    {
                        data: 'status',
                        name: 'Status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                paging: false,
                lengthChange: false,
                searching: false,
                autoWidth: false,
                responsive: true,
                info: false,
                sorting: false
            });

        });

        // CANCEL TICKET
        $('#cancel-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            swal.fire({
                html: '<h6>Loading... Please wait</h6>',
                onRender: function() {
                    $('.swal2-content').prepend(sweet_loader);
                },
                showConfirmButton: false
            });

            $.ajax({
                type: 'POST',
                url: "{{ url('/documents/cancel') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (res) => {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: res.success
                        })
                        location.reload();

                        $("#cancel-modal").modal('hide');
                        $("#cancel-btn").attr("disabled", false);
                        $("#cancel-form").trigger('reset');

                    } else {
                        swal.fire({
                            icon: 'error',
                            html: res.errors
                        });

                    }

                },

                error: function(data) {
                    console.log(data);
                }
            });
        });

        function addUserOffice() {
            var ticket_id = $('#ticket_id').val();
            var office_id = $('#office_id').val();
            if (!office_id) {
                swal.fire({
                    icon: 'error',
                    html: '<h5>No Office selected!</h5>'
                });
                return;
            }

            $.ajax({
                type: 'POST',
                url: '{{ url('/documents/assign/add') }}',
                data: {
                    ticket_id: ticket_id,
                    office_id: office_id,
                },
                success: function(data) {
                    if (data.success) {
                        $('#office_id').val('');
                        var oTable = $('#user-offices').dataTable();
                        oTable.fnDraw(false);

                        var assignedTable = $('#assigned-offices-dt').dataTable();
                        assignedTable.fnDraw(false);
                    } else {
                        swal.fire({
                            icon: 'error',
                            html: '<h5>Office already designated!</h5>'
                        });
                    }
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
        //remove user
        function removeUserOffice($office_id) {
            var id = $office_id;
            $.ajax({
                type: 'DELETE',
                url: '{{ url('/documents/update/remove/') }}/' + id,
                dataType: 'json',
                success: function(res) {
                    var oTable = $('#user-offices').dataTable();
                    oTable.fnDraw(false);
                }
            });
        }

        //show modal timeline
        function showLog(office_id) {
            office_id = id

            $.ajax({
                type: "POST",
                url: "{{ url('/documents/update/assigned') }}",
                data: {
                    id: id
                },
                "token": "{{ csrf_token() }}",
                dataType: 'json',
                success: function(res) {
                    $('#mdl-timeline-office-title').html("View Logs");
                    $('#mdl-timeline-office').modal('show');
                    //     $('#role_id').val(res.id);
                    //     $('#name').val(res.name);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    </script>


@endsection
