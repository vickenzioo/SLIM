<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="border-bottom: 3px solid var(--theme-yellow-primary);">
    <ul class="navbar-nav align-items-center">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="font-size: 1.0rem;">
          <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= base_url('portofolio') ?>" class="nav-link breadcrumb-home" style="font-size: 14px; font-weight: 600; margin-left: 5px; display: flex; align-items: center; height: 40px;">
          Home
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
      <li class="nav-item mr-2">
         <a class="nav-link" href="#" role="button" id="darkModeBtn" title="Ganti Mode" style="font-size: 1.0rem;">
           <i class="fas fa-moon"></i>
         </a>
      </li>
      
      <li class="nav-item dropdown">
        <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" style="padding: 0.25rem 0.5rem;">
          <div class="user-icon-container mr-2">
            <i class="far fa-user fa-lg" style="font-size: 1.0rem;"></i>
          </div>

          <div class="user-info-content d-flex flex-column justify-content-center border-left pl-2" style="line-height: 1.3;">
            <span class="font-weight-bold" style="font-size: 13px; display: block;">
              <?= $this->session->userdata('username') ? $this->session->userdata('username') : 'User'; ?>
            </span>
            <span class="text-muted" style="font-size: 9px; font-weight: 500; letter-spacing: 0.3px;">
              <?= $this->session->userdata('role') ? $this->session->userdata('role') : ''; ?>
            </span>
          </div>

          <div class="ml-3">
            <i class="fas fa-caret-down text-secondary"></i>
          </div>
        </a>
        
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="<?= base_url('auth/logout') ?>" class="dropdown-item text-danger" id="logoutLink">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
</nav>