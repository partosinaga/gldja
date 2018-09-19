<?php defined('BASEPATH') OR exit('No direct script access allowed');

class jv extends CI_Controller{
	function __Construct(){
	parent ::__construct();
	//$this->load->model('ap/M_ap');
	$this->load->model('ar/M_ar');
	$this->load->model('jv/M_jv');
	$this->load->model('ap/M_ap');
	$this->load->library(array('import/PHPExcel','import/PHPExcel/IOFactory'));
	$this->load->helper('url');
		 
	}

	public function view_jv(){
		if($this->session->userdata('username') == null){
            redirect('home');
        }
		$data['page']='jv/view_jv';
		
		$data['bank']= $this->M_ar->get_bank();
		//get form  table currency
		$data['curr']= $this->db->get('currency');
		//get  table coa
		$data['coa1']= $this->db->get('coa');
		//get  table coa
		$data['coa']= $this->M_ar->get_coa('coa');
		//get kode otomatis
		$data['kode'] = $this->M_jv->get_kode();

		$this->load->view('template/template', $data);
	}

	public function input_jv(){
			 $data['no_voucher'] = $_POST['no_voucher'];
			 $data['date'] = $_POST['date'];
	         $data['bank_id'] = $_POST['bank_id'];
	         $data['description'] = $_POST['description'];
	         $data['curr_id'] = $_POST['curr_id'];
	         $data['total'] = $_POST['total'];
	         $data['kurs'] = $_POST['kurs'];
	         $data['receive_from'] = $_POST['receive_from'];
	         $data['no_cek'] = $_POST['no_cek'];
	         $data['gl_date'] = $_POST['gl_date'];
	         $data['status'] = $_POST['status'];
	         $data['audit_user'] =$this->session->userdata('username');
	         $data['audit_date'] = date("Y-m-d H:i:sa");

	         $this->db->insert('jv_header', $data);
	         
	        $no_vouc = $this->input->post('no_vouc');
	        $coa_id = $this->input->post('coa_id');
	        $desc = $this->input->post('desc');
			$debit = $this->input->post('debit');
			$credit = $this->input->post('credit');

			$datax =array();
			for($i=0; $i<count($coa_id); $i++) {
			$datax[$i] = array(
	           'coa_id' => $coa_id[$i],
	           'description' => $desc[$i],
	           'no_voucher' => $no_vouc[$i],
	           'debit' => $debit[$i],
	           'credit' => $credit[$i],

	           );
			}
			//print_r($datax);
			//exit;
			$this->db->insert_batch('jv_detail', $datax);
			
			$this->session->set_flashdata('success', 'Journal voucher saved!');
			redirect('jv/jv/view_jv');
	}

		public function jv_list(){
			if($this->session->userdata('username') == null){
            redirect('home');
        }
			$data['page'] = 'jv/jv_list';
			$data['jvList'] = $this->M_jv->get_jv();
			$this->load->view('template/template', $data);
		}

		public function detail_jv(){
			if (isset($_POST["transaction_detail"])) 
			{
				$output = '';
				$connect = mysqli_connect("localhost", "root", "", "gldja");
				$query =  "SELECT * from jv_header WHERE no_voucher =  '".$_POST["transaction_detail"]."' ";
				$result = mysqli_query($connect, $query);

				$query2 =  "SELECT jv_detail.coa_id, coa.name_coa, jv_detail.debit, jv_detail.credit
							from jv_detail 
							join coa
							on jv_detail.coa_id = coa.coa_id
							where jv_detail.no_voucher= '".$_POST["transaction_detail"]."' ";
				$result2 = mysqli_query($connect, $query2);
				

				$output .= '
				<div class="">
					<table class="table table-striped table-bordered table-hover dataTable table-po-detail">';

				while($row = mysqli_fetch_array($result)) {
					$output .= '
					 <div class="row">

					  <div class="col-md-3">
					
							<label>VOUCHER NO.</label><br>
							'.$row["no_voucher"].'
						
					  </div>

					  <div class="col-md-3">
						
							<label>DATE</label><br>
						'.$row["date"].'
						
					  </div>

					  <div class="col-md-3">
						
							<label>BANK CODE</label><br>
							'.$row["bank_id"].'
						
					  </div>

					 </div><br>

					 <div class="row">
                		<div class="col-md-12">
                  			<label>Desription</label><br>
                      		'.$row["description"].'            
                		</div>
              		 </div><br>



              		 <div class="row">

					  <div class="col-md-3">
					
							<label>CURRENCY</label><br>
							'.$row["curr_id"].'
						
					  </div>

					  <div class="col-md-3">
						
							<label>TOTAL</label><br>
						'.number_format($row["total"]).'
						
					  </div>

					  <div class="col-md-3">
						
							<label>EXCHANGE RATE</label><br>
							'.$row["kurs"].'
						
					  </div>

					 </div><br>



					 <div class="row">

					  <div class="col-md-3">
					
							<label>Paid To</label><br>
							'.$row["receive_from"].'
						
					  </div>

					  <div class="col-md-3">
						
							<label>NO.CEL/GIRO</label><br>
						'.$row["no_cek"].'
						
					  </div>

					  <div class="col-md-3">
						
							<label>GL. DATE</label><br>
							'.$row["gl_date"].'
						
					  </div>

					  <i>created by:'.$row['audit_user'].'</i>

					 </div><br>
					 <table class="table">
					 <tr bgcolor="#578EBE">
					 	<td width="200px" align="center"><b>ACCOUNT</td>
					 	<td align="center"><b>DESCRIPTION</td>
					 	<td width="200px" align="center"><b>DEBIT</td>
					 	<td width="200px" align="center"><b>CREDIT</td>
					 </tr>
					';


				};


				while($row2 = mysqli_fetch_array($result2)) {
					$output .= '
					 <div class="row">
					 
					  <tr>
					 	<td>'.$row2["coa_id"].'</td>
					 	<td>'.$row2["name_coa"].'</td>
					 	<td align="right">'. number_format($row2['debit']).'</td>
					 	<td align="right">'. number_format($row2['credit']).'</td>
					 </tr>
					 </div>
					';

				
				};


				$output .= "</table></div>";
				echo $output;

			};
		}

		public function print_jv(){

			$no_voucher=$this->input->get('id');

			$data['terbilang'] = $this->M_jv->terbilang($no_voucher);
			//get headet
			$data['header'] = $this->M_jv->get_header($no_voucher);
			//get detail
			$data['detail'] = $this->M_jv->get_detail($no_voucher);
			$data['totalDetail'] = $this->M_jv->get_totalDetail($no_voucher);
			//get sytem parameter
			$data['syspar'] = $this->M_ar->get_syspar();
			$this->load->view('jv/jv_print', $data);
		}

		public function print_jv_up(){

			$no_voucher=$this->input->get('id');

			$data['terbilang'] = $this->M_jv->terbilang($no_voucher);
			
			//get headet
			$data['header'] = $this->M_jv->get_header($no_voucher);
			//get detail
			$data['detail'] = $this->M_jv->get_detail($no_voucher);
			$data['totalDetail'] = $this->M_jv->get_totalDetail($no_voucher);
			//get sytem parameter
			$data['syspar'] = $this->M_ar->get_syspar();
			$this->load->view('jv/jv_print_up', $data);
		}
		public function posting(){
			if($this->session->userdata('username') == null){
            redirect('home');
        }
			$data['page'] = 'jv/posting';
			$data['postlist'] = $this->M_jv->get_postList();
			$this->load->view('template/template', $data);
		}

