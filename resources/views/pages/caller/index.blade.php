@extends('layouts.app',[
    'page' => 'Callers',
    'title' => ''
])

@section('content')
<section class="content">
  <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h4 class="h2" style="padding-left: 5px">List of Callers</h4>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <div class="container-login100-form-btn">
                            <div class="wrap-login100-form-btn">
                                <div class="login100-form-bgbtn"></div>
                                <button class="login100-form-btn" onclick="location.href='{{ URL('/caller/create') }}'"><i class="fa fa-plus pr-2"></i>Caller</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-primary card-tabs" style="margin: 10px">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="settings-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="named-callers" data-toggle="pill" href="#named-callers-content" role="tab" aria-controls="named-callers-content" aria-selected="true">Named Callers</a>
                  </li>
                  {{-- <li class="nav-item">
                    <a class="nav-link " id="anon-callers" data-toggle="pill" href="#anon-callers-content" role="tab" aria-controls="types-content" aria-selected="false">Anonymous Callers</a>
                  </li> --}}
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="set-content">
                  <div class="tab-pane fade show active" id="named-callers-content" role="tabpanel" aria-labelledby="callers-tab">
                    @include('pages.caller.named-caller')
                  </div>
                  {{-- <div class="tab-pane fade" id="anon-callers-content" role="tabpanel" aria-labelledby="anon-callers-tab">
                    @include('pages.caller.anonymous-caller')
                  </div> --}}
                </div>
              </div>
              <!-- /.card -->
            </div>
    </div>
</section>


@endsection