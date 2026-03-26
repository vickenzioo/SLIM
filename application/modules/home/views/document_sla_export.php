<!DOCTYPE html>

<html>
<head>
    <style>
        @page {
            /* Mengatur margin: Atas, Kanan, Bawah, Kiri */
            margin: 4cm 2cm 3cm 2.5cm;
        }

        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            line-height: 1.4;
        }

        #header {
            width: 100%;
            border: none;
        }
        /* Judul Section */
        .section-title { 
            background-color: #6a969a; 
            color: #fff; 
            padding: 6px 10px; 
            font-weight: bold; 
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin-top: 85px; 
            margin-bottom: 1px;
        }

        .section-title-pg { 
            background-color: #6a969a; 
            color: #fff; 
            padding: 6px 10px; 
            font-weight: bold; 
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin-top: 85px; 
            margin-bottom: 1px;
        }

        .section-title-ch { 
            background-color: #6a969a; 
            color: #fff; 
            padding: 6px 10px; 
            font-weight: bold; 
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin-top: 5px; 
            margin-bottom: 1px;
        }

        .page-break {
            page-break-after: always;
        }

        /* --- KUNCI UTAMA PERBAIKAN TABEL --- */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 5px; 
            table-layout: fixed; 
            page-break-inside: auto;
        }

        tr { 
            page-break-inside: avoid; 
            page-break-after: auto; 
        }

        th, td { 
            border: 0.4px solid #d1d1d1; 
            padding: 6px 8px; 
            vertical-align: top; 
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #555; 
        }
        /* Kolom Label Kiri */
        .label-col { 
            width: 30%; 
            background-color: #436d85; 
            color: white; 
            font-weight: bold; 
            border: 0.5px solid #fff !important;
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header-table th { 
            background-color: #d3d3d3; 
            text-align: center; 
        }

        .termination-box {
            background-color: #436d85;
            color: white;
            padding: 15px;
            font-size: 11px;
            line-height: 1.6;
            border: 1px solid #d1d1d1;
            text-align: justify;
        }
        .italic-text { font-style: italic; }

        /* --- TABLE DALAM TABEL (NESTED) --- */
        .nested-table { 
            width: 100%; 
            border-collapse: collapse; 
            border: none !important; 
            margin-bottom: 0 !important; 
            table-layout: fixed;
        }

        .nested-table td { 
            border: none !important; 
            padding: 2px 0 !important; 
        }
        ol { margin: 0; padding-left: 15px; }

        /* --- HEADER LOGO & TITLE --- */
        .header-table-container {
            width: 100%;
            border: none !important;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        .header-table-container td {
            border: none !important;
            vertical-align: middle;
        }

        .logo-img {
            max-width: 180px; 
            height: auto;
        }

        .main-title {
            color: #436d85; 
            font-family: Arial, sans-serif;
            font-size: 16px;                 
            font-weight: bold;
            text-align: right; /* Memastikan teks rata kanan */
            margin: 0;
            line-height: 1.2;
            width: 100%;
            display: block;
        }

        /* --- CLASS TAMBAHAN UNTUK MENCEGAH HALAMAN KOSONG --- */

        .mb-0 {
            margin-bottom: 0 !important;
        }

        #footer {
            position: fixed;
            bottom: -2.5cm; 
            /* Gunakan margin yang sama dengan @page Anda */
            left: 2.5cm; 
            right: 2cm;  
            height: 1cm;
            border-top: 2px solid #1a4d69;
            padding-top: 6px;
            font-size: 11px;
            color: #436d85 !important;
            font-family: Arial, Helvetica, sans-serif;
        }

        .table-footer {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            border-top: 2px solid #1a4d69 !important; 
            margin-top: 0;
            table-layout: fixed;
        }

        .table-footer td {
            border: none !important; /* Menghilangkan border pada cell agar tidak terlihat kotak */
            padding-top: 8px;
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #436d85 !important;
            vertical-align: middle;
        }

        .ft-left {
            text-align: left;
            color: #1a4d69;
            width: 50%;
        }

        .ft-right {
            text-align: right;
            color: #1a4d69;
            width: 50%;
        }
    </style>
</head>

<body>
    <page_header>
        <div id="header">
            <table class="header-table-container">
                <colgroup>
                    <col style="width: 40%;">
                    <col style="width: 60%;">
                </colgroup>
                <tr>
                    <td style="border:none;">
                        <img src="assets/img/logo.png" class="logo-img" alt="Logo">
                    </td>

                    <td style="border:none;">
                        <div class="main-title">
                            DOKUMEN TINGKAT KETERSEDIAAN<br>
                            LAYANAN (SLA)
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </page_header>


    <page_footer>
        <table class="table-footer">
            <tr>
                <td class="ft-left">DOKUMEN INTERNAL</td>
                <td class="ft-right">Halaman: [[page_cu]]</td>
            </tr>
        </table>
    </page_footer>

        <div class="section-title">LAYANAN</div>
        <table>
            <colgroup>
                <col style="width: 30%;">
                <col style="width: 70%;">
            </colgroup>
            <?php
                $version_num = isset($app['version']) ? (int)$app['version'] : 1;
                $doc_version = str_pad($version_num, 3, '0', STR_PAD_LEFT);
                $cat_name = isset($app['category_name']) ? strtoupper($app['category_name']) : '';
                $cat_initial = 'O'; 

                if ($cat_name == 'CRITICAL') {
                    $cat_initial = 'C';
                } elseif ($cat_name == 'VERY IMPORTANT') {
                    $cat_initial = 'V';
                } elseif ($cat_name == 'IMPORTANT') {
                    $cat_initial = 'I';
                } elseif ($cat_name == 'NECESSARY') {
                    $cat_initial = 'N';
                }
                $app_date = !empty($app['modified_at']) ? $app['modified_at'] : date('Y-m-d H:i:s');
                $doc_month = date('m', strtotime($app_date));
                $doc_year  = date('Y', strtotime($app_date));
                $no_dokumen = "SLA.{$doc_version}.{$cat_initial}.{$doc_month}.{$doc_year}";
            ?>

            <tr>
                <td class="label-col">No. Dokumen</td>
                <td><?= $no_dokumen ?></td>
            </tr>

            <tr>
                <td class="label-col">Nama Layanan</td>
                <td><strong><?= isset($app['application_name']) ? $app['application_name'] : '-' ?></strong></td>
            </tr>

            <tr>
                <td class="label-col">Deskripsi</td>
                <td><?= isset($app['apps_description']) ? $app['apps_description'] : '-' ?></td>
            </tr>

            <tr>
                <td class="label-col">Kategori</td>
                <td><strong><?= isset($app['category_name']) ? $app['category_name'] : '-' ?></strong></td>
            </tr>

            <tr>
                <td class="label-col">Penyedia Layanan</td>
                <td>
                    Directorate Operations, Technology, Analytic & AI<br>
                    Sub Directorate Information Technology<br>
                    Sub Directorate Cyber Security
                </td>
            </tr>

            <tr>
                <td class="label-col"><i>Owner</i>/Pengguna Layanan</td>
                <td>
                    Directorate <?= isset($app['directorate_user']) ? $app['directorate_user'] : '«Directorate_User»' ?>, <br>
                    Sub Directorate <?= isset($app['sub_dir_user']) ? $app['sub_dir_user'] : '«Sub_Dir_User»' ?>
                </td>
            </tr>
        </table>

        <div class="section-title-ch">PLATFORM INFRASTRUKTUR</div>
        <table>
            <colgroup>
                <col style="width: 30%;">
                <col style="width: 70%;">
            </colgroup>

            <tr>
                <td class="label-col">Platform Server</td>
                <td><?= isset($app['server_names_str']) ? $app['server_names_str'] : '-' ?></td>
            </tr>

            <tr>
                <td class="label-col">Penempatan</td>
                <td><?= isset($app['deployment_model_name']) ? $app['deployment_model_name'] : (isset($app['deployment_model']) ? $app['deployment_model'] : '-') ?>, <?= isset($app['deployment_name']) ? $app['deployment_name'] : (isset($app['provider_name']) ? $app['provider_name'] : '-') ?></td>
            </tr>

            <tr>
                <td class="label-col">Lokasi Utama</td>
                <td><?= isset($app['deployment_site_name']) ? $app['deployment_site_name'] : (isset($app['site_name']) ? $app['site_name'] : '-') ?></td>
            </tr>

            <tr>
                <td class="label-col">Sistem Operasi (OS)</td>
                <td><?= isset($app['operating_software_name']) ? $app['operating_software_name'] : (isset($app['os_names_str']) ? $app['os_names_str'] : '-') ?></td>
            </tr>

            <tr>
                <td class="label-col">Database</td>
                <td><?= isset($app['database_name']) ? $app['database_name'] : (isset($app['database_names_str']) ? $app['database_names_str'] : '-') ?></td>
            </tr>

            <tr>
                <td class="label-col">DR</td>
                <td><?= isset($app['DR']) ? $app['DR'] : (isset($app['dr_availability']) ? $app['dr_availability'] : '-') ?></td>
            </tr>

            <tr>
                <td class="label-col">Resiliency/Ketahanan</td>
                <td><?= isset($app['resilience_category']) ? $app['resilience_category'] : '-' ?> : <?= isset($app['ha']) ? $app['ha'] : '-' ?> HA</td>
            </tr>

            <tr>
                <td class="label-col">Jenis Pengguna Layanan</td>
                <td><?= isset($app['network_name']) ? $app['network_name'] : '-' ?></td>
            </tr>
        </table>

        <div class="section-title-ch">DUKUNGAN LAYANAN/VENDOR</div>
        <table>
            <colgroup>
                <col style="width: 30%;">
                <col style="width: 70%;">
            </colgroup>

            <tr>
                <td class="label-col">Penyedia Solusi</td>
                <td><?= isset($app['solution_name']) ? $app['solution_name'] : (isset($app['solution_vendor']) ? $app['solution_vendor'] : '-') ?></td>
            </tr>

            <tr>
                <td class="label-col">Penyedia Layanan</td>
                <td><?= isset($app['services_name']) ? $app['services_name'] : (isset($app['services_vendor']) ? $app['services_vendor'] : '-') ?></td>
            </tr>
        </table>

        <div class="section-title-ch">OPERASIONAL</div>
        <table class="mb-0">
            <colgroup>
                <col style="width: 30%;">
                <col style="width: 70%;">
            </colgroup>

            <tr>
                <td class="label-col">Hari Operasional</td>
                <td>
                    <?php if (!empty($app['start_day']) && !empty($app['end_day'])): ?>
                        <?= $app['start_day'] ?> – <?= $app['end_day'] ?><?= !empty($app['total_day']) ? " ({$app['total_day']} Hari Per Minggu)" : "" ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td class="label-col">Jam Operasional</td>
                <td>
                    <?php 
                        if (!empty($app['start_time']) && !empty($app['end_time'])) {
                            $start_time = date('H:i', strtotime($app['start_time']));
                            $end_time   = date('H:i', strtotime($app['end_time']));
                            if ($end_time == '23:59') { $end_time = '24:00'; }
                            $total_hour_text = '';
                            if (isset($app['total_hour']) && $app['total_hour'] != '') {
                                $total = str_replace(',', '.', $app['total_hour']);
                                $total = (float)$total; 
                                $total_hour_text = " ({$total} Jam)";
                            }
                            echo "{$start_time} – {$end_time}{$total_hour_text}";
                        } else {
                            echo "-";
                        }
                    ?>
                </td>
            </tr>
        </table>


        <div class="section-title-ch">KOMITMEN</div>
        <table class="mb-0">
            <colgroup>
                <col style="width: 40%;">
                <col style="width: 30%;">
                <col style="width: 30%;">
            </colgroup>

            <thead>
                <tr style="background-color: #44728d; color: white;">
                    <th style="padding: 8px; color: white; border: 1px solid #fff; text-align: left;">Matriks</th>
                    <th style="padding: 8px; color: white; border: 1px solid #fff; text-align: right;">Ambang Batas/Batas Nilai</th>
                    <th style="padding: 8px; color: white; border: 1px solid #fff; text-align: left;">Frekuensi</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="label-col">Ketersediaan</td>
                    <td style="text-align: right; padding: 8px; border: 1px solid #dee2e6;">
                        <?= isset($app['standard_category']) ? $app['standard_category'] : '-' ?> %
                    </td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">Per Tahun</td>
                </tr>

                <tr>
                    <td class="label-col">Komitmen Ketersediaan Layanan (Menit)</td>
                    <td style="text-align: right; padding: 8px; border: 1px solid #dee2e6;">
                        <?php 
                            $target_sla_val = isset($app['standard_category']) ? (float)$app['standard_category'] : 0;
                            $t_day = isset($app['total_day']) ? (int)$app['total_day'] : 0;
                            $t_hour = isset($app['total_hour']) ? (int)$app['total_hour'] : 0;
                            $hasil_menit = ($target_sla_val / 100) * ($t_day * $t_hour * 60 * 52);
                            echo number_format($hasil_menit, 0, ',', '.');
                        ?>
                    </td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">Menit Per Tahun</td>
                </tr>
                <tr>
                    <td cLass="label-col">Toleransi <i>Downtime</i> (Menit)</td>
                    <td style="text-align: right; padding: 8px; border: 1px solid #dee2e6;">
                        <?php 
                            $target_sla_dec = isset($app['standard_category']) ? (float)$app['standard_category'] / 100 : 0;
                            $t_day = isset($app['total_day']) ? (int)$app['total_day'] : 0;
                            $t_hour = isset($app['total_hour']) ? (int)$app['total_hour'] : 0;
                            $total_menit_operasional_tahun = ($t_day * $t_hour * 60 * 52);
                            $toleransi_downtime = (1 - $target_sla_dec) * $total_menit_operasional_tahun;
                            echo number_format($toleransi_downtime, 0, ',', '.');
                        ?>
                    </td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">Menit Per Tahun</td>
                </tr>

               <tr>
                    <td class="label-col">Waktu Tanggap Insiden</td>
                    <td style="text-align: right; padding: 8px; border: 1px solid #dee2e6;">
                        Up to <?= isset($app['response_time']) ? $app['response_time'] : '-' ?>
                    </td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">
                        <?= isset($app['response_time_sat']) ? $app['response_time_sat'] : '-' ?>
                    </td>
                </tr>

                <tr>
                    <td class="label-col">Waktu Penyelesaian Insiden</td>
                    <td style="text-align: right; padding: 8px; border: 1px solid #dee2e6;">
                        Up to <?= isset($app['recovery_time']) ? $app['recovery_time'] : '-' ?>
                    </td>
                    <td style="padding: 8px; border: 1px solid #dee2e6;">
                        <?= isset($app['recovery_time_sat']) ? $app['recovery_time_sat'] : '-' ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="page-break"></div>

        <div class="section-title-pg">TUGAS & TANGGUNG JAWAB</div>
        <table>
            <colgroup>
                <col style="width: 30%;">
                <col style="width: 70%;">
            </colgroup>

            <tr>
                <td class="label-col">Penyedia Layanan</td>
                <td>
                    Menyediakan dukungan layanan sesuai dengan SLA<br>
                    Melakukan pemeliharaan sistem
                </td>
            </tr>

            <tr>
                <td class="label-col"><i>Owner</i>/Pengguna Layanan</td>
                <td>
                    Melaporkan insiden melalui saluran resmi <i>Service Desk</i><br>
                    Memberikan informasi yang dibutuhkan untuk mempercepat penyelesaian masalah
                </td>
            </tr>
        </table>

        <div class="section-title-ch">MONITORING & PELAPORAN</div>

        <table>
            <colgroup>
                <col style="width: 30%;">
                <col style="width: 70%;">
            </colgroup>

            <tr>
                <td class="label-col">Monitoring</td>
                <td style="padding: 8px;">Harian</td>
            </tr>

            <tr>
                <td class="label-col">Laporan</td>
                <td style="padding: 8px;">Bulanan</td>
            </tr>

            <tr>
                <td class="label-col" style="vertical-align: top; padding-top: 8px;"> Unit Dukungan Layanan <br> <i>(Service Desk)</i></td>
                <td style="padding: 0; vertical-align: top;">
                    <table class="nested-table" style="width: 100%; border: none;">
                        <colgroup>
                            <col style="width: 35%;">
                            <col style="width: 65%;">
                        </colgroup>

                        <tr>
                            <td style="padding-top: 8px; border: none; padding-left: 8px;">Jam Operasional</td>
                            <td style="padding-top: 8px; border: none; padding-left: 4px;">: 07:00 – 17:30 (Service Desk)</td>
                        </tr>

                        <tr>
                            <td style="border: none;"></td>
                            <td style="border: none; padding-left: 4px;">: 17:30 – 07:00 (Data Center)</td>
                        </tr>

                        <tr>
                            <td style="padding-left: 8px; border: none;">No. Telepon & Ext</td>
                            <td style="border: none; padding-left: 4px;">: 021-29972400, Ext. 85100</td>
                        </tr>

                        <tr>
                             <td style="padding-left: 8px; border: none;">Email</td>
                            <td style="border: none; padding-left: 4px;">: <span style="color: purple; text-decoration: underline;">servicedesk@cimbniaga.co.id</span></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="label-col" style="vertical-align: top; padding-top: 8px;">Sumber Data</td>
                <td style="vertical-align: top; padding-left: 8px;">
                    <ol style="margin-top: 8px; margin-bottom: 8px;">
                        <li><i>Incident Report (unplanned downtime)</i></li>
                        <li>SLA <i>Network</i></li>
                        <li>Proses <i>batch</i> akhir hari (untuk aplikasi tertentu)</li>
                  </ol>
                </td>
            </tr>

            <tr>
                <td class="label-col">Pengecualian</td>
                <td style="padding: 8px;">Pemeliharaan yang direncanakan dan disetujui oleh pengguna</td>
            </tr>

            <tr>
                <td class="label-col" style="vertical-align: middle;">Ketersediaan</td>
                <td style="vertical-align: middle; padding: 10px 8px;">
                    <table style="width: auto; border: none !important; border-collapse: collapse; display: inline-table;">
                        <tr>
                            <td rowspan="2" style="border: none !important; vertical-align: middle; padding: 0 5px; font-size: 10px;">1 -</td>
                            <td rowspan="2" style="border: none !important; vertical-align: middle; padding: 0 5px; font-size: 10px;">(</td>
                            <td style="border: none !important; border-bottom: 0.5px solid #555 !important; text-align: center; vertical-align: bottom; padding: 0 10px 2px 10px;">
                                <i style="font-family: Arial, sans-serif; font-size: 10px; color: #555;">Unplanned Downtime</i>
                            </td>
                            <td rowspan="2" style="border: none !important; vertical-align: middle; padding: 0 5px; font-size: 10px;">x 100%</td>
                            <td rowspan="2" style="border: none !important; vertical-align: middle; padding: 0 5px; font-size: 10px;">)</td>
                        </tr>
                        <tr>
                            <td style="border: none !important; text-align: center; vertical-align: top; padding: 2px 10px 0 10px;">
                                <i style="font-family: Arial, sans-serif; font-size: 10px; color: #555;">Committed Uptime</i>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="section-title-ch">TINJAUAN DOKUMEN</div>
        <table>
            <colgroup>
                <col style="width: 30%;">
                <col style="width: 70%;">
            </colgroup>

            <tr>
                <td class="label-col">Periode Berlaku</td>
                <td>Berlaku terus menerus hingga ada perubahan informasi pada portofolio aplikasi dan atau informasi dari pengguna aplikasi </td>
            </tr>

            <tr>
                <td class="label-col">Tinjauan/Evaluasi</td>
                <td>1 tahun sekali </td>
            </tr>
        </table>

        <div class="section-title-ch">KETENTUAN PENGAKHIRAN</div>
        <div class="termination-box" style="margin-bottom: 5px;">
            Dokumen SLA ini dapat dihentikan seiring dengan penghentian layanan aplikasi, setelah menerima pemberitahuan resmi dari tim <i>Enterprise Architecture</i> (EA).
        </div>
        <div class="section-title-ch">MENGETAHUI</div>

        <table>
            <colgroup>
                <col style="width: 30%;">
                <col style="width: 70%;">
            </colgroup>

            <tr>
                <td class="label-col" rowspan="2" style="vertical-align: middle;">Penyedia Layanan</td>
                <td style="padding: 8px;">
                    <strong>Tess</strong><br>
                    Head of Information Technology
                </td>
            </tr>

            <tr>
                <td style="padding: 8px;">
                    <strong>Tess</strong><br>
                    Head of Information Security
                </td>
            </tr>

            <tr>
                <td class="label-col" style="vertical-align: middle;">Pemilik/Pengguna Layanan</td>
                <td style="padding: 8px;">
                    <strong><?= isset($app['ho_name_user']) ? $app['ho_name_user'] : '«HO_Name_User»' ?></strong><br>
                    Head of <?= isset($app['sub_dir_user']) ? $app['sub_dir_user'] : '«Sub_Dir_User»' ?>
                </td>
            </tr>

            <tr>
                <td class="label-col" style="vertical-align: middle;">Mengetahui</td>
                <td style="padding: 8px;">
                    <strong>Tess</strong><br>
                    Head of OTAA Office, Cost Management & Analytics
                </td>
            </tr>
        </table>
</body>
</html>