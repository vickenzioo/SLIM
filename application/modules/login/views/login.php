<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLIM | Login</title>
    
    <link rel="stylesheet" href="<?= base_url('assets/dist/css/slim/login.css'); ?>">
    <?php $this->load->view('layout/head_links'); ?>
</head>
<body>

    <div class="bg-illustration-shape shape-1"></div>
    <div class="bg-illustration-shape shape-2"></div>

    <div class="login-split-wrapper">
        <div class="mascot-side">
            <div class="interactive-mascot-wrapper">
                <img src="<?= base_url('assets/img/slim_icon.svg') ?>" alt="Maskot Gajah Lucu" class="base-mascot">
                <div class="eye-socket js-eye">
                    <div class="pupil"></div>
                </div>
            </div>
            <div class="legal-text-area">
                <div class="footer-legal-text">
                    <p class="mb-3">
                        This information system is to be used by authorized users only. Your use of this system may be monitored, recorded and audited. 
                        By using this system, all users acknowledge notice of, and agree to comply with <b><u>PT. BANK CIMB NIAGA Tbk</u></b>.
                    </p>
                    <p class="mb-0">
                        Unauthorized or improper use of this system may result in administrative disciplinary action,
                        civil charges/criminal penalties and/or other sanctions.
                    </p>
                </div>
            </div>
        </div>

        <div class="form-side">
            <div class="card login-card">
                <div class="card-body">
                    <div class="logo-container">
                        <svg class="slim-logo-svg" viewBox="0 0 240 80" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="textGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#2f3542;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#57606f;stop-opacity:1" />
                                </linearGradient>
                                <linearGradient id="goldGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#fbc531;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#e1b12c;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <text x="50%" y="55" font-family="'Montserrat', sans-serif" font-weight="900" font-style="italic" font-size="65" fill="url(#textGradient)" text-anchor="middle" letter-spacing="-2">SLIM</text>
                            <circle cx="195" cy="55" r="6" fill="#fbc531" />
                            <path d="M 40 70 L 200 70" stroke="url(#goldGradient)" stroke-width="6" stroke-linecap="round" />
                        </svg>
                        <div class="login-subtitle">Selamat datang! Silahkan login untuk lanjut.</div>
                    </div>

                    
                    <form action="<?= base_url('auth/process_login'); ?>" method="post" autocomplete="off">
    
                        <div class="form-label-group text-left">
                            <input type="email" class="form-control" id="emailInput" name="email" placeholder="Email" autocomplete="off"
                                value="<?= $this->session->flashdata('old_email') ? $this->session->flashdata('old_email') : set_value('email'); ?>">
                            <label for="emailInput">Email</label>
                            <small class="text-danger"><?= form_error('email'); ?></small>
                        </div>

                        <div class="form-label-group text-left position-relative">
                            <input type="password" 
                               class="form-control <?= (form_error('password') || $this->session->flashdata('error_password')) ? 'is-invalid' : ''; ?>" 
                               id="passwordInput" name="password" placeholder="Password" autocomplete="new-password"
                               value="<?= $this->session->flashdata('old_password'); ?>">
                            
                            <label for="passwordInput">Password</label>
                            
                            <button type="button" class="password-toggle-btn" id="togglePassword">
                                <i class="fa-regular fa-eye" id="eyeIcon"></i>
                            </button>

                            <div class="d-flex justify-content-end">
                                <small class="text-danger font-weight-bold mt-2">
                                    <?= form_error('password'); ?>
                                    <?= $this->session->flashdata('error_password'); ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-theme-yellow btn-block shadow-sm">
                                <i class="fa-solid fa-lock"></i> Masuk Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="position: fixed; bottom: 10px; width: 100%; text-align: center; z-index: 5; pointer-events: none;">
        <div class="footer-copyright">
            &copy; 2026 PT Bank CIMB Niaga Tbk.
        </div>
    </div>

    <div id="loadingOverlay">
        <svg class="spinner-svg" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#spinner-gF00)">
                <circle cx="4" cy="12" r="3">
                    <animate attributeName="cx" calcMode="spline" dur="0.75s" values="4;9;4" keySplines=".56,.52,.17,.98;.56,.52,.17,.98" repeatCount="indefinite"/>
                    <animate attributeName="r" calcMode="spline" dur="0.75s" values="3;8;3" keySplines=".56,.52,.17,.98;.56,.52,.17,.98" repeatCount="indefinite"/>
                </circle>
                <circle cx="15" cy="12" r="8">
                    <animate attributeName="cx" calcMode="spline" dur="0.75s" values="15;20;15" keySplines=".56,.52,.17,.98;.56,.52,.17,.98" repeatCount="indefinite"/>
                    <animate attributeName="r" calcMode="spline" dur="0.75s" values="8;3;8" keySplines=".56,.52,.17,.98;.56,.52,.17,.98" repeatCount="indefinite"/>
                </circle>
            </g>
        </svg>
        <div class="loading-text">Logging in...</div>
    </div>

    <script src="<?= base_url('assets/dist/js/slim/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/dist/js/slim/jquery-3.6.0.min.js') ?>"></script>

    <script>
        /* JavaScript tetap sama seperti aslinya */
        const eyes = document.querySelectorAll('.js-eye');
        document.addEventListener('mousemove', (e) => {
            eyes.forEach(eye => {
                let rect = eye.getBoundingClientRect();
                let eyeCenterX = rect.left + rect.width / 2;
                let eyeCenterY = rect.top + rect.height / 2;
                let mouseX = e.clientX;
                let mouseY = e.clientY;
                let radian = Math.atan2(mouseY - eyeCenterY, mouseX - eyeCenterX);
                let rotationDegrees = radian * (180 / Math.PI);
                eye.style.transform = `rotate(${rotationDegrees}deg)`;
            });
        });

        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        const loginForm = document.querySelector('form');
        const overlay = document.getElementById('loadingOverlay');
        if(loginForm){
            loginForm.addEventListener('submit', function(e) {
                overlay.style.display = 'flex';
            });
        }
        window.addEventListener('pageshow', function(event) {
            // Jika event.persisted = true, berarti halaman dimuat dari Cache (tombol Back browser)
            if (event.persisted) {
                // Sembunyikan div loading overlay-mu (sesuaikan ID-nya jika berbeda)
                var overlay = document.getElementById('loadingOverlay'); // Ganti dengan ID loading-mu
                if (overlay) {
                    overlay.style.display = 'none';
                }
                
                // Opsional: Kosongkan password demi keamanan
                var passInput = document.getElementById('passwordInput');
                if (passInput) {
                    passInput.value = '';
                }
            }
        });
    </script>
</body>
</html>