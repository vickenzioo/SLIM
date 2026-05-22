<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SLIM | Holiday Calendar</title>

	<?php $this->load->view('layout/head_links'); ?>

</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">

<div class="wrapper">
    <?php $this->load->view('layout/header'); ?>
    <?php $this->load->view('layout/sidebar'); ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color: var(--text-dark); font-weight: 700;">Holiday Calendar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('portofolio')?>" class="breadcrumb-home">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Holiday</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content p-3">
        <div class="card" style="border-top: 3px solid var(--theme-yellow-primary);">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalHoliday" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= base_url('holiday/save') ?>" id="formHoliday" class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--theme-bg-yellow-light) 0%, var(--theme-bg-yellow-dark) 100%); color: var(--text-dark);">
                <h5 class="modal-title" id="modalHolidayTitle">Add Holiday</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="Holiday_ID" id="modal_holiday_id">

                <div class="form-group">
                    <label>Holiday Name</label>
                    <input type="text" name="Holiday_Name" id="modal_holiday_name" class="form-control" required placeholder="Enter Holiday Name">
                </div>

                <div class="form-group">
                    <label>Holiday Date</label>
                    <input type="date" name="Holiday_Date" id="modal_holiday_date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="Holiday_Description" id="modal_holiday_desc"class="form-control" placeholder="Enter Holiday Description..." ></textarea>
                </div>

                <div class="form-group" id="reason_container_holiday" style="display: none;">
                    <label>Reason</label>
                    <textarea name="reason" id="modal_holiday_reason" class="form-control" placeholder="Masukkan alasan perubahan..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-save-custom">Submit</button>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="modalHolidayDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header" style="background: linear-gradient(135deg, var(--theme-bg-yellow-light) 0%, var(--theme-bg-yellow-dark) 100%); color: var(--text-dark);">
                <h5 class="modal-title" id="detail_title">Holiday Detail</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="detail_id">
                <input type="hidden" id="detail_is_api">

                <p><strong>Holiday Name</strong><br><span id="detail_name"></span></p>
                <p><strong>Date</strong><br><span id="detail_date"></span></p>
                <p><strong>Description</strong><br><span id="detail_desc"></span></p>
            </div>

            <div class="modal-footer justify-content-end">
                <button class="btn btn-sm btn-primary" id="btnAudit" title="Audit Trail" style="background-color: #4e5abf; border: none;">
                    <i class="fas fa-history"></i>
                </button>

                <button class="btn btn-sm btn-action-yellow ml-1" id="btnEdit" title="Edit Data">
                    <i class="fas fa-edit"></i>
                </button>

                <button class="btn btn-sm btn-action-red ml-1" id="btnDelete" title="Hapus Data">
                    <i class="fas fa-trash"></i>
                </button>

            </div>
        </div>
    </div>
</div>

<div id="holidayHoverPopover">
    <div id="hp_title"></div>
    <div id="hp_date"></div>
    <div id="hp_desc"></div>
</div>

</div>

<?php $this->load->view('layout/foot_links'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function formatDate(date){
            return new Date(date).toLocaleDateString('en-US',{
                weekday:'long',year:'numeric',month:'long',day:'numeric'
            });
        }

        function showPopover(e,title,date,desc){
            $('#hp_title').text(title);
            $('#hp_date').text(date);
            $('#hp_desc').text(desc);
            $('#holidayHoverPopover')
                .css({left:e.clientX+15, top:e.clientY+15})
                .show();
        }

        function hidePopover(){
            $('#holidayHoverPopover').hide();
        }

        /* ====================== SWEETALERT: EXPORT ====================== */
        function confirmExportHoliday() {
            Swal.fire({
                title: 'Export to Excel?',
                text: "File akan otomatis diunduh.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Export',
                reverseButtons: true,
                cancelButtonText: 'Cancel',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-save-custom px-4 mx-2',
                    cancelButton: 'btn btn-secondary px-4 mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Downloading file...'
                    });

                    window.location.href = "<?= base_url('holiday/export') ?>";
                }
            });
        }

        /* ====================== SWEETALERT: DELETE (WITH REASON) ====================== */
        function confirmDeleteHoliday(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                input: 'text',
                inputLabel: 'Alasan Penghapusan:',
                inputPlaceholder: 'Masukkan Alasan...',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-deactivate px-4 mx-2',  
                    cancelButton: 'btn btn-secondary px-4 mx-2'
                },
                inputAttributes: {
                    style: 'width: 95%; margin: 10px auto; display: block; border: 1px solid #ced4da; padding: 8px; border-radius: 4px;'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return 'Anda harus menuliskan alasan menghapus data!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = encodeURIComponent(result.value);
                    window.location.href = url + "?reason=" + reason;
                }
            });
        }

        /* ====================== FULLCALENDAR INIT ====================== */
        const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialDate: new Date(),
            initialView: 'dayGridMonth',

            // [PENTING] Panggil styleCalendarButtons setiap kali tanggal berubah (navigasi)
            datesSet: function() {
                styleCalendarButtons();
            },

            customButtons: {
                addHoliday: {
                    text: '', // Text diset di fungsi styleCalendarButtons
                    click: function() {
                        $('#modalHolidayTitle').text('Add Holiday');
                        $('#formHoliday').attr('action','<?= base_url("holiday/save") ?>');
                        $('#modal_holiday_id').val('');
                        $('#modal_holiday_name').val('');
                        $('#modal_holiday_date').val('');
                        $('#modal_holiday_desc').val('');
                        
                        $('#modal_holiday_reason').val('').prop('required', false);
                        $('#reason_container_holiday').hide();
                        
                        $('#modalHoliday').modal('show');
                    }
                },
                exportHoliday: {
                    text: '', // Text diset di fungsi styleCalendarButtons
                    click: function() {
                        confirmExportHoliday();
                    }
                }
            },

            headerToolbar:{
                left:'addHoliday exportHoliday',
                center:'title',
                right:'prev,next'
            },

            eventMouseEnter:function(info){
                showPopover(
                    info.jsEvent,
                    info.event.title,
                    formatDate(info.event.startStr),
                    info.event.extendedProps.description || 'Holiday'
                );
            },
            eventMouseLeave: hidePopover,

            eventClick:function(info){
                hidePopover();
                const e = info.event;

                $('#detail_id').val(e.id);
                $('#detail_is_api').val(e.extendedProps.is_api ? 1 : 0);
                $('#detail_title').text(e.title);
                $('#detail_name').text(e.title);
                $('#detail_date').text(formatDate(e.startStr));
                $('#detail_desc').text(e.extendedProps.description || '-');

                if(e.extendedProps.is_api){
                    $('#btnEdit,#btnDelete').hide();
                }else{
                    $('#btnEdit,#btnDelete').show();
                }

                $('#modalHolidayDetail').modal('show');
            },

            eventSources:[
                {
                    url:'<?= base_url("holiday/get_holidays") ?>',
                    success:function(res){
                        return res.map(i=>({
                            id:i.Holiday_ID,
                            title:i.Holiday_Name,
                            start:i.Holiday_Date,
                            backgroundColor:'#2d8cff',
                            borderColor:'#2d8cff',
                            textColor:'#fff',
                            extendedProps:{description:i.Holiday_Description,is_api:false}
                        }));
                    }
                },
            ]
        });

        calendar.render();
        // Tidak perlu panggil styleCalendarButtons() disini karena datesSet akan berjalan otomatis saat render awal

        /* ====================== STYLE BUTTONS (FIXED SIZE) ====================== */
        function styleCalendarButtons(){
            const addBtn    = document.querySelector('.fc-addHoliday-button');
            const exportBtn = document.querySelector('.fc-exportHoliday-button');
            const prevBtn   = document.querySelector('.fc-prev-button');
            const nextBtn   = document.querySelector('.fc-next-button');

            if(addBtn){
                // Pastikan hanya menggunakan btn-sm agar ukurannya standar Bootstrap
                addBtn.className = 'btn btn-sm btn-holiday-add'; 
                addBtn.innerHTML = '<i class="fas fa-plus mr-1"></i> Add';
            }

            if(exportBtn){
                exportBtn.className = 'btn btn-sm btn-holiday-export ml-2';
                exportBtn.innerHTML = '<i class="fas fa-file-export mr-1"></i> Export';
            }

            if(prevBtn) {
                // Hapus penambahan class manual agar mengikuti style CSS holiday
                prevBtn.innerHTML = '<i class="fas fa-chevron-left" style="font-size: 12px;"></i>';
            }

            if(nextBtn) {
                // Menambahkan ml-2 agar jaraknya sama dengan button Add & Export (8px)
                nextBtn.classList.add('ml-2');
                nextBtn.innerHTML = '<i class="fas fa-chevron-right" style="font-size: 12px;"></i>';
            }
        }

        /* ====================== EDIT BUTTON (DETAIL MODAL -> OPEN EDIT MODAL) ====================== */
        $('#btnEdit').on('click', function() {
            $('#modalHolidayDetail').modal('hide');
            $('#modalHolidayTitle').text('Edit Holiday');
            $('#formHoliday').attr('action', '<?= base_url("holiday/update") ?>');
            
            const eventId = $('#detail_id').val();
            const event = calendar.getEventById(eventId);
            
            $('#modal_holiday_id').val(eventId);
            $('#modal_holiday_name').val($('#detail_name').text());
            $('#modal_holiday_desc').val($('#detail_desc').text());

            // Format Date
            const dateObj = event.start; 
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            
            $('#modal_holiday_date').val(formattedDate);

            // Tampilkan Reason saat Edit
            $('#modal_holiday_reason').val('');
            $('#reason_container_holiday').show();
            
            $('#modalHoliday').modal('show');
        });


        /* ====================== TOMBOL AUDIT REDIRECT ====================== */
        $('#btnAudit').on('click', function() {
            const holidayId = $('#detail_id').val();
            if (holidayId) {
                window.location.href = "<?= base_url('holiday/audit_trail/') ?>" + holidayId;
            } else {
                Swal.fire('Error', 'ID Holiday tidak ditemukan', 'error');
            }
        });


        /* ====================== DELETE BUTTON (DETAIL MODAL) ====================== */
        $('#btnDelete').on('click', function () {
        $('#modalHolidayDetail').modal('hide');

        setTimeout(function () {
            confirmDeleteHoliday('<?= base_url("holiday/delete/") ?>' + $('#detail_id').val());
        }, 200);
        
        });

        // Flashdata Success
        <?php if($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= $this->session->flashdata('success') ?>',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: { confirmButton: 'btn btn-theme-gradient px-4' }
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        // Flashdata Error
        <?php if($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= $this->session->flashdata('error') ?>',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: { confirmButton: 'btn btn-danger px-4' } 
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        // --- Script Dark Mode & Logout ---
        const toggleBtn = document.getElementById('darkModeBtn');
        const body = document.body;
        const icon = toggleBtn ? toggleBtn.querySelector('i') : null;

        function updateIcon(isDark) {
            if (!icon) return;
            if (isDark) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun'); 
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon'); 
            }
        }

        if (localStorage.getItem('theme') === 'dark') {
            if(!body.classList.contains('dark-mode')){
                body.classList.add('dark-mode');
            }
            updateIcon(true);
        }

        if(toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                body.classList.toggle('dark-mode');
                const isDark = body.classList.contains('dark-mode');
                
                if (isDark) {
                    localStorage.setItem('theme', 'dark');
                    updateIcon(true);
                } else {
                    localStorage.setItem('theme', 'light');
                    updateIcon(false);
                }

                if (typeof updateChartTheme === 'function') {
                    updateChartTheme(isDark);
                }
            });
        }
        
        const logoutBtn = document.getElementById('logoutLink');
        const overlay = document.getElementById('loadingOverlay');

        if(logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault(); 
                const urlLogout = this.getAttribute('href');

                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Apakah Anda yakin ingin keluar dari sistem?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Logout',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-save-custom px-4 mx-2', 
                        cancelButton: 'btn btn-secondary px-4 mx-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        if(overlay) overlay.style.display = 'flex';
                        window.location.href = urlLogout;
                    }
                });
            });
        }
    });

    $(document).on('input', '#modal_holiday_name, #modal_holiday_desc, #modal_holiday_reason, .swal2-popup input[type="text"], .swal2-popup textarea', function() {
            var el = $(this);
            var currentValue = el.val();
            var forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g; 

            if (forbiddenChars.test(currentValue)) {
                el.val(currentValue.replace(forbiddenChars, ''));
                
                el.css({
                    'border-color': '#dc3545',
                    'box-shadow': '0 0 0 0.2rem rgba(220, 53, 69, 0.25)'
                });
                setTimeout(function() {
                    el.css({ 'border-color': '', 'box-shadow': '' });
                }, 400);
            }
        });
        
        $(document).on('paste', '#modal_holiday_name, #modal_holiday_desc, #modal_holiday_reason, input[name="keyword"], .filter-search-input, .swal2-popup input[type="text"], .swal2-popup textarea', function(e) {
            
            // Regex: HANYA izinkan huruf, angka, spasi, titik, koma, strip, dan underscore
            var forbiddenChars = /[^a-zA-Z0-9\s.,_\-]/g; 
            
            // Ambil data teks dari clipboard
            var pasteData = (e.originalEvent || e).clipboardData.getData('text');

            if (forbiddenChars.test(pasteData)) {
                // Jika mengandung karakter terlarang, batalkan proses paste secara total
                e.preventDefault();
                
                // Beri feedback visual border merah berkedip
                var el = $(this);
                el.css({
                    'border-color': '#dc3545',
                    'box-shadow': '0 0 0 0.2rem rgba(220, 53, 69, 0.25)'
                });
                
                setTimeout(function() {
                    el.css({
                        'border-color': '',
                        'box-shadow': ''
                    });
                }, 400);
            }
        });
</script>
</body>
</html>