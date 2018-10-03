<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}
	public function profile()
	{
		echo "saya adalah profile";
	}
	public function simpan()
	{
		$data = array(
			"id_user"=>"",
			"nama_user"=>"Suryaningsih Patandung",
			"email_user"=>"suryaningsihpatandung@gmail.com",
			"password_user"=>"Uya080696!",
			"telepon_user"=>"085399250048",
			"alamat_user"=>"Depok",
			"gender_user"=>"Perempuan",
			"ttl_user"=>""
		);
		$this->manufriend_model->mm_insert_user($data);
		echo "sudah masuk";
	}
	public function hapus($idgue)
	{
		$data = array(
			"id_user"=>$idgue
		);
		$this->manufriend_model->mm_delete_user($data);
		echo "sudah dihapus";
	}
	public function memperbaharui($idgue)
	{
		$data = array(
			"id_user"=>$idgue
		);
		$this->manufriend_model->mm_update_user($data);
		echo "sudah diupdate";
	}


	public function proses_login(){

		$var_usermail = $this->input->post("email");
		$var_userpassword = $this->input->post("password");

		$is_exist =	$this->manufriend_model->mm_cek_user($var_usermail, $var_userpassword);
		$user_profile = $this->manufriend_model->mm_data_user($var_usermail, $var_userpassword);

		if($is_exist>0){
				$arrayName = 	
				array(
							 'email'=>$user_profile->email_user,
							 'status'=> "logged",
							 'role_user'=>$user_profile->role_user
			    );
				$this->session->set_userdata($arrayName);	
			echo "data ada";
		}
		else{
			echo "data belum ada";
		}
		$data['sess_email'] = $this->session->userdata("email");
		$this->dashboard($data);
	}

	public function login(){
			$this->load->view("view_login");
	}

	public function logout(){
        $this->session->sess_destroy();
        redirect('/');
    }

	public function dashboard($data){
		$pass["valemail"]=$data["sess_email"];
		if($pass!="" || $pass!=null){

			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/04_view_main", $pass);
			$this->load->view("admin/05_view_footer");
		}
		else
		{
			redirect('/');
		}
	}


	// TRANSACTION
	public function transaction(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_transaction();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/06_view_transaction", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_transaction(){
		$pass["data"]=$this->manufriend_model->mm_show_transaction();
		echo json_encode($pass);
	}

	// REQUEST
	public function request(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_request();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/08_view_request", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_request(){
		$pass["data"]=$this->manufriend_model->mm_show_request();
		echo json_encode($pass);
	}

	// ON GOING
	public function ongoing(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_ongoing();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/09_view_ongoing", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_ongoing(){
		$pass["data"]=$this->manufriend_model->mm_show_ongoing();
		echo json_encode($pass);
	}

	// DONE
	public function done(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_done();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/10_view_done", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_done(){
		$pass["data"]=$this->manufriend_model->mm_show_done();
		echo json_encode($pass);
	}

	public function button_approve($id_trx){
		$data="";
		if($id_trx!=""||$id_trx!=null){
			$this->manufriend_model->mm_update_transaction_to_ongoing($id_trx);
			redirect("welcome/request");
		}
		else{
			$data["response"]=404;
			$data["message"]="Gagal proses";
		}
	}
	
	public function button_done($id_trx){
		$data="";
		if($id_trx!=""||$id_trx!=null){
			$this->manufriend_model->mm_update_transaction_to_done($id_trx);
			redirect("welcome/ongoing");
		}
		else{
			$data["response"]=404;
			$data["message"]="Gagal proses";
		}
	}

	public function button_deny_request($id_trx)
	{
		$data="";
		if($id_status=1){
			$this->manufriend_model->mm_deny_request_trx($id_trx);
			redirect("welcome/request");
		}
		else{
			$data["response"]=404;
			$data["message"]="Gagal proses";
		}
	}


	public function button_cancel_ongoing($id_trx){
		$data="";
		if($id_status=2){
			$this->manufriend_model->mm_update_cancel_ongoing($id_trx);
			redirect("welcome/ongoing");
		}
		else{
			$data["response"]=404;
			$data["message"]="Gagal proses";
		}
	}

	//CUSTOMER
	public function user(){
		$pass["valemail"]= $this->session->userdata["email"];
		$pass["values"]= $this->manufriend_model->mm_show_user();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/07_view_user", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_user(){
		$pass["data"]=$this->manufriend_model->mm_show_user();
		echo json_encode($pass);
	}

	/*public function button_save_user(){
		$data = array(
			"id_user"=>"",
			"nama_user"=>"Suryaningsih Patandung",
			"email_user"=>"suryaningsihpatandung@gmail.com",
			"password_user"=>"Uya080696!",
			"telepon_user"=>"085399250048",
			"alamat_user"=>"Depok",
			"gender_user"=>"Perempuan",
			"ttl_user"=>""
		);
		$this->manufriend_model->mm_insert_user($data);
		echo "sudah masuk";
	}*/

	//YANG BELUM : BUTTON SAVE, BUTTON RESET, DAN UPDATE

	public function button_delete_user($id_user)
	{
		$data = array(
			"id_user"=>$id_user
		);
		$this->manufriend_model->mm_delete_user($data);
		redirect("welcome/user");
	}

	// HANG OUT
	public function hang_out(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_hang_out();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/11_view_hang_out", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_hang_out(){
		$pass["data"]=$this->manufriend_model->mm_show_hang_out();
		echo json_encode($pass);
	}

	// SHOPPING
	public function shopping(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_shopping();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/12_view_shopping", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_shopping(){
		$pass["data"]=$this->manufriend_model->mm_show_shopping();
		echo json_encode($pass);
	}

	// CHIT-CHAT
	public function chit_chat(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_chit_chat();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/13_view_chit_chat", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_chit_chat(){
		$pass["data"]=$this->manufriend_model->mm_show_chit_chat();
		echo json_encode($pass);
	}

	// SPORT
	public function sport(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_sport();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/14_view_sport", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_sport(){
		$pass["data"]=$this->manufriend_model->mm_show_sport();
		echo json_encode($pass);
	}

	// ATTENDING A PARTY
	public function attending_party(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_attending_party();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/15_view_attending_party", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_attending_party(){
		$pass["data"]=$this->manufriend_model->mm_show_attending_party();
		echo json_encode($pass);
	}

	// OTHERS
	public function others(){
		$pass["valemail"]= $this->session->userdata["email"];

		$pass["values"]= $this->manufriend_model->mm_show_others();
		//echo json_encode($pass)
		
			$this->load->view("admin/01_view_head");
			$this->load->view("admin/02_view_header");
			$this->load->view("admin/03_view_sidebar", $pass);
			$this->load->view("admin/16_view_others", $pass);
			$this->load->view("admin/05_view_footer");
	}

	public function api_others(){
		$pass["data"]=$this->manufriend_model->mm_show_others();
		echo json_encode($pass);
	}



	public function register(){
		$dataParsing = array(
			"id_user"=>"",

			//andri diganti dengan $this->input->post('namauser');
			"nama_user"=>"uya",
			"email_user"=>"uya@gmail.com",
			"password_user"=>"uyauyauya",
			"telepon_user"=>"085399250048",
			"alamat_user"=>"Depok",
			"gender_user"=>"Perempuan",
			"ttl_user"=> date('y-m-d'),
			"role_user"=> 2
		);

		$this->manufriend_model->mm_insert_new_user($dataParsing);
		echo json_encode($dataParsing);
	}



// DI BAWAH INI ADALAH FUNCTION UNTUK API ANDROID
	public function api_register_user(){
		$data="";
		$dataParsing = array(
			"id_user"=>"",

			//andri diganti dengan $this->input->post('namauser');
			"nama_user"=>"Tesningsih",
			"email_user"=>"tesningsih@gmail.com",
			"password_user"=>"uyauyauya",
			"telepon_user"=>"085399250048",
			"alamat_user"=>"Jakarta",
			"gender_user"=>"Perempuan",
			"ttl_user"=> date('y-m-d'),
			"role_user"=> 2
		);

		$cekemail = $this->manufriend_model->mm_cek_email($dataParsing['email_user']);

		if($dataParsing!=null && $cekemail<1){
			$data["response"] = 200;
			$data["message"] = "Data Anda berhasil didaftarkan";
			$this->manufriend_model->mm_insert_new_user($dataParsing);
		}
		else if($cekemail>0){
			$data["response"] = 404;
			$data["message"] = "Data Anda sudah pernah didaftarkan";
		}
		else{
			$data["response"] = 404;
			$data["message"] = "Koneksi gagal";
		}

		echo json_encode($data);
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */