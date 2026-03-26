<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="border-bottom: 3px solid var(--theme-yellow-primary); position: sticky; top: 0; z-index: 1034;">
    <ul class="navbar-nav align-items-center">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="font-size: 1.0rem;">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?= base_url('home') ?>" class="nav-link breadcrumb-home" style="font-size: 14px; font-weight: 600; margin-left: 5px; display: flex; align-items: center; height: 40px;" onclick="sessionStorage.removeItem('portfolioTableScrollLeft');">
        <img src="<?= base_url('assets/img/icon_cimb_niaga_light.svg') ?>" alt="CIMB Niaga" class="logo-light" style="width: 135px; height: 20px; margin-right: 8px;">
        
        <img src="<?= base_url('assets/img/icon_cimb_niaga_dark.svg') ?>" alt="CIMB Niaga" class="logo-dark" style="width: 135px; height: 20px; margin-right: 8px;">
      </a>
    </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
    <li class="nav-item" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); z-index: 10;">
      <div id="darkModeBtn" class="neumorphic-toggle" role="button" title="Ganti Mode">
        
        <span class="toggle-text text-light-mode"></span>
        <span class="toggle-text text-dark-mode"></span>
        
        <div class="toggle-thumb">
          <i class="fas fa-sun icon-sun"></i>
          <i class="fas fa-moon icon-moon"></i>
        </div>
        
      </div>
    </li>
      
    <li class="nav-item d-flex align-items-center pr-3">
      <div class="d-flex align-items-center border-right pr-3 mr-2" style="padding: 0.25rem 0;">
        <div class="user-icon-container mr-2">
          <img src="<?= base_url('assets/img/profile_picture.svg') ?>" alt="Profile Picture" style="width: 24px; height: 24px; border-radius: 50%;">
        </div>

        <div class="user-info-content d-flex flex-column justify-content-center" style="line-height: 1.3;">
          <span class="font-weight-bold" style="font-size: 13px; display: block;">
            <?= $this->session->userdata('username') ? $this->session->userdata('username') : 'User'; ?>
          </span>
          <span class="text-muted" style="font-size: 9px; font-weight: 500; letter-spacing: 0.3px;">
            <?= $this->session->userdata('role') ? $this->session->userdata('role') : ''; ?>
          </span>
        </div>
      </div>
      
      <a href="<?= base_url('auth/logout') ?>" class="btn btn-sm text-danger border-0" id="logoutLink" style="font-weight: 600; font-size: 14px;" onclick="sessionStorage.removeItem('portfolioTableScrollLeft');">
        <i class="fas fa-sign-out-alt mr-1"></i> Logout
      </a>
  </li>
    </ul>
</nav>