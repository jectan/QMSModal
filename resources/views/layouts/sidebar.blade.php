<aside class="main-sidebar sidebar-dark-primary elevation-4 d-flex flex-column">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link">
        <img src="/img/logo1.png" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text" style="font-size: 10px;word-wrap: break-word;">Document Management System</span><br>
    </a>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-grow-1">
        
        <!-- Sidebar Menu (Top Section) -->
        <nav class="mt-2 flex-grow-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ $page == 'dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/documents" class="nav-link {{ $page == 'documents' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Manage Documents</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/masterlist" class="nav-link {{ $page == 'masterlist' ? 'active' : '' }}">
                        <i class="nav-icon fa fa-th-list" aria-hidden="true"></i>
                        <p>Masterlist</p>
                    </a>
                </li>

                @if(Auth::user()->role->id==1)
                    <li class="nav-item">
                        <a href="/accounts" class="nav-link {{ $page == 'Account' ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon"></i>
                            <p>Accounts</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
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

        <!-- About Us (Bottom Section) -->
        @if(Auth::user())
            <ul class="nav nav-pills nav-sidebar flex-column mt-auto">
                <li class="nav-item">
                    <a href="/aboutus" class="nav-link {{ $page == 'About' ? 'active' : '' }}">
                        <i class="material-icons nav-icon align-middle">info</i>
                        <p>About Us</p>
                    </a>
                </li>
            </ul>
        @endif
    </div>
</aside>