		public function save_posting(){		
			$this->db->trans_start();
			$valid = true;	
			$noVoc=$this->input->post('noVoc');
			$posted_no=$this->input->post('posted_no');
			$gl_date=$this->input->post('gl_date');
			$audit_user = $this->session->userdata('username');
			$audit_date = date('Y-m-d H:i:s');
		
				$q = $this->db->query("
		          SELECT
		            MAX( RIGHT ( gl_no, 4 ) ) AS kt 
		          FROM
		            gl_header 
		          WHERE
		            Fmodule = 'JV' 
		            AND MONTH ( gl_date ) = MONTH ( '".$gl_date."' ) 
		            AND YEAR ( gl_date ) = YEAR ( '".$gl_date."' );
		          ");
				$kd="";
				$posted_no="";
				$tgl= date("my");
				$gld = New DateTime($gl_date);
        		$kd2 = "6".$gld->format('my');
				if($q->num_rows()>0){
					foreach ($q->result() as $k){
						$tmp = ((int)$k->kt)+1;
						$kd = sprintf("%04s", $tmp);
					}
				}else {
					$kd = "6".$gld->format('my')."0001";
				}
				$posted_no = $kd2.$kd;
				
				$glh = $this->M_ap->cek_header($posted_no);
				$gld = $this->M_ap->cek_detail($posted_no);

				if ( count($glh) > 0 OR count($gld) > 0 ) {
					$this->session->set_flashdata('error', 'Reposting Failed, Try again later(1)!');
					$valid = false;
				}else {
					//1. to update status JV
					$data = $this->M_jv->save_posting($noVoc, $posted_no);
				
					$gl_no 			= $posted_no;
					$gl_date		= $gl_date;
					$noVoc 			= $this->input->post('noVoc');
					$description 	= $this->input->post('description');
					$total 			= $this->input->post('total');
					$Fmodule		= "JV";
					$Fmonth 		= date("m");
					$Fyear 			= date("Y");
					$status 		= "posted";
					$audit_user		= $audit_user;
					$audit_date		= $audit_date;


					$data = array(
						'gl_no' 	=> $gl_no,
						'gl_date'	=> $gl_date,
						'reff_no'	=> $noVoc,
						'description'=>	$description,
						'total'		=> $total,
						'Fmodule'	=> $Fmodule,
						'Fmonth'	=> $Fmonth,
						'Fyear'		=> $Fyear,
						'status'	=> $status,
						'audit_user'=> $audit_user,
						'audit_date'=> $audit_date
					);

					$this->M_jv->save_glHead($data, 'gl_header');
					if ($gl_no == '' OR empty($gl_no)) {
						$this->session->set_flashdata('error', 'Posting Failed, Try again later(2)!');
						$valid = false;
					}else {
						//3. move from ar detail to gl detail
						$data=$this->M_jv->save_glDetail($noVoc, $gl_no);
						$this->session->set_flashdata('success', 'POSTED!');
					}
					
				}
				// validation comit or rolback
				if($valid){
	                if ($this->db->trans_status() === FALSE)
	                {
	                    $this->db->trans_rollback();
	                    $this->session->set_flashdata('error', 'Reposting Failed, Try again later(3)');
	                }
	                else
	                {
	                    $this->db->trans_commit();
	                }
	            }else{
	                $this->db->trans_rollback();
	                $this->session->set_flashdata('error', 'Reposting Failed, Try again later(4)');
	            }	

				
				redirect('jv/jv/posting');
		}

		public function unposting(){
			if($this->session->userdata('username') == null){
            redirect('home');
        }
			$data['page'] = 'jv/unposting';
			$data['unpostlist'] = $this->M_jv->get_unposting();
			$this->load->view('template/template', $data);
		}

		public function save_unposting(){
			$id = $this->input->get('id');
			$data = $this->M_jv->save_unposting($id);
			$data = $this->M_jv->updateGLHposted($id);
			$this->session->set_flashdata('message', array('info', 'fa-check',  'SUCCESS', 'UNPOSTED!'));
			$this->session->set_flashdata('success', 'UNPOSTED!');
			redirect('jv/jv/unposting');
		}

		public function save_reposting(){
			$id = $this->input->get('id');
			$data = $this->M_jv->save_reposting($id);
			$data = $this->M_jv->updateGLHunposted($id);
			$this->session->set_flashdata('success', 'POSTED and keep journal number!');
			redirect('jv/jv/posting');
		}
		 
		public function save_upd_reposting(){
			$this->db->trans_start();
			$valid = true;
			$id = $this->input->get("no_voucher");
			$postedNo = $this->input->get("postedNo");
			$gl_date = $this->input->get("gl_date");
			
				$q = $this->db->query("
		          SELECT
		            MAX( RIGHT ( gl_no, 4 ) ) AS kt 
		          FROM
		            gl_header 
		          WHERE
		            Fmodule = 'JV' 
		            AND MONTH ( gl_date ) = MONTH ( '".$gl_date."' ) 
		            AND YEAR ( gl_date ) = YEAR ( '".$gl_date."' );
		          ");
		        $kd="";
		        $posted_no="";
		        $tgl= date("my");
		        $gld = New DateTime($gl_date);
		        $kd2 = "6".$gld->format('my');
		        if($q->num_rows()>0){
		          foreach ($q->result() as $k){
		            $tmp = ((int)$k->kt)+1;
		            $kd = sprintf("%04s", $tmp);
		          }
		        }else {
		          $kd = "6".$gld->format('my')."0001";
		        }
		       	$posted_no = $kd2.$kd;

			$glh = $this->M_ap->cek_header($posted_no);
			$gld = $this->M_ap->cek_detail($posted_no);
			if ( count($glh) > 0 OR count($gld) > 0 ) {
				$this->session->set_flashdata('error', 'Reposting Failed, Try again later(1)!');
				$valid = false;
			}else {
				//reupdate status in gl header and add posted no
				$data = $this->M_jv->save_upd_reposting($id, $posted_no);
				//update GL no in GL header
				//NB=> posted no on AR header = GL No in GL header 
				$data = $this->M_jv->save_upd_reposting2($id, $posted_no);
				//to update status in gl header
				$data = $this->M_jv->updateGLHunposted($id);

				//to update gl_no in GL detail
				$dadta = $this->M_jv->updateGlNoGlDetail($posted_no, $postedNo);

				$this->session->set_flashdata('success', 'POSTED and generate new journal number!');
			}
			
			// validation comit or rolback
				if($valid){
	                if ($this->db->trans_status() === FALSE)
	                {
	                    $this->db->trans_rollback();
	                    $this->session->set_flashdata('error', 'Reposting Failed, Try again later(2)');
	                }
	                else
	                {
	                    $this->db->trans_commit();
	                }
	            }else{
	                $this->db->trans_rollback();
	                $this->session->set_flashdata('error', 'Reposting Failed, Try again later(3)');
	            }	
	            
			redirect('jv/jv/posting');
		}

	public function edit_jv(){
		if($this->session->userdata('username') == null){
            redirect('home');
        }
			$no_voucher = $this->input->get('id');
			$data['page'] = 'jv/edit_jv';
			
		
			//get form  table bank
			$data['bank']= $this->M_ar->get_bank();
			//get form  table currency
			$data['curr']= $this->db->get('currency');
			//get  table coa
			//$data['coa1']= $this->db->get('coa');
			//get  table coa
			$data['coa']= $this->M_ar->get_coa('coa');
			$data['editjvh'] = $this->M_jv->get_jvh_edit($no_voucher);
			
			$data['editjvd'] = $this->M_jv->get_jvd_edit($no_voucher);
			$this->load->view('template/template', $data);
		}

	public function save_edit(){
			$id = $this->input->post('id');
			$date = $this->input->post('date');
			$no_voucher = $this->input->post('no_voucher');
			$bank_id = $this->input->post('bank_id');
			$description = addslashes($this->input->post('description'));
			$curr_id = $this->input->post('curr_id');
			$total = $this->input->post('total');
			$kurs = $this->input->post('kurs');
			$receive_from = $this->input->post('receive_from');
			$no_cek = $this->input->post('no_cek');
			$gl_date = $this->input->post('gl_date');
			
			// print_r($date);
			// exit();

			$data = array (
				'no_voucher'=> $no_voucher,
				'bank_id'=> $bank_id,
				'description'=> $description,
				'curr_id'=> $curr_id,
				'total'=> $total,
				'kurs'=> $kurs,
				'receive_from'=> $receive_from,
				'no_cek'=> $no_cek,
				'gl_date'=> $gl_date,
			);
			$this->M_jv->save_update_jvh($id, $bank_id, $date, $description, $curr_id, $total, $kurs, $receive_from, $no_cek, $gl_date,$no_voucher);

			$this->M_jv->save_update_glh($total, $description, $gl_date, $no_voucher);
			
			$no_voucher = $this->input->post('no_voucher');
			$posted_no = $this->input->post('posted_no');
			$desc = $this->input->post('desc');
	        $coa_id = $this->input->post('coa_id');
			$debit = $this->input->post('debit');
			$credit = $this->input->post('credit');

			$datax2 =array();
			for($a=0; $a<count($coa_id); $a++) {
			$datax2[$a] = array(
	           'coa_id' => $coa_id[$a],
	           'description' => $desc[$a],
	           'no_voucher' => $no_voucher,
	           'debit' => $debit[$a],
	           'credit' => $credit[$a],

	           );
			}
			$this->M_jv->delete_jvd_old($no_voucher);
			$this->db->insert_batch('jv_detail', $datax2);

			if (!empty($posted_no)) {
				$datax3 =array();
				for($a=0; $a<count($coa_id); $a++) {
				$datax3[$a] = array(
		           'gl_no' => $posted_no,
		           'coa_id' => $coa_id[$a],
		            'description' => $desc[$a],
		           'debit' => $debit[$a],
		           'credit' => $credit[$a],

		           );
				}
				$this->M_jv->delete_gld_old($posted_no);
				$this->db->insert_batch('gl_detail', $datax3);
			}

			$this->session->set_flashdata('success', 'Update success!');
			redirect('jv/jv/jv_list');

	}
	public function edit_glDate(){
		$nv = $this->input->post('nv');
		$d = $this->input->post('hari');
		$m = $this->input->post('bulan');
		$y = $this->input->post('tahun');
		
		$date = $y.'-'.$m.'-'.$d;
		
		$this->M_jv->edit_glDate($nv, $date);
		//edit in gl_header
		$this->M_jv->edit_glDate_glh($nv, $date);
	}
	function import(){
		$data['page'] = 'jv/import';
		$this->load->view('template/template', $data);
 	}
 	function download(){
    	$this->load->helper('download');
        force_download('./uploads/jv/jv_format.rar', NULL);
    	$this->session->set_flashdata('info', 'Your File Will Downloaded');
    }

    public function upload(){
        $fileName = $this->input->post('file', TRUE);
        $config['upload_path'] = './uploads/jv/'; 
        $config['file_name'] = "Header-".date('Y-m-d');
        $config['allowed_types'] = 'xls|xlsx|csv|ods|ots';
        $config['max_size'] = 10000;

        $this->load->library('upload', $config);
        $this->upload->initialize($config); 
          
        if (!$this->upload->do_upload('file')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error',  $error);
            redirect('jv/jv/import');
        } else {
            $media = $this->upload->data();
            $inputFileName = 'uploads/jv/'.$media['file_name'];
           
            try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++){  
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL,
                    TRUE,
                    FALSE);
             
                $data = array(
                    "no_voucher"=> $rowData[0][0],
                    "date"=> $rowData[0][1],
                    "bank_id"=> $rowData[0][2],
                    "description"=> $rowData[0][3],
                    "curr_id"=> $rowData[0][4],
                    "total"=> $rowData[0][5],
                    "kurs"=> $rowData[0][6],
                    "receive_from"=> $rowData[0][7],
                    "no_cek"=> $rowData[0][8],
                    "gl_date"=> $rowData[0][9],
                    "status" => "post",
                    "audit_user" => $this->session->userdata('username'),
                    "audit_date" => date("Y-m-d H:i:sa")
                );
                $this->db->insert("jv_header",$data);
            } 
            $this->session->set_flashdata('info','UPLOADED'); 
            redirect('jv/jv/import');
        }  
    }

