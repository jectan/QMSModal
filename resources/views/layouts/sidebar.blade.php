<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="sidebar">
      <a href="/dashboard" class="brand-link">
          <img src="/img/logo1.png" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
          <span class="brand-text" style="font-size: 10px;word-wrap: break-word; ">Document Management System</span><br>
      </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="/dashboard" class="nav-link {{ $page == 'dashboard' ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/documents" class="nav-link {{ $page == 'documents' ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
              Manage Documents
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/masterlist" class="nav-link {{ $page == 'masterlist' ? 'active' : '' }}">
              <i class="nav-icon fa fa-th-list" aria-hidden="true"></i>
              <p>
              Masterlist
              </p>
            </a>
          </li>
     
          @if(Auth::user()->role->id==1) 
          
          {{-- {{Auth::user()->roles[0]['id']}} --}}
          <li class="nav-item">
            <a href="/accounts" class="nav-link {{ $page == 'Account' ? 'active' : '' }}">
              <i class="fas fa-user nav-icon"></i>
              <p>Accounts</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Manage Libraries
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/divisions" class="nav-link {{ $page == 'Division' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Divisions</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/units" class="nav-link {{ $page == 'Unit' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Units</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/docTypes" class="nav-link {{ $page == 'DocType' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Document Type</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/requestTypes" class="nav-link {{ $page == 'RequestType' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Request Type</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/roles" class="nav-link {{ $page == 'Role' ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>