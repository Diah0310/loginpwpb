<?php
defined('BASEPATH') or exit('No direct script access allowed');

class admin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    is_logged_in();
  }

  public function index()
  {
    $data['tittle'] = 'Dashboard';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->Userdata('email')])->row_array();


    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('templates/topbar', $data);
    $this->load->view('admin/dashboard', $data);
    $this->load->view('templates/footer');
  }


  public function role()
  {
    $data['tittle'] = 'Role';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->Userdata('email')])->row_array();

    $data['role'] = $this->db->get('user_role')->result_array();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('templates/topbar', $data);
    $this->load->view('admin/role', $data);
    $this->load->view('templates/footer');
  }


  public function roleAccess($role_id)
  {
    $data['tittle'] = 'Role Access';
    $data['user'] = $this->db->get_where('user', ['email' => $this->session->Userdata('email')])->row_array();

    $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

    $this->db->where('id !=', 1);
    $data['menu'] = $this->db->get('user_menu')->result_array();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar', $data);
    $this->load->view('templates/topbar', $data);
    $this->load->view('admin/role-access', $data);
    $this->load->view('templates/footer');
  }


  public function changeAccess()
  {
    $menu_id = $this->input->post('menuId');
    $role_id = $this->input->post('roleId');

    $data = [
      'role_id' => $role_id,
      'menu_id' => $menu_id
    ];

    $result = $this->db->get_where('user_access_menu', $data);

    if ($result->num_rows() < 1) {
      $this->db->insert('user_access_menu', $data);
    } else {
      $this->db->delete('user_access_menu', $data);
    }
    $this->session->set_flashdata('message', '<div class="alert alert-success" 
    role="alert">Access Changed!</div>');
  }

  //edit Role
  public function editRole()
  {
    $this->form_validation->set_rules('role', 'Role', 'required|trim');
    if ($this->form_validation->run() == false) {
      $this->session->set_flashdata('error', "Data Gagal di Edit");
      redirect('admin/role');
    } else {
      $this->db->set('role', $this->input->post('role'));
      $this->db->where('id', $this->input->post('id'));
      $this->db->update('user_role');
      $this->session->set_flashdata('message', "Data Berhasil Diedit");
      redirect('admin/role');
    }
  }
  public function delete($id)
  {
    $this->db->delete('user_role', ['id' => $id]);
    $this->session->set_flashdata('message', "Data Berhasil di Hapus");
    redirect('admin/role');
  }
}
