<?php
class M_jv extends CI_Model{

	public function get_kode(){
		// get financial month/year
		$m=$this->db->query("SELECT financial_month AS m FROM system_parameter ORDER BY id DESC LIMIT 1;");
		$y=$this->db->query("SELECT RIGHT(financial_year, 2) AS y FROM system_parameter ORDER BY id DESC LIMIT 1");
		$month = $m->row_array();
		$year = $y->row_array();
		$monthDate = $month['m'].'/'.$year['y'];
		
		// query mencari nomor maximum dengan financial month/year yg dipilih
		$q=$this->db->query("
			SELECT
				LEFT( MAX( no_voucher ), 3 ) AS kt
			FROM
				jv_header
			WHERE
				RIGHT ( no_voucher, 5 ) = '".$monthDate."'
		");

		// query cek apakah bulan dan tahun pada financial month/year sudah ada
		$c=$this->db->query("
			SELECT
				RIGHT (no_voucher, 5) AS kk
			FROM
				jv_header
			WHERE
				RIGHT (no_voucher, 5) = '".$monthDate."'
			ORDER BY
				id DESC
			LIMIT 1 
		");
		$current = $c->row_array();




		$kd="";
		if($q->num_rows() > 0 AND  $current['kk'] == $monthDate ){
			foreach ($q->result() as $k){
				$tmp = ((int)$k->kt)+1;
				$kd = sprintf("%03s", $tmp);
			};
		} else {
			$kd = "001";
		};
		return $kd.'/'.$monthDate;
	}

	
	public function get_jv(){
		$data = $this->db->query("
			SELECT
				jv_header.id,
				jv_header.gl_date,
				jv_header.no_voucher,
				jv_header.date,
				jv_header.description,
				jv_header.total,
				jv_header.Fmonth,
				jv_header.receive_from,
				jv_header.status,
				jv_header.posted_no,
				jv_header.no_cek,
				gl_header.Fclose
			FROM
				jv_header
			LEFT JOIN gl_header ON gl_header.reff_no = jv_header.no_voucher
			AND jv_header.description = gl_header.description
			AND jv_header.total = gl_header.total
			WHERE 
				jv_header.status != 'cancel'
			ORDER BY
				jv_header.id DESC;
			");
		return $data->result();
	}
	public function get_header($no_voucher){
		$data = $this->db->query("select * from jv_header where no_voucher =  '".$no_voucher."' " );
		return $data->result();
	}

	public function get_detail($no_voucher){
		$data = $this->db->query(" SELECT jv_detail.coa_id, coa.name_coa, jv_detail.debit, jv_detail.credit
			from jv_detail 
			join coa
			on jv_detail.coa_id = coa.coa_id
			where jv_detail.no_voucher= '".$no_voucher."'; " );
		return $data->result();
	}
	public function get_totalDetail($no_voucher){
		$data = $this->db->query(" select SUM(jv_detail.debit) as total_debit, SUM(jv_detail.credit) as total_credit
			from jv_detail 
			join coa
			on jv_detail.coa_id = coa.coa_id
			where jv_detail.no_voucher= '".$no_voucher."';" );
		return $data->result();
	}

	public function get_postList(){
		$data = $this->db->query("
			SELECT
				jv_header.no_voucher,
				jv_header.date,
				jv_header.description,
				jv_header.total,
				jv_header.gl_date,
				jv_header.posted_no,
				jv_header.status,
				jv_header.Fmonth
			FROM
				jv_header
			WHERE
				STATUS = 'post'
			OR STATUS = 'unposted'
			");
		return $data->result();
	}

	public function save_posting($noVoc,$posted_no){
		$data = $this->db->query("update jv_header set status = 'posted', posted_no = '".$posted_no."' where no_voucher = '".$noVoc."' ; ");
	}

	public function save_glHead($data,$table){
		$this->db->insert($table,$data);
	}

	public function save_glDetail($noVoc, $gl_no){
		$result=$this->db->query("
			INSERT INTO gl_detail ( gl_no, description, coa_id, debit, credit ) SELECT
				'".$gl_no."',
				description,
				coa_id,
				debit,
				credit 
			FROM
				jv_detail 
			WHERE
				no_voucher = '".$noVoc."'
			");
		return $result;
	}

	public function get_unposting(){
		$data = $this->db->query("
			SELECT
				jv_header.no_voucher,
				jv_header.date,
				jv_header.description,
				jv_header.total,
				jv_header.gl_date,
				jv_header.status,
				gl_header.Fclose,
				gl_header.gl_no
			FROM
				jv_header
			JOIN gl_header ON jv_header.no_voucher = gl_header.reff_no
			WHERE
				gl_header.`status` = 'posted'
			AND gl_header.Fmodule = 'JV'
			ORDER BY
				jv_header.no_voucher DESC
			");
		return $data->result();
	}

	public function save_unposting($id){
		$data = $this->db->query("update jv_header set status = 'unposted' where no_voucher = '".$id."' ; ");
	}

	public function updateGLHposted($id){
		$data = $this->db->query("update gl_header set status = 'unposted' where reff_no = '".$id."' AND Fmodule = 'jv'; ");
	}

	public function save_reposting($id){
		$data = $this->db->query("update jv_header set status = 'posted' where no_voucher = '".$id."' ; ");
	}

	public function updateGLHunposted($id){
		$data = $this->db->query("update gl_header set status = 'posted' where reff_no = '".$id."' AND Fmodule = 'JV'; ");
	} 

	public function save_upd_reposting($id, $posted_no){
		$data = $this->db->query("update jv_header set status = 'posted', posted_no = '".$posted_no."' where no_voucher = '".$id."' ; ");
	}
	public function save_upd_reposting2($id, $posted_no){
		$data = $this->db->query("update gl_header set status = 'posted', gl_no = '".$posted_no."' where reff_no = '".$id."' AND Fmodule = 'JV' ; ");
	}
	public function updateGlNoGlDetail($posted_no, $postedNo){
		$data = $this->db->query("update gl_detail set gl_no = '".$posted_no."' where 
			gl_no = '".$postedNo."' ");
	}
	public function get_jvh_edit($no_voucher){
		$data=$this->db->query("select * from jv_header where no_voucher = '".$no_voucher."' ");
		return $data->result();
	}
	public function get_jvd_edit($no_voucher){
		$data=$this->db->query("
			SELECT
				jv_detail.no_voucher,
				jv_detail.coa_id,
				jv_detail.description,
				jv_detail.debit,
				jv_detail.credit,
				coa.name_coa 
			FROM
				jv_detail
				JOIN coa ON coa.coa_id = jv_detail.coa_id 
			WHERE
				jv_detail.no_voucher = '".$no_voucher."'
			");
		return $data->result();
	}

	public function save_update_jvh($id, $bank_id, $date, $description, $curr_id, $total, $kurs, $receive_from, $no_cek, $gl_date, $no_voucher){
		$data = $this->db->query(" update jv_header set no_voucher = '".$no_voucher."' , bank_id = '".$bank_id."' , date='".$date."' , description = '".$description."' , curr_id = '".$curr_id."' , total= '".$total."' , kurs = '".$kurs."' , receive_from = '".$receive_from."' , no_cek = '".$no_cek."' , gl_date = '".$gl_date."' where id = '".$id."' ");
	}

	public function delete_jvd_old($no_voucher){
		$data = $this->db->query(" delete from jv_detail where no_voucher = '".$no_voucher."' ");
	}

	public function save_update_glh($total, $description, $gl_date, $no_voucher){
		$data = $this->db->query(" update gl_header set gl_date = '".$gl_date."' , description = '".$description."' , total = '".$total."' where reff_no = '".$no_voucher."' AND Fmodule = 'jv' ");
	}

	public function delete_gld_old($posted_no){
		$data = $this->db->query(" delete from gl_detail where gl_no = '".$posted_no."' 
			AND gl_no like '6%'  ");
	}
	public function terbilang($no_voucher){
		$data = $this->db->query("
			SELECT
				f_terbilang (total) as terb
			FROM
				jv_header
			WHERE
				no_voucher = '".$no_voucher."'
			");
		return $data->row();
	}
	public function edit_glDate($nv, $date){
		$this->db->query("update jv_header set gl_date = '".$date."' WHERE no_voucher = '".$nv."' ");
	}
	public function edit_glDate_glh($nv, $date){
		$this->db->query("update gl_header set gl_date = '".$date."' WHERE reff_no = '".$nv."' AND Fmodule = 'JV' ");
	}
	public function cancel_jv($id){
		$this->db->query("update jv_header set status = 'cancel' , posted_no = NULL  where no_voucher = '".$id."' ");
	}
	public function cancel_jv_post($id){
		$this->db->query("update jv_header set status = 'cancel' , posted_no = NULL  where posted_no = '".$id."'");
	}
	public function cancel_glh($id){
		$this->db->query("delete from gl_header where  gl_no = '".$id."'");
	}
	public function cancel_gld($id){
		$this->db->query("delete from gl_detail where gl_no = '".$id."'");
	}
	public function get_cancel(){
		$data = $this->db->query("SELECT * FROM jv_header where status = 'cancel'");
		return $data->result();
	}
	public function open_jv($id){
		$this->db->query("update jv_header set status = 'post' where no_voucher = '".$id."'");
	}

	public function edit_cek($nov, $value){
		$this->db->query("UPDATE jv_header SET no_cek = '".$value."' WHERE no_voucher = '".$nov."' ");
	}

	public function mass_posting_head($no_voucher, $gl_date){
		$data2 = array();
		for ($a=0; $a < count($gl_date); $a++) { 
					
			$q[$a] = $this->db->query("
	          SELECT
	            MAX( RIGHT ( gl_no, 4 ) ) AS kt 
	          FROM
	            gl_header 
	          WHERE
	            Fmodule = 'JV' 
	            AND MONTH ( gl_date ) = MONTH ( '".$gl_date[$a]."' ) 
	            AND YEAR ( gl_date ) = YEAR ( '".$gl_date[$a]."' );
	        ");

			$kd[$a]="";
			$posted_no[$a]="";
			$tgl= date("my");
			$gld[$a] = New DateTime($gl_date[$a]);
			$kd2[$a] = "6".$gld[$a]->format('my');
			if($q[$a]->num_rows()>0){
				foreach ($q[$a]->result() as $k[$a]){
					$tmp[$a] = ((int)$k[$a]->kt)+1;
					$kd[$a] = sprintf("%04s", $tmp[$a]);
				}
			}else {
				$kd[$a] = "6".$gld[$a]->format('my')."0001";
			}
			$posted_no[$a] = $kd2[$a].$kd[$a];
			


			$query1 = $this->db->query("
				INSERT INTO gl_header (gl_no, gl_date, reff_no, description, total, Fmodule,Fmonth, Fyear, status, Fclose, audit_user, audit_date)
				SELECT 
					'".$posted_no[$a]."',
					gl_date,
					no_voucher,
					description,
					total,
					'JV',
					'".date('m')."',
					'".date('Y')."',
					'posted',
					NULL,
					'".$this->session->userdata('username')."',
					'".date("Y-m-d H:i:sa")."'
				FROM jv_header
				WHERE 
				no_voucher = '".$no_voucher[$a]."';
			");	

			$query2 = $this->db->query("
				INSERT INTO gl_detail(gl_no, description, coa_id, debit, credit)
				SELECT 
					'".$posted_no[$a]."',
					description,
					coa_id,
					debit,
					credit
				FROM jv_detail
				WHERE no_voucher = '".$no_voucher[$a]."' 
			");

			$query3 = $this->db->query("
					UPDATE jv_header 
					SET status = 'posted',
						posted_no = '".$posted_no[$a]."'
					WHERE
						no_voucher = '".$no_voucher[$a]."';
			");

		}
		
	}

	public function mass_unposting($no_voucher){
		$data = array();
		for ($i=0; $i < count($no_voucher); $i++) { 
			$data = $this->db->query("UPDATE jv_header SET status = 'unposted' WHERE no_voucher = '".$no_voucher[$i]."' ; ");

			$data = $this->db->query("UPDATE gl_header SET status = 'unposted' WHERE reff_no = '".$no_voucher[$i]."' AND Fmodule = 'JV' ; ");
		}
	}

}
?>