@extends('layouts.app',[
    'page' => 'About Us',
    'title' => 'About Us'
])

@section('content')
<div class="container custom-container mt-4">
    <div class="card">
        <!-- TABS -->
        <div class="card card-primary card-tabs" style="margin: 10px">
            <div class="card-header p-0 pt-1 bg-dblue">
                <ul class="nav nav-tabs" id="settings-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="isojourney-tab" data-toggle="pill" href="#isojourney" role="tab" aria-controls="isojourney" aria-selected="true">ISO Journey</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="missionvision-tab" data-toggle="pill" href="#missionvision" role="tab" aria-controls="missionvision" aria-selected="false">Mission & Vision</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="objectives-tab" data-toggle="pill" href="#objectives" role="tab" aria-controls="objectives" aria-selected="false">Quality Objectives</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="policy-tab" data-toggle="pill" href="#policy" role="tab" aria-controls="policy" aria-selected="false">Quality Policy</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- TABS CONTENT -->
        <div class="card-body">
            <div class="tab-content" id="set-content">
                <div class="tab-pane fade show active" id="isojourney" role="tabpanel" aria-labelledby="isojourney-tab">
                    @include('pages.aboutus.isojourney')
                </div>
                <div class="tab-pane fade" id="missionvision" role="tabpanel" aria-labelledby="missionvision-tab">
                    @include('pages.aboutus.missionvision')
                </div>
                <div class="tab-pane fade" id="objectives" role="tabpanel" aria-labelledby="objectives-tab">
                    @include('pages.aboutus.objectives')
                </div>
                <div class="tab-pane fade" id="policy" role="tabpanel" aria-labelledby="policy-tab">
                    @include('pages.aboutus.policy')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
