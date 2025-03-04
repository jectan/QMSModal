@extends('pages.feedback.index')
@section('content')
    <div class="body1">
        <div class="body2">
            
            <div class="form-style-9">
                <div class="card" style="padding: 5%">
                    <div class="row justify-content-md-center" style="margin-bottom: 2%">
                        <div class="col-md-6" style="width: 100%">
                            <img class="img-fluid" src="/img/feedback-form.png" alt="">
                        </div>
                    </div>

                    <div class="row">
                        <span class="number" style="margin-bottom: 20px">1</span>
                        <h4>Feedback</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <label>Ticket no.</label>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="field-style form-control row-margin" disabled=""
                                    style="background: white;" value="{{ $ticket->ticket_no }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <label>Ticket Status</label>
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="field-style form-control row-margin" disabled=""
                                    style="background: white;" value="{{ $ticket->status }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <label>Ticket Details:</label>
                    </div>
                    <div class="field-style row">
                        <div class="col-sm-12" style="word-break: break-all;">
                            {!! $ticket->call_details !!}
                        </div>

                    </div>
                    <div style="text-align: center; margin-top: 5%;">
                        <button class="view" data-toggle="modal" data-target="#timeline-modal"><i
                                class="fas fa-eye"></i>&nbsp;View
                            Logs</button>
                    </div>


                </div>

                <div class="card">
                    <div style="text-align: center;margin-top: 5%;">
                        <h1>How did we do?</h1>
                        <p style="word-break: break-all;" class="margin">Please let us know how your food delivery was. It will really help
                            us to keep improving our
                            service!</p>
                    </div>
                    <div class="star-rating">
                        <div class="star-input">
                    
                            <input type="radio" name="rating" id="rating-5" onclick="setValue(5)">
                            <label for="rating-5" class="fas fa-star"></label>
                            <input type="radio" name="rating" id="rating-4" onclick="setValue(4)">
                            <label for="rating-4" class="fas fa-star"></label>
                            <input type="radio" name="rating" id="rating-3" onclick="setValue(3)">
                            <label for="rating-3" class="fas fa-star"></label>
                            <input type="radio" name="rating" id="rating-2" onclick="setValue(2)">
                            <label for="rating-2" class="fas fa-star"></label>
                            <input type="radio" name="rating" id="rating-1" onclick="setValue(1)">
                            <label for="rating-1" class="fas fa-star"></label>
                      

                            <!-- Rating Submit Form -->
                            <form action="/feedback/save" method="POST">
                                @csrf
                                <div class="card" style="padding: 5%">
                                    <div class="row">
                                        <label>Feedback</label>
                                    </div>
                                    <div class="row">
                                        <textarea type="text" name="feedback_details"
                                            class="field-style form-control row-margin"></textarea>
                                    </div>
                                    <span class="rating-reaction"></span>
                                    <input type="hidden" name="rate" id="rate">
                                    <input type="hidden" value="{{ $ticket->id }}" name="id" class="form-control" />
                                    <button type="submit" class="submit-rating">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>

    {{-- timeline modal --}}
    <div class="modal fade" id="timeline-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header card-head">
                    <h4>Ticket Timeline Logs</h4>
                </div>
                <div class="modal-body" style="overflow: auto; padding: 5%;">
                
                    <div class="timeline" style="height: 450px; overflow-x: auto;">
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
                                        {{ $log->status }}<br>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                        class="fas fa-chevron-left"></i>&nbsp;Back</button>

                </div>
              
            </div>
        </div>
    </div>

    

@endsection
