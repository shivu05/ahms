<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Vedic Hospital Management System - Complete Ayurvedic Hospital Management Solution by Dharani Tech Solutions">
    <meta name="author" content="Dharani Tech Solutions">
    <title>VHMS | <?= $this->layout->title ?></title>
    <?php
    echo $this->scripts_include->includeCss();
    echo $this->scripts_include->preJs();
    ?>
    <style id="antiClickjack">
        body {
            display: none !important;
        }
    </style>
    <script type="text/javascript">
        if (self === top) {
            var antiClickjack = document.getElementById("antiClickjack");
            antiClickjack.parentNode.removeChild(antiClickjack);
        } else {
            top.location = self.location;
        }
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
            height: 100vh;
        }

        .modern-login-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* Left Side - Branding */
        .login-brand-section {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 50px;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .login-brand-section::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .login-brand-section {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .login-brand-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('<?php echo base_url('/assets/ayush_bg.png') ?>');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
            z-index: 1;
        }

        .brand-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .brand-logo-wrapper {
            margin-bottom: 20px;
            animation: fadeInDown 0.8s ease-out;
        }

        .brand-logo {
            width: 140px;
            height: 140px;
            background: white;
            border-radius: 20px;
            padding: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .brand-logo:hover {
            transform: translateY(-5px);
        }

        .brand-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
            animation: fadeInUp 0.8s ease-out 0.2s both;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .brand-subtitle {
            font-size: 15px;
            opacity: 0.95;
            line-height: 1.5;
            max-width: 480px;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .brand-features {
            margin-top: 35px;
            display: flex;
            gap: 25px;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .brand-feature {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            backdrop-filter: blur(10px);
        }

        .feature-text {
            font-size: 13px;
            opacity: 0.9;
            text-align: center;
            line-height: 1.3;
        }

        /* Right Side - Login Form */
        .login-form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 50px;
            background: #ffffff;
            position: relative;
            overflow-y: auto;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .login-form-section::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .login-form-section {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 450px;
            animation: fadeInRight 0.8s ease-out;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .login-subtitle {
            font-size: 15px;
            color: #718096;
        }

        .alert-message {
            background: #fff5f5;
            border-left: 4px solid #fc8181;
            color: #c53030;
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1.5;
            animation: shake 0.5s;
        }

        .alert-message:empty {
            display: none;
        }

        .form-group-modern {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label-modern {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 6px;
        }

        .form-control-modern {
            width: 100%;
            padding: 12px 14px 12px 44px;
            font-size: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: #f7fafc;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control-modern:disabled {
            background: #f1f1f1;
            cursor: not-allowed;
        }

        .form-icon {
            position: absolute;
            left: 14px;
            top: 36px;
            color: #a0aec0;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .form-control-modern:focus+.form-icon {
            color: #667eea;
        }

        select.form-control-modern {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23a0aec0' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

        select.form-control-modern option[disabled] {
            color: #fc8181;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-modern {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .checkbox-label {
            font-size: 13px;
            color: #4a5568;
            cursor: pointer;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            background: #cbd5e0;
            cursor: not-allowed;
            box-shadow: none;
        }

        .form-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            line-height: 1.5;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .modern-login-container {
                flex-direction: column;
            }

            .login-brand-section {
                flex: 0 0 300px;
                padding: 40px 30px;
            }

            .brand-logo {
                width: 120px;
                height: 120px;
            }

            .brand-title {
                font-size: 28px;
            }

            .brand-subtitle {
                font-size: 14px;
            }

            .brand-features {
                flex-direction: row;
                gap: 20px;
            }

            .login-form-section {
                padding: 40px 30px;
            }

            .login-form-wrapper {
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .login-brand-section {
                flex: 0 0 250px;
                padding: 30px 20px;
            }

            .brand-logo {
                width: 100px;
                height: 100px;
            }

            .brand-title {
                font-size: 24px;
            }

            .brand-features {
                gap: 15px;
            }

            .feature-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .login-form-section {
                padding: 30px 20px;
            }

            .login-title {
                font-size: 26px;
            }

            .form-control-modern {
                padding: 12px 14px 12px 44px;
                font-size: 14px;
            }

            .btn-login {
                padding: 14px;
                font-size: 15px;
            }
        }

        /* Loading State */
        .btn-login.loading {
            position: relative;
            color: transparent;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spinner 0.8s linear infinite;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 36px;
            cursor: pointer;
            color: #a0aec0;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }
    </style>
</head>

<body>
    <div class="modern-login-container">
        <!-- Left Side - Branding -->
        <div class="login-brand-section">
            <div class="brand-content">
                <div class="brand-logo-wrapper">
                    <img src="<?= base_url('assets/your_logo.png') ?>" alt="VHMS Logo" class="brand-logo" />
                </div>
                <h1 class="brand-title">VHMS</h1>
                <p class="brand-subtitle" style="margin-bottom: 8px;">
                    <strong>Vedic Hospital Management System</strong>
                </p>
                <p class="brand-subtitle" style="font-size: 12px; margin-bottom: 18px;">
                    An integrated software that handles different directions of ayurvedic hospital workflows.
                    It manages the smooth healthcare performance along with administrative, medical control.
                </p>
                <div class="brand-features" style="margin-top: 25px;">
                    <div class="brand-feature">
                        <div class="feature-icon">
                            <i class="fa fa-hospital-o"></i>
                        </div>
                        <div class="feature-text">Complete<br />Healthcare</div>
                    </div>
                    <div class="brand-feature">
                        <div class="feature-icon">
                            <i class="fa fa-shield"></i>
                        </div>
                        <div class="feature-text">Secure &<br />Reliable</div>
                    </div>
                    <div class="brand-feature">
                        <div class="feature-icon">
                            <i class="fa fa-leaf"></i>
                        </div>
                        <div class="feature-text">Ayurvedic<br />Focused</div>
                    </div>
                </div>
                <div
                    style="margin-top: 35px; font-size: 13px; opacity: 0.85; animation: fadeInUp 0.8s ease-out 0.8s both;">
                    <p style="margin-bottom: 6px; font-size: 12px;">Powered by</p>
                    <p style="font-size: 18px; font-weight: 600; margin-bottom: 4px;">Dharani Tech Solutions</p>
                    <p style="font-size: 12px; opacity: 0.9; line-height: 1.4;">We Build Smart Software to Power Your
                        Business Growth</p>
                    <a href="https://www.dharanitechsolutions.com/" target="_blank" style="color: white; text-decoration: none; opacity: 0.8; font-size: 11px; 
                              border-bottom: 1px solid rgba(255,255,255,0.3); padding-bottom: 2px;
                              transition: opacity 0.3s ease;" onmouseover="this.style.opacity='1'"
                        onmouseout="this.style.opacity='0.8'">
                        www.dharanitechsolutions.com
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-form-section">
            <div class="login-form-wrapper">
                <div class="login-header">
                    <h2 class="login-title">Welcome Back</h2>
                    <p class="login-subtitle">Please sign in to your account to continue</p>
                </div>

                <?php
                $error_msg = '';
                if ($this->session->flashdata('noty_msg') != '') {
                    $error_msg .= $this->session->flashdata('noty_msg');
                }
                $error_msg .= validation_errors();
                if (!empty($error_msg)) {
                    echo '<div class="alert-message">' . $error_msg . '</div>';
                }
                ?>

                <form class="login-form" action="<?php echo base_url('login/validate'); ?>" method="post" id="loginForm"
                    novalidate>
                    <div class="form-group-modern">
                        <label for="loginname" class="form-label-modern">Email Address</label>
                        <input class="form-control-modern" type="text" placeholder="Enter your email" autofocus
                            name="loginname" id="loginname" required autocomplete="username"
                            value="<?= set_value('loginname') ?>">
                        <span class="form-icon">
                            <i class="glyphicon glyphicon-envelope"></i>
                        </span>
                    </div>

                    <div class="form-group-modern">
                        <label for="password" class="form-label-modern">Password</label>
                        <input class="form-control-modern" type="password" placeholder="Enter your password"
                            name="password" id="password" required autocomplete="current-password">
                        <span class="form-icon">
                            <i class="glyphicon glyphicon-lock"></i>
                        </span>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="fa fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>

                    <div class="form-group-modern">
                        <label for="selection_year" class="form-label-modern">Select Year</label>
                        <select name="selection_year" id="selection_year" class="form-control-modern" required>
                            <option value="">Choose year</option>
                            <?php
                            if (!empty($accessConfig)) {
                                foreach ($accessConfig as $row) {
                                    $years = $row['client_data_access'];
                                    $years = explode(',', $years);
                                    if (!empty($years)) {
                                        foreach ($years as $n) {
                                            $value = base64_encode($row['client_short_name'] . "_" . $n);
                                            $site_access = (strtoupper($row['client_access']) == 'N') ? " disabled" : "value='" . $value . "'";
                                            $notice = (strtoupper($row['client_access']) == 'N') ? ' - License expired' : '';
                                            $selected = (set_value('selection_year') == $value) ? ' selected' : '';
                                            echo '<option ' . $site_access . $selected . '>' . htmlspecialchars($row['client_short_name']) . '-' . htmlspecialchars($n) . $notice . '</option>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </select>
                        <span class="form-icon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                    </div>

                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="rememberMe" class="checkbox-modern">
                        <label for="rememberMe" class="checkbox-label">Remember me for 30 days</label>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        Sign In
                    </button>

                    <div class="form-footer">
                        © <?= date('Y') ?> VHMS - Vedic Hospital Management System. All rights reserved.<br />
                        <small style="opacity: 0.7;">Developed by <a href="https://www.dharanitechsolutions.com/"
                                target="_blank" style="color: #667eea; text-decoration: none;">Dharani Tech
                                Solutions</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?= $this->scripts_include->includeJs(); ?>

    <script>
        // Password Toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form Validation and Submit Handler
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const loginBtn = document.getElementById('loginBtn');
            const loginname = document.getElementById('loginname').value.trim();
            const password = document.getElementById('password').value;
            const selection_year = document.getElementById('selection_year').value;

            // Basic validation
            if (!loginname) {
                e.preventDefault();
                alert('Please enter your email address');
                document.getElementById('loginname').focus();
                return false;
            }

            // Email format validation (basic)
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(loginname)) {
                e.preventDefault();
                alert('Please enter a valid email address');
                document.getElementById('loginname').focus();
                return false;
            }

            if (!password) {
                e.preventDefault();
                alert('Please enter your password');
                document.getElementById('password').focus();
                return false;
            }

            if (password.length < 4) {
                e.preventDefault();
                alert('Password must be at least 4 characters long');
                document.getElementById('password').focus();
                return false;
            }

            if (!selection_year) {
                e.preventDefault();
                alert('Please select an academic year');
                document.getElementById('selection_year').focus();
                return false;
            }

            // Add loading state
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
        });

        // Remove loading state if back button is pressed
        window.addEventListener('pageshow', function (event) {
            const loginBtn = document.getElementById('loginBtn');
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                loginBtn.classList.remove('loading');
                loginBtn.disabled = false;
            }
        });

        // Auto-hide alert after 5 seconds
        const alertMessage = document.querySelector('.alert-message');
        if (alertMessage && alertMessage.textContent.trim()) {
            setTimeout(function () {
                alertMessage.style.transition = 'opacity 0.5s ease';
                alertMessage.style.opacity = '0';
                setTimeout(function () {
                    alertMessage.style.display = 'none';
                }, 500);
            }, 5000);
        }

        // Add focus/blur effects to form inputs
        document.querySelectorAll('.form-control-modern').forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function () {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>

</html>