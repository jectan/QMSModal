@extends('pages.feedback.index')
@section('content')
    <div class="sec">
        <div class="background-image">
            <img class="image" src="/img/zamboanga.jpg" alt="">
        </div>
        <div class="navigator">
            <img src="/img/logo.png" alt="" style="height: 50px;position: absolute;">
            <div>
                <ul class="nav justify-content-end" style="margin: 10px">
                    <li class="nav-item">
                        <a class="active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="" href="/about-us">About Us</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="head">
            <div class="heading">
                <ul class="cards">
                    <li>
                        <a data-toggle="modal" data-target="#feedback-modal" class="card1">
                            <div style="width: 100%;height: 100%;">
                                <img src="/img/feedback-tittle.jpg" class="card__image" alt="" />
                            </div>
                            <div class="card__overlay">
                                <div class="card__header">
                                    <svg class="card__arc" xmlns=>
                                        <path />
                                    </svg>
                                    <div class="card__header-text">
                                        <h3 class="card__title">FEEDBACK</h3>
                                    </div>
                                </div>
                                <p class="card__description" style="text-align: center">Click here to Rate your Ticket</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a data-toggle="modal" data-target="#track-modal" class="card1">
                            <img src="/img/track-tittle.jpg" class="card__image" alt="" />
                            <div class="card__overlay">
                                <div class="card__header">
                                    <svg class="card__arc" xmlns="http://www.w3.org/2000/svg">
                                        <path />
                                    </svg>
                                    <div class="card__header-text">
                                        <h3 class="card__title">TICKET TRACKER</h3>
                                    </div>
                                </div>
                                <p class="card__description" style="text-align: center">Click here to Track your Ticket</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="/e-sumbong-form" class="card1">
                            <img src="/img/complaint-tittle.jpg" class="card__image" alt="" />
                            <div class="card__overlay">
                                <div class="card__header">
                                    <svg class="card__arc" xmlns="http://www.w3.org/2000/svg">
                                        <path />
                                    </svg>
                                    <div class="card__header-text">
                                        <h3 class="card__title">COMPLAINT</h3>
                                    </div>
                                </div>
                                <p class="card__description" style="text-align: center">Click here to file an e-Sumbong Form
                                </p>
                            </div>
                        </a>
                    </li>
                </ul>

            </div>
            <div class="heads">
                <img src="/img/header.png" alt="" style="width: 90%;">

            </div>
        </div>
    </div>

   
    {{-- feedback modal --}}

    <div class="modal fade" id="feedback-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="trackfeedback-form" name="trackfeedback-form" class="form-horizontal" action="/feedback" method="post">
                    @csrf
                    <div class="modal-header card-head">
                        <h4 class="modal-title" id="feedback-modal-title">Search Ticket</h4>
                    </div>
                    <div class="modal-body">
                        <div style="margin: 20%;">
                            <div class="wrap">
                                <div class="search">
                                    <input type="text" class="searchTerm" placeholder="Enter Ticket No." id="trackfeedback_id" name="trackfeedback_ticket">
                                    <button type="submit"  class="searchButton" >
                                        <i class="fas fa-search" style="color: white;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                                class="fas fa-chevron-left"></i>&nbsp;Back</button>
                    </div>


                </form>
            </div>
        </div>
    </div>

    {{-- Track Search modal --}}

    <div class="modal fade" id="track-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="track-form" name="track-form" class="form-horizontal" action="/home/track-view" method="post">
                    @csrf
                    <div class="modal-header card-head">
                        <h4 class="modal-title" id="track-modal-title">Search Ticket</h4>
                    </div>
                    <div class="modal-body">
                        <div style="margin: 20%;">
                            <div class="wrap">
                                <div class="search">
                                    <input type="text" class="searchTerm" placeholder="Enter Ticket No." id="track_id"
                                        name="track_ticket">
                                    <button type="submit" class="searchButton">
                                        <i class="fas fa-search" style="color: white;"></i>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                                class="fas fa-chevron-left"></i>&nbsp;Back</button>
                    </div>
            </div>
        </div>
        </form>
    </div>
    </div>
@endsection