    public function upload2(){
        $fileName = $this->input->post('file', TRUE);
        $config['upload_path'] = './uploads/jv/'; 
        $config['file_name'] = "Detail-".date('Y-m-d');
        $config['allowed_types'] = 'xls|xlsx|csv|ods|ots';
        $config['max_size'] = 10000;

        $this->load->library('upload', $config);
        $this->upload->initialize($config); 
          
        if (!$this->upload->do_upload('file')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error',  $error);
            redirect('jv/jv/import');
        } else {
            $media = $this->upload->data();
            $inputFileName = 'uploads/jv/'.$media['file_name'];
           
            try {
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++){  
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL,
                    TRUE,
                    FALSE);
             
                $data = array(
                    "no_voucher"=> $rowData[0][0],
                    "description"=> $rowData[0][1],
                    "coa_id"=> $rowData[0][2],
                    "debit"=> $rowData[0][3],
                    "credit"=> $rowData[0][4],
                 
                );
                $this->db->insert("jv_detail",$data);
            } 
            $this->session->set_flashdata('info','UPLOADED'); 
            redirect('jv/jv/import');
        }  
    }
    public function cancel_jv(){
    	$id = $this->input->get('id');
    	$status = $this->input->get('status');
    	
    	if ($status == 'post') {
    		//cancel ar_header. belum ada di table gl
    		$this->M_jv->cancel_jv($id);
    	} else {
    		//cancel ar_header
	    	$this->M_jv->cancel_jv_post($id);
	    	//cancel gl_header 
	    	$this->M_jv->cancel_glh($id);
	    	//cancel gl_detil
	    	$this->M_jv->cancel_gld($id);
    	}
    	
    	$this->session->set_flashdata('success', 'Voucher canceled!');
    	redirect('jv/jv/jv_list');
    }

    public function cancel_list(){
    	$data['page'] = 'jv/cancel_jv';
    	$data['cancel'] = $this->M_jv->get_cancel();
    	$this->load->view('template/template', $data);
    }
    public function open_jv(){
    	$id = $this->input->get('id');
    	$this->M_jv->open_jv($id);
    	$this->session->set_flashdata('success', 'Voucher Opened!');
    	redirect('jv/jv/cancel_list');
    }

    public function edit_cek(){
    	$nov = $this->input->post('nov');
    	$value = $this->input->post('value');

    	$this->M_jv->edit_cek($nov, $value);
    }

    public function mass_posting(){
    	$no_voucher = $_POST['chkArray'];
    	$gl_date = $_POST['gld'];
		//insert to gl_header & gl detail, update status in ar header
		$this->M_jv->mass_posting_head($no_voucher, $gl_date);
    }

    public function mass_unposting(){
    	$no_voucher = $_POST['check'];
    	$gl_no = $_POST['gln'];
    	
    	$this->M_jv->mass_unposting($no_voucher);

    }

}
	