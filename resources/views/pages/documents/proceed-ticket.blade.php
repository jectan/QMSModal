@extends('layouts.app',[
'page' => 'Create Ticket',
'title' => ''
])
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h4 class="h4" style="padding-left: 5px">Create Ticket</h4>
        <div>
            <button type="button" class="btn btn-block btn-secondary" style="float: right"
                onclick="location.href='/documents'"><i
                    class="fas fa-chevron-left"></i>&nbsp;Back</button>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <form  action="/documents/store" id="ticket-form" method="POST">
                @csrf
                <div class="card card-default">
                    <div class="card-header card-head">
                        <h3 class="card-title"><strong>Caller Information</strong></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" value="{{ $caller->id }}" name="caller_id" class="form-control" />
                            <div class="form-group col-md-4">
                                <p>Caller:<span class="require">*</span></p>
                                <p>{{ $caller->fullName }}</p>
                                <br>
                                <p>Address:</p>
                                <p>{{ $caller->address }}</p>
                            </div>
                           
                            <div class="form-group  col-md-4">
                                <p>Contact No<span class="require">*</span></p>
                                <input type="text" value="{{ $caller->contact_no }}" name="contact_no" class="form-control" />
                                <br>
                                <p>Email:</p>
                                <input type="text" value="{{ $caller->email }}" name="email" class="form-control">
                            </div>

                            <div class="form-group col-md-4">
                                <p>Call Type<span class="require">*</span></p>
                                <select class="form-control" name="call_type_id">
                                @foreach ($caller_types as $ct)
                                    <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                                @endforeach
                                </select>
                                <br>
                                <p>Call Status<span class="require">*</span></p>
                                <select class="form-control" name="call_status">
                                    <option value="Acted">Acted</option>
                                    <option value="Dropped Call">Dropped Call</option>
                                </select>
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
                                <textarea name="call_details" class="callDetails"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right">Save</button>
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
