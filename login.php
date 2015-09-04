<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//nama class harus sama dengan nama file dan diawali dengan huruf besar
class Login extends CI_Controller {

    public function index()
    {
            $this->load->library('session');
            $this->load->helper('url');
            //jika seasson login belum ada maka tampilkan login
            if(!$this->session->userdata('login')){
                //mengambil helpher form. digunakan di v_login.php untuk membuat element form.
                $this->load->helper('form');
                //tampilan html login
                $this->load->view('v_login');
            }else
            {
                //jika seasson ada direct ke home
                redirect('admin','refresh');
            }
    }

    public function logout(){
        $this->load->library('session');
        $this->load->helper('url');
        $this->session->unset_userdata('login');
        redirect('login','refresh');
    }
}