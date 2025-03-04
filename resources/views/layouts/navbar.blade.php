<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="z-index: 5">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">

     <!-- User Account: style can be found in dropdown.less -->
     <li class="nav-item dropdown user-menu" style="margin-right: -4%"> 
          <a href="/" class="nav-link" data-toggle="dropdown">
            <span class="hidden-xs">Welcome, {{ Auth::user()->staff->firstname ?? ''}}</span>
            <img src="/img/user.png" class="user-image" alt="User Image" style="width: 25px; height: 25px">
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">

              <img src="/img/smiley.gif" alt="User Image" style="width: 45px; height: 45px; margin-top:5px"><br>
              <div class="pull-center">
                <p><b>{{ Auth::user()->staff->firstname ?? '' }} {{ Auth::user()->staff->middlename ?? ''}} {{ Auth::user()->staff->lastname ?? ''}}</b>
                <br>{{ Auth::user()->staff->job_title ?? ''}}</p>
                <a href="/logout" class="btn btn-default btn-flat">Log out</a>
              </div>
            </li>
          </ul>
      </li>
      <li class="nav-item" style="margin-right: -4%">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
          </a>
      </li>
  </ul>
</nav>