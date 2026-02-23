<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        date_default_timezone_set('Asia/Jakarta');
        
        $this->load->library('session');
        $this->load->model('Holiday_model');

        // Cek Login
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
    }

    public function index() {
        $data['user'] = $this->session->userdata('email');
        $data['title'] = 'Holiday Calendar';
        $data['total_rows'] = $this->Holiday_model->count_all_holidays();

        $this->load->view('holiday_view', $data);
    }

    public function get_holidays() {
        $data = $this->Holiday_model->get_all_holidays();

        $events = [];
        foreach ($data as $row) {
            $events[] = [
                'Holiday_ID'          => $row->Holiday_ID,
                'Holiday_Name'        => $row->Holiday_Name,
                'Holiday_Date'        => $row->Holiday_Date,
                'Holiday_Description' => $row->Holiday_Description
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($events);
    }

    public function save() {
        $id   = $this->security->xss_clean($this->input->post('Holiday_ID'));
        $date = $this->security->xss_clean($this->input->post('Holiday_Date'));
        $name = $this->security->xss_clean($this->input->post('Holiday_Name'));
        $desc = $this->security->xss_clean($this->input->post('Holiday_Description'));

        // Ambil data untuk audit
        $reason   = $this->input->post('reason');
        $username = $this->session->userdata('username');

        // Validasi wajib
        if (empty($date) || empty($name)) {
            $this->session->set_flashdata('error', 'Nama dan Tanggal harus diisi!');
            redirect('holiday');
            return;
        }

        // CEK DUPLIKAT Holiday_Date
        $this->db->where('Holiday_Date', $date);
        if (!empty($id)) {
            $this->db->where('Holiday_ID !=', $id);
        }
        $dup = $this->db->get('tbl_holiday')->row();

        if ($dup) {
            $this->session->set_flashdata('error', 'Holiday Date "'. $date .'" sudah ada! Gagal menyimpan.');
            redirect('holiday');
            return;
        }

        if (!empty($id)) {
            // ==========================================
            // MODE EDIT
            // ==========================================

            // Validasi Reason
            if (empty($reason)) {
                $this->session->set_flashdata('error', 'Gagal update: Alasan wajib diisi!');
                redirect('holiday');
                return;
            }

            // Validasi Data Sama
            $oldData = $this->db->get_where('tbl_holiday', ['Holiday_ID' => $id])->row_array();
            if (!$oldData) {
                $this->session->set_flashdata('error', 'Data tidak ditemukan.');
                redirect('holiday');
                return;
            }

            $oldName = trim((string)$oldData['Holiday_Name']);
            $oldDate = trim((string)$oldData['Holiday_Date']);
            $oldDesc = trim(str_replace(["\r", "\n"], '', (string)$oldData['Holiday_Description']));

            $newName = trim((string)$name);
            $newDate = trim((string)$date);
            $newDesc = trim(str_replace(["\r", "\n"], '', (string)$desc));

            if ($oldName === $newName && $oldDate === $newDate && $oldDesc === $newDesc) {
                $this->session->set_flashdata('error', 'Gagal simpan: Tidak ada perubahan data.');
                redirect('holiday');
                return;
            }

            // Ambil data lama sebelum diupdate untuk audit
            $old_data = $this->Holiday_model->get_holiday_by_id($id);

            $data = [
                'Holiday_Name'        => $name,
                'Holiday_Date'        => $date,
                'Holiday_Description' => $desc
            ];

            $this->Holiday_model->update_holiday($id, $data);

            // Simpan Audit EDIT
            $this->load->model('audit/Audit_model');

            if ($old_data && $old_data->Holiday_Name != $name) {
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => 'EDIT',
                    'table_name' => 'tbl_holiday',
                    'foreign_id' => $id,
                    'field_name' => 'Holiday Name', // [UBAH] Menghapus Underscore
                    'old_value'  => $old_data->Holiday_Name,
                    'new_value'  => $name,
                    'reason'     => $reason,
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);
            }

            if ($old_data && $old_data->Holiday_Date != $date) {
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => 'EDIT',
                    'table_name' => 'tbl_holiday',
                    'foreign_id' => $id,
                    'field_name' => 'Holiday Date', // [UBAH] Menghapus Underscore
                    'old_value'  => $old_data->Holiday_Date,
                    'new_value'  => $date,
                    'reason'     => $reason,
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);
            }

            $oldDescVal = $old_data ? (string)$old_data->Holiday_Description : '';
            $newDescVal = (string)$desc;

            if ($oldDescVal != $newDescVal) {
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => 'EDIT',
                    'table_name' => 'tbl_holiday',
                    'foreign_id' => $id,
                    'field_name' => 'Holiday Description', // [UBAH] Menghapus Underscore
                    'old_value'  => $oldDescVal === '' ? '-' : $oldDescVal,
                    'new_value'  => $newDescVal === '' ? '-' : $newDescVal,
                    'reason'     => $reason,
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->session->set_flashdata('success', 'Data berhasil diperbarui');
        } else {
            // ==========================================
            // MODE ADD
            // ==========================================
            $data = [
                'Holiday_Name'        => $name,
                'Holiday_Date'        => $date,
                'Holiday_Description' => $desc
            ];

            $this->Holiday_model->insert_holiday($data);
            $new_id = $this->db->insert_id();

            // Simpan Audit ADD per Field
            $this->load->model('audit/Audit_model');
            
            $new_fields = [
                'Holiday Name'        => $name,
                'Holiday Date'        => $date,
                'Holiday Description' => $desc ?: '-'
            ];

            foreach ($new_fields as $label => $value) {
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => 'ADD',
                    'table_name' => 'tbl_holiday',
                    'foreign_id' => $new_id,
                    'field_name' => $label,
                    'old_value'  => '-',
                    'new_value'  => $value,
                    'reason'     => 'Initial creation',
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
        }

        redirect('holiday');
    }

    public function update() {
        // Fungsi update() dipindahkan logikanya ke save() di atas agar satu pintu.
        // Jika form action Anda masih mengarah ke update(), redirect saja ke save() atau copy logika save() bagian EDIT ke sini.
        // Namun di kode view sebelumnya action-nya adalah 'holiday/save' untuk Add dan 'holiday/update' untuk Edit.
        // Agar aman, saya arahkan fungsi ini untuk memanggil logika save().
        
        $this->save(); 
    }

    public function delete($id) {
        if (!$id) {
            redirect('holiday');
            return;
        }

        $reason = $this->input->get('reason');
        if (empty($reason)) {
            $reason = 'Delete holiday';
        }

        $username = $this->session->userdata('username');

        // Ambil data lama untuk audit
        $old = $this->Holiday_model->get_holiday_by_id($id);

        $delete = $this->Holiday_model->delete_holiday($id);

        if ($delete) {
            $this->load->model('audit/Audit_model');
            if ($old) {
                $this->Audit_model->insert_log([
                    'username'   => $username,
                    'action'     => 'DELETE',
                    'table_name' => 'tbl_holiday',
                    'foreign_id' => $id,
                    'field_name' => 'Holiday Date',
                    'old_value'  => $old->Holiday_Date . ' ' . $old->Holiday_Name,
                    'new_value'  => '-',
                    'reason'     => $reason,
                    'timestamp'  => date('Y-m-d H:i:s')
                ]);
            }

            $this->session->set_flashdata('success', 'Data libur berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data libur.');
        }

        redirect('holiday');
    }

    public function audit_trail($id) {
        $holiday = $this->Holiday_model->get_holiday_by_id($id);
        if (!$holiday) {
            show_404();
        }

        $data['target_name'] = $holiday->Holiday_Name; 
        $data['back_url']    = 'holiday';
        $data['export_url']  = base_url('holiday/export_audit/' . $id);
        
        $data['audit_data']  = $this->Holiday_model->get_audit_trail($id);
        $data['total_rows']  = count($data['audit_data']);

        $this->load->view('audit/audit_view', $data); 
    }

    public function export() {
        $year = date('Y');

        // 1. BAGIAN API NASIONAL DIHAPUS (Sudah tidak mengambil dari vercel.app)
        $combined = [];

        // 2. Ambil data HANYA dari Database (Tabel tbl_holiday)
        $user_data = $this->db->get('tbl_holiday')->result_array();

        // 3. Masukkan data user ke array combined
        foreach ($user_data as $item) {
            $combined[] = [
                'name' => $item['Holiday_Name'],
                'date' => $item['Holiday_Date'],
                'type' => 'User Input',
                'desc' => $item['Holiday_Description']
            ];
        }

        // 4. Sort berdasarkan date
        usort($combined, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        $data['holidays'] = $combined;

        // 5. Header download Excel (.xls)
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Holiday_" . date('Y-m-d') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('holiday_export', $data);
    
    }
}