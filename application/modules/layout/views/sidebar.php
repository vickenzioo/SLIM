<?php
    $role_id = (int) $this->session->userdata('role_id');
    $raw_uri = $this->uri->segment(1);
    $uri     = !empty($raw_uri) ? strtolower($raw_uri) : 'home';

    $apps_group  = [
        'application_type', 'database', 'operating_software', 
        'operational_day', 'operational_hour', 'network','category', 
        'deployment', 'deployment_model', 'deployment_site', 'server_type'
    ];
    $admin_group = ['user_role', 'history', 'holiday'];
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url('home') ?>" class="brand-link" onclick="sessionStorage.removeItem('portfolioTableScrollLeft');">
       <svg width="120" height="40" viewBox="0 0 240 80" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="textGradientSide" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" /> 
                    <stop offset="100%" style="stop-color:#dcdcdc;stop-opacity:1" />
                </linearGradient>
                <linearGradient id="goldGradientSide" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:#fbc531;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#e1b12c;stop-opacity:1" />
                </linearGradient>
            </defs>

            <text x="50%" y="55" font-family="'Montserrat', sans-serif" font-weight="900" font-style="italic" font-size="45" fill="url(#textGradientSide)" text-anchor="middle" letter-spacing="-2">SLIM</text>
            <circle cx="195" cy="55" r="6" fill="#fbc531" />
            <path d="M 40 70 L 200 70" stroke="url(#goldGradientSide)" stroke-width="6" stroke-linecap="round" />
        </svg>
    </a>


    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <?php if ($role_id === 1): ?>
                <li class="nav-item">
                  <a href="<?= base_url('home') ?>" class="nav-link <?= ($uri == '' || $uri == 'home' || $uri == 'home_detail') ? 'active' : '' ?>" onclick="sessionStorage.removeItem('portfolioTableScrollLeft');">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Home</p>
                  </a>
                </li>

                <li class="nav-item <?= (in_array($uri, $apps_group)) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($uri, $apps_group)) ? 'active' : '' ?>">
                      <i class="nav-icon fas fa-cubes"></i>
                      <p>Apps <i class="right fas fa-angle-left"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('application_type')?>" class="nav-link <?= ($uri == 'application_type') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Application Type</p>
                            </a>
                          </li>

                        <li class="nav-item">
                            <a href="<?= base_url('category')?>" class="nav-link <?= ($uri == 'category') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Category</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('database') ?>" class="nav-link <?= ($uri == 'database') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Database</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('deployment')?>" class="nav-link <?= ($uri == 'deployment') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Deployment</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('deployment_model')?>" class="nav-link <?= ($uri == 'deployment_model') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Deployment Provider</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('deployment_site')?>" class="nav-link <?= ($uri == 'deployment_site') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Deployment Site</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('network')?>" class="nav-link <?= ($uri == 'network') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Network</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('operating_software')?>" class="nav-link <?= ($uri == 'operating_software') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Operating Software</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('operational_hour')?>" class="nav-link <?= ($uri == 'operational_hour') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Operational Hour</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('operational_day')?>" class="nav-link <?= ($uri == 'operational_day') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Operational Day</p>
                            </a>
                        </li>  

                        <li class="nav-item">
                            <a href="<?= base_url('server_type')?>" class="nav-link <?= ($uri == 'server_type') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Server Type</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item <?= (in_array($uri, $admin_group)) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($uri, $admin_group)) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Administration<i class="right fas fa-angle-left"></i></p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="<?= base_url('history') ?>" class="nav-link <?= ($uri == 'history') ? 'active' : '' ?>">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Audit Trail</p>
                          </a>
                        </li>

                        <li class="nav-item">
                          <a href="<?= base_url('holiday') ?>" class="nav-link <?= ($uri == 'holiday') ? 'active' : '' ?>">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Holiday</p>
                          </a>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('user_role') ?>" class="nav-link <?= ($uri == 'user_role') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User Role</p>
                            </a>
                        </li>
                    </ul>
                </li>

            <?php else: ?>

                <li class="nav-item">
                  <a href="<?= base_url('home') ?>" class="nav-link <?= ($uri == '' || $uri == 'home' || $uri == 'home_detail') ? 'active' : '' ?>" onclick="sessionStorage.removeItem('portfolioTableScrollLeft');">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Home</p>
                  </a>
                </li>

            <?php endif; ?>
        </ul>
      </nav>
    </div>
</aside>

