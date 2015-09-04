<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library(array('session','upload','parser'));
        $this->load->model('m_admin');
        $this->load->helper(array('form','transaksi','url'));
        session_start();
        if($this->session->userdata('login'))
        {
            //mengambil nama dari session
            $session = $this->session->userdata('login');
            $data['no_telp'] = $session['no_telp'];

        }else{
            redirect('login','refresh');   
        }
	}

	function do_upload()
	{
		    $config['upload_path'] = '../skripsi/upload/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['file_name'] = url_title($this->input->post('gambar'));
			$config['overwrite'] = FALSE;
			$config['encrypt_name'] = TRUE;
			
		$this->upload->initialize($config);
	}

	public function index()

	{
		if($this->session->userdata('login'))
        {
            //mengambil nama dari session
            $session = $this->session->userdata('login');
            $data['no_telp'] = $session['no_telp'];

        }else{
            redirect('login','refresh');   
        }

		$data['title'] = "Pemesanan Bus Pariwisata | Beranda";
		$data['judul'] = "Selamat Datang di Dashboard";
		$data['content'] = $this->load->view('dashboard_index', $data, TRUE);
		$this->parser->parse('dashboard', $data);
	}


    public function list_fasilitas()
	{

		$data['title'] = "Pemesanan Bus Pariwisata | Fasilitas";
		$data['judul'] = "Daftar Fasilitas";
		$data['hasil'] = $this->m_admin->get_fasilitas();
		$data['content'] = $this->load->view('dashboard_list_fasilitas', $data, TRUE);
		$this->parser->parse('dashboard', $data);
	}

		public function add_fasilitas()
	{
		$data['title'] = "Pemesanan Bus Pariwisata | Fasilitas";
		$data['judul'] = "Tambah Fasilitas";

		if($this->form_validation->run() == FALSE){
		$data['content'] = $this->load->view('dashboard_add_fasilitas', $data, TRUE);

		$this->do_upload();
		$this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fasilitas', 'Fasilitas', 'trim|required|xss_clean');
		$this->form_validation->set_rules('kursi', 'Kursi', 'trim|required|xss_clean');
		
		if( $this->form_validation->run() == FALSE || !$this->upload->do_upload('gambar') )
		{
			$error = array('error' => $this->upload->display_errors());
			/*$this->load->view('dashboard_add_slider',$error);*/
		}
		else
		{
			$data = array('gambar' => $this->upload->file_name,
				'type' => $this->input->post('type'),
				'fasilitas' => $this->input->post('fasilitas'),
				'kursi' => $this->input->post('kursi'),
				);
			$this->m_admin->insert_fasilitas($data);

			redirect('admin/list_fasilitas');
		}
	}
		
		$this->parser->parse('dashboard', $data);
	}

	function update_fasilitas(){

		$data['title'] = "Pemesanan Bus Pariwisata | Fasilitas";
		$data['judul'] = "Ubah Fasilitas";   
	    
		
		$id=$this->uri->segment(3);
	    $data['data']=$this->m_admin->get_by_id_fasilitas($id);

		//if($this->form_validation->run() == FALSE){

			$data['content'] = $this->load->view('dashboard_update_fasilitas', $data, TRUE);

		//}		
		$this->parser->parse('dashboard', $data);
	}

	public function ubah()
	{
		$id = $this->input->post('id_fasilitas');
		$data_fasilitas = array(
							'type' => $this->input->post('type'),
							'fasilitas' => $this->input->post('fasilitas'),
							'kursi' => $this->input->post('kursi'),
							);
             
	//print_r($data_fasilitas);exit();

	$this->m_admin->update_fasilitas($data_fasilitas,$id);
	     
			redirect('admin/list_fasilitas');
			
		// 	//$this->do_upload();
		// 	$this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
		// 	$this->form_validation->set_rules('fasilitas', 'Fasilitas', 'trim|required|xss_clean');
		// 	$this->form_validation->set_rules('kursi', 'Kursi', 'trim|required|xss_clean');
			
		// if( $this->form_validation->run() == FALSE || !$this->upload->do_upload('gambar') )
		// {
		// 	$error = array('error' => $this->upload->display_errors());
		// 	$this->load->view('dashboard_add_slider',$error);
		// }else{
		// 	$id = $this->input->post('type');
		// 	$data_fasilitas = array(//'gambar' => $this->upload->file_name,
		// 					'type' => $this->input->post('type'),
		// 					'fasilitas' => $this->input->post('fasilitas'),
		// 					'kursi' => $this->input->post('kursi')
		// 					);

		// 	print_r($data_fasilitas);exit();
		// 	$this->m_admin->update_fasilitas($data_fasilitas,$id);
			
		// 	redirect('admin/list_fasilitas');
		// }
	}

	public function hapus_fasilitas()
	{
		$id = $this->uri->segment(3);
		$path = $this->uri->segment(4);
		$data = $this->m_admin->delete_fasilitas($id);
		// $pathh = '../eri/upload/'.$path;
		// unlink($pathh);
		if($data == TRUE)
		{
			redirect('admin/list_fasilitas');
		}
		else
		{
			echo 'Data anda gagal dihapus!!';
		}
	}
    
	public function list_sewa()
	{

		$data['title'] = "Pemesanan Bus Pariwisata | Sewa";
		$data['judul'] = "Daftar sewa";
		$data['hasil'] = $this->m_admin->get_sewa();
		$data['content'] = $this->load->view('dashboard_list_sewa', $data, TRUE);
		$this->parser->parse('dashboard', $data);
	}

	
		

	public function add_sewa()
	{
		$data['title'] = "Pemesanan Bus Pariwisata | Sewa";
		$data['judul'] = "Tambah Sewa";
	
		$this->form_validation->set_rules('tujuan', 'Tujuan', 'trim|required|xss_clean');
		$this->form_validation->set_rules('harga', 'Harga', 'trim|required|xss_clean');

		if($this->form_validation->run() == FALSE){
			$data['content'] = $this->load->view('dashboard_add_sewa', $data, TRUE);
		}else{
			
			$data_sewa = array(
						'tujuan' => $this->input->post('tujuan'),
						'harga' => $this->input->post('harga'),
						 );

			$input = $this->m_admin->insert_sewa($data_sewa);
			if($input == FALSE){
				redirect('admin/list_sewa');
			}else{
				echo 'Data Gagal Disimpan!';
			}
		}
		$this->parser->parse('dashboard', $data);
		
	}

	
	function update_sewa(){

		$data['title'] = "Pemesanan Bus Pariwisata | Sewa";
		$data['judul'] = "Ubah Fasilitas";   

		$id =$this->uri->segment(3);
	    $data['data']=$this->m_admin->getEdit($id);


	    $data['content'] = $this->load->view('dashboard_update_sewa', $data, TRUE);
	    
	    $this->parser->parse('dashboard', $data);
	}

	    public function update()
	   {

		$id = $this->input->post('id_sewa');
		$data_sewa = array(	
					'tujuan' => $this->input->post('tujuan'),
					'harga' => $this->input->post('harga'),
							);

		$this->m_admin->update_sewa($data_sewa,$id);
			redirect('admin/list_sewa');

		
}




	//	$data['title'] = "Pemesanan Bus Pariwisata | Sewa";
		//$data['judul'] = "Ubah Sewa";   

		// $data['hasil'] = $this->m_admin->get_sewa();
	    
		//$this->form_validation->set_rules('tujuan', 'Tujuan', 'trim|required|xss_clean');
		

		//$id=$this->uri->segment(3);
       // $data['data']=$this->m_admin->get_by_id_sewa($id);

		//if($this->form_validation->run() == FALSE){
			//$data['content'] = $this->load->view('dashboard_update_sewa', $data, TRUE);
		//}else{
			//$id = $this->input->post('tujuan');
			//$data_sewa = array(	
							//'tujuan' => $this->input->post('tujuan'),
							//'harga' => $this->input->post('harga'),
							//);

			//$this->m_admin->update_sewa($data_sewa,$id);
			//redirect('admin/list_sewa');
		//}	//	
		//$this->parser->parse('dashboard', $data);
	//}
	

	public function hapus_sewa()
	{
		$id = $this->uri->segment(3);
		$datawisata = $this->m_admin->delete_sewa($id);
		if($datawisata == TRUE)
		{
			redirect('admin/list_sewa');
		}
		else
		{
			echo 'Data anda gagal dihapus!!';
		}
	}

	public function list_pesan()
	{

		$data['title'] = "Pemesanan Bus Pariwisata | Pemesanan";

		$data['judul'] = "Daftar Pemesanan";
		
		$data['hasil'] = $this->m_admin->get_pesan();
 		
		$data['content'] = $this->load->view('dashboard_list_pesan', $data, TRUE);

		$this->parser->parse('dashboard', $data);
	}


	function update_pesan(){
		$data['title'] = "Pemesanan Bus Pariwisata | Pemesanan";
		$data['judul'] = "Ubah Slider";   
	    
			$id=$this->uri->segment(3);
	        $data['data']=$this->m_admin->get_by_id_pesan($id);
           
	        if($this->form_validation->run() == FALSE){
			$data['content'] = $this->load->view('dashboard_update_pesan', $data, TRUE);

			}
			else
			{
				$id = $this->input->post('id_pesan');
				$data = array(
					
					'no_telp' => $this->input->post('no_telp'),
					'nama' => $this->input->post('nama'),
					'alamat' => $this->input->post('alamat'),
					'tujuan' => $this->input->post('tujuan'),
					'tgl_berangkat' => $this->input->post('tgl_berangkat'),
					'tgl_kembali' => $this->input->post('tgl_kembali'),
					'kursi' => $this->input->post('kursi'),
					'jum_armada' => $this->input->post('jum_armada'),
					'jam_jemput' => $this->input->post('jam_jemput'),
					'alamat_jemput' => $this->input->post('alamat_jemput'),
					'biaya' => $this->input->post('biaya'),
					'dp' => $this->input->post('dp'),
					'tgl_pelunasan' => $this->input->post('tgl_pelunasan'),
					);

				 $this->m_admin->update_pesan($data,$id);

				
              redirect('admin/list_pesan');
				
			}
		
        $this->parser->parse('dashboard', $data);	
	}
	public function get_update_pesan(){

		$id = $this->input->post('id_pesan');
		$data_pesan = array(
			'alamat' => $this->input->post('alamt'),);

		$this->m_admin->update_pesan($data_pesan, $id);
        

        redirect('admin/list_pesan');

	}


	public function delete_pesan()
	{
		$id = $this->uri->segment(3);

		$data = $this->m_admin->delete_pesan($id);
		
		if($data == TRUE)
		{
			redirect('admin/list_pesan');
		}
		else
		{
			echo 'Data anda gagal dihapus!!';
		}
	}

	public function list_user()
	{

		$data['title'] = "Pemesanan Bus Pariwisata | Sewa";
		$data['judul'] = "Daftar User";

		$data['hasil'] = $this->m_admin->get_user();

		$data['content'] = $this->load->view('dashboard_list_user', $data, TRUE);
		$this->parser->parse('dashboard', $data);
	}

	public function add_user()
	{

		$data['title'] = "Pemesanan Bus Pariwisata | Sewa";
		$data['judul'] = "Tambah User";

		$this->form_validation->set_rules('id_pers', 'Id Pers', 'trim|required|xss_clean');
		$this->form_validation->set_rules('nama_pers', 'Nama Pers', 'trim|required|xss_clean');
		$this->form_validation->set_rules('alamat_pers', 'Alamat Pers', 'trim|required|xss_clean');
		$this->form_validation->set_rules('no_telp', 'No Telp', 'trim|required|xss_clean');
		$this->form_validation->set_rules('longitude', 'Longitude', 'trim|required|xss_clean');
		$this->form_validation->set_rules('latitude', 'Latitude', 'trim|required|xss_clean');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

		if($this->form_validation->run() == FALSE){
			$data['content'] = $this->load->view('dashboard_add_user', $data, TRUE);
		}else{
			$data1['id_pers'] = $this->input->post('id_pers');
			$data1['nama_pers'] = $this->input->post('nama_pers');
			$data1['alamat_pers'] = $this->input->post('alamat_pers');
			$data1['no_telp'] = $this->input->post('no_telp');
			$data1['longitude'] = $this->input->post('longitude');
			$data1['latitude'] = $this->input->post('latitude');
			$data1['username'] = $this->input->post('username');
			$data1['password'] = md5($this->input->post('password'));

			$datauser = array(
						'id_pers' => $data1['id_pers'],
						'nama_pers' => $data1['nama_pers'],
						'alamat_pers' => $data1['alamat_pers'],
						'no_telp' => $data1['no_telp'],
						'longitude' => $data1['longitude'],
						'latitude' => $data1['latitude'],
						'username' => $data1['username'],
						'password' => $data1['password'],
						 );

			$input = $this->m_admin->add_user($datauser);
			if($input == TRUE){
				redirect('admin/list_user');
			}else{
				echo 'Data Gagal Disimpan!';
			}
		}
		$this->parser->parse('dashboard', $data);
		
	}

	public function update_user()
	{

		$data['title'] = "Pemesanan Bus Pariwisata | Sewa";
		$data['judul'] = "Update User";
		$this->form_validation->set_rules('no_telp', 'No Telp', 'trim|required|xss_clean');
		$id=$this->uri->segment(3);
        $data['data']=$this->m_admin->get_by_id_user($id);

		if($this->form_validation->run() == FALSE){
			$data['content'] = $this->load->view('dashboard_update_user', $data, TRUE);
		}else{
			$id = $this->input->post('no_telp');
			$data = array(
							'id_pers' => $this->input->post('id_pers'),
							'nama_pers' => $this->input->post('nama_pers'),
							'alamat_pers' => $this->input->post('alamat_pers'),
							'no_telp' => $this->input->post('no_telp'),
							'longitude' => $this->input->post('longitude'),
							'latitude' => $this->input->post('latitude'),
							'username' => $this->input->post('username')
							);
			$password = $this->input->post('password');
			if(!empty($password))
			{
				$data['password'] = md5($this->input->post('password'));
			}

			$this->m_admin->update_user($data, $id);
			
			redirect('admin/list_user');
			
		}
		$this->parser->parse('dashboard', $data);
	}
	
	function delete_user($id){
        $this->m_admin->delete_user($id);
        if($this == TRUE){
				redirect('admin/list_user');
			}else{
				echo 'Data Gagal Dihapus!';
			}   
        
    }



	}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */