@extends('layouts.app',[
'page' => 'Update Ticket',
'title' => ''
])
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4 class="h4" style="padding-left: 5px">Update Ticket</h4>
        <div>
            <button type="button" class="btn btn-block btn-secondary" style="float: right"
            onclick="location.href='/documents/view/{{ $ticket->id }}'"><i
                    class="fas fa-chevron-left"></i>&nbsp;Back</button>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <form  action="/documents/update" id="ticket-form" method="POST">
                @csrf
                <div class="card card-default">
                    <div class="card-header card-head">
                        <h3 class="card-title"><strong>Caller Information</strong></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" value="{{ $ticket->id }}" name="id" class="form-control" />
                            <div class="form-group col-md-4">
                                <p>Caller:<span class="require">*</span></p>
                                <p>{{ $caller->fullName }}</p>
                                <br>
                                <p>Address:</p>
                                <p>{{ $caller->address }}</p>
                            </div>
                           
                            <div class="form-group  col-md-4">
                                <p>Contact No<span class="require">*</span></p>
                                <p>{{ $caller->contact_no }}</p>
                                {{-- <input type="text" value="{{ $caller->contact_no }}" name="contact_no" class="form-control" /> --}}
                                <br>
                                <p>Email:</p>
                                <p>{{ $caller->email }}</p>
                                {{-- <input type="text" value="{{ $caller->email }}" name="email" class="form-control"> --}}
                            </div>

                            <div class="form-group col-md-4">
                                <p>Call Type<span class="require">*</span></p>
                                <select class="form-control" name="call_type_id">
                                    <option selected value="{{$ticket->callerType->id}}">{{$ticket->callerType->name}}</option>
                                @foreach ($caller_types as $ct)
                                @unless ($ticket->call_type_id == $ct->id)
                                    <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                                @endunless
                                @endforeach


{{-- 
                                <option selected value="{{$account->getRole->id}}">{{$account->getRole->name}}</option>
                                @foreach ($roles as $role)
                                  @unless ($account->role_id == $role->id)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                  @endunless
                                @endforeach
 --}}

                                </select>
                                <br>
                                <p>Call Status</p>
                                <p>{{$ticket->status}}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-default">
                    <div class="card-header card-head">
                        <h3 class="card-title"><strong>Ticket</strong></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group  col-md-12">
                                <p>Call Details<span class="require">*</span></p>
                                <textarea name="call_details" class="callDetails">{{$ticket->call_details}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('.callDetails').summernote();
        });
    </script>
@endsection
