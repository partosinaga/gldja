<?php
class M_profit_loss extends CI_Model{

	public function get_income($date_from, $date_to){
		$data = $this->db->query("
		SELECT
			coa.coa_id, coa.name_coa, SUM(det.credit-det.debit) as income
		FROM
			gl_detail det
		JOIN gl_header head ON head.gl_no = det.gl_no
		JOIN coa ON coa.coa_id = det.coa_id
		JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
		WHERE
			subgroup.kelompok = 5
		AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
		GROUP BY
			det.coa_id ASC");
		return $data->result();
	}


	public function get_cogs($date_from, $date_to){
		$data = $this->db->query("
			SELECT
				gl_header.gl_date,
				coa.name_coa,
				coa.coa_id,
				SUM(gl_detail.debit) AS balance_debit,
				SUM(gl_detail.credit) AS balance_credit,
				(
					CASE
					WHEN subgroup.kelompok IN (1, 6, 7, 8) THEN
						SUM(gl_detail.debit) - SUM(gl_detail.credit)
					ELSE
						SUM(gl_detail.credit) - SUM(gl_detail.debit)
					END
				) AS cogs
			FROM
				gl_detail
			JOIN gl_header ON gl_detail.gl_no = gl_header.gl_no
			JOIN coa ON coa.coa_id = gl_detail.coa_id
			JOIN subgroup ON subgroup.subgroup_id = coa.subgroup
			WHERE
			 gl_header.gl_date <= '".$date_to."'
			AND subgroup.kelompok = 6
			GROUP BY
				gl_detail.coa_id ASC;");
		return $data->result();
	}
	public function get_expense($date_from, $date_to){
		$data = $this->db->query("
			SELECT
				coa.coa_id,
				coa.name_coa,
				SUM(det.debit - det.credit) AS expense
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.kelompok BETWEEN 7
			AND 8
			AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
			GROUP BY
				det.coa_id ASC
				");
		return $data->result();
	}

	public function sum_income($date_from, $date_to){
		$data = $this->db->query("
			SELECT
				SUM(det.credit - det.debit) AS sum_income
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.kelompok = 5
			AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
			");
		return $data->result();
	}

	public function sum_cogs($date_from, $date_to){
		$data = $this->db->query("
			SELECT
				(
					CASE
					WHEN subgroup.kelompok IN (1, 7, 8) THEN
						SUM(gl_detail.debit) - SUM(gl_detail.credit)
					ELSE
						SUM(gl_detail.credit) - SUM(gl_detail.debit)
					END
				) AS sum_cogs
			FROM
				gl_detail
			JOIN gl_header ON gl_detail.gl_no = gl_header.gl_no
			JOIN coa ON coa.coa_id = gl_detail.coa_id
			JOIN subgroup ON subgroup.subgroup_id = coa.subgroup
			WHERE gl_header.gl_date <= '".$date_to."'
			AND subgroup.kelompok = 6");
		return $data->result();
	}

	public function sum_expense($date_from, $date_to){
		$data = $this->db->query("
			SELECT
				SUM(det.debit - det.credit) AS sum_expense
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.kelompok BETWEEN 7 AND 8
			AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
			");
		return $data->result();
	}


	public function get_previncome($date_to){
		$data = $this->db->query("
			SELECT
				coa.coa_id, coa.name_coa, SUM(det.credit-det.debit) as prev_income
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.kelompok = 5
			AND head.gl_date BETWEEN (SELECT MAKEDATE(year(now()),1)) AND '".$date_to."'
			GROUP BY
				det.coa_id ASC
			");
		return $data->result();
	}

	public function sum_prevIncome($date_to){
		$data = $this->db->query("
			SELECT
				coa.coa_id, coa.name_coa, SUM(det.credit-det.debit) as prev_sum_income
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.kelompok = 5
			AND head.gl_date BETWEEN (SELECT MAKEDATE(year(now()),1)) AND '".$date_to."'
			");
		return $data->result();
	}
	public function get_prevCogs($date_from){
		$data = $this->db->query("
			SELECT
				gl_header.gl_date,
				coa.name_coa,
				coa.coa_id,
				SUM(gl_detail.debit) AS balance_debit,
				SUM(gl_detail.credit) AS balance_credit,
				(
					CASE
					WHEN subgroup.kelompok IN (1, 6, 7, 8) THEN
						SUM(gl_detail.debit) - SUM(gl_detail.credit)
					ELSE
						SUM(gl_detail.credit) - SUM(gl_detail.debit)
					END
				) AS prev_cogs
			FROM
				gl_detail
			JOIN gl_header ON gl_detail.gl_no = gl_header.gl_no
			JOIN coa ON coa.coa_id = gl_detail.coa_id
			JOIN subgroup ON subgroup.subgroup_id = coa.subgroup
			WHERE
				gl_header.gl_date < '".$date_from."'
			AND subgroup.kelompok = 6
			");
		return $data->result();
	}
	public function sum_prevCogs($date_from){
		$data = $this->db->query("
					SELECT
			(
				CASE
				WHEN subgroup.kelompok IN (1, 7, 8) THEN
					SUM(gl_detail.debit) - SUM(gl_detail.credit)
				ELSE
					SUM(gl_detail.credit) - SUM(gl_detail.debit)
				END
			) AS prev_sum_cogs
		FROM
			gl_detail
		JOIN gl_header ON gl_detail.gl_no = gl_header.gl_no
		JOIN coa ON coa.coa_id = gl_detail.coa_id
		JOIN subgroup ON subgroup.subgroup_id = coa.subgroup
		WHERE
			gl_header.gl_date < '".$date_from."'
		AND subgroup.kelompok = 6
			");
		return $data->result();
	}

	// PREV beban
	public function get_prevExpense($date_to){
		$data = $this->db->query("
				SELECT
					coa.coa_id, coa.name_coa, SUM(det.debit-det.credit) as prev_expense
				FROM
					gl_detail det
				JOIN gl_header head ON head.gl_no = det.gl_no
				JOIN coa ON coa.coa_id = det.coa_id
				JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
				WHERE
					subgroup.kelompok BETWEEN 7 AND 8
				AND head.gl_date BETWEEN (SELECT MAKEDATE(year(now()),1)) AND '".$date_to."'
				GROUP BY
					det.coa_id ASC
			");
		return $data->result();
	}

	public function sum_prevExpense($date_to){
		$data = $this->db->query("
				SELECT
					coa.coa_id, coa.name_coa, SUM(det.debit-det.credit) as sum_prev_expense
				FROM
					gl_detail det
				JOIN gl_header head ON head.gl_no = det.gl_no
				JOIN coa ON coa.coa_id = det.coa_id
				JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
				WHERE
					subgroup.kelompok BETWEEN 7 AND 8
				AND head.gl_date BETWEEN (SELECT MAKEDATE(year(now()),1)) AND '".$date_to."'
			");
		return $data->result();
	}

	// pendapatan lainnya
	public function other_income($date_from, $date_to){
		$data = $this->db->query("
				SELECT
					coa.coa_id,
					coa.name_coa,
					SUM(det.credit - det.debit) AS other_income
				FROM
					gl_detail det
				JOIN gl_header head ON head.gl_no = det.gl_no
				JOIN coa ON coa.coa_id = det.coa_id
				JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
				WHERE
					subgroup.subgroup_id BETWEEN 901 AND 904
				AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
				GROUP BY
					det.coa_id ASC
			");
		return $data->result();
	}

	public function sum_other_income($date_from, $date_to){
		$data =$this->db->query("
			SELECT
				SUM(det.credit - det.debit) AS sum_other_income
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.subgroup_id BETWEEN 901 AND 904
			AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
			");
		return $data->result();
	}

	// beban lainnya
	public function other_expense($date_from, $date_to){
		$data = $this->db->query("
			SELECT
				coa.coa_id,
				coa.name_coa,
				SUM(det.debit - det.credit) AS other_expense
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.subgroup_id = 905
			AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
			GROUP BY
				det.coa_id ASC
			");
		return $data->result();
	}

	public function sum_other_expense($date_from, $date_to){
		$data = $this->db->query("
			SELECT
				SUM(det.debit - det.credit) AS sum_other_expense
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.subgroup_id = 905
			AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
			");
		return $data->result();
	}
	// beban lainnya akun 910-999
	public function other_expense2($date_from, $date_to){
		$data = $this->db->query("
			SELECT
				coa.coa_id,
				coa.name_coa,
				SUM(det.debit - det.credit) AS other_expense2
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.subgroup_id BETWEEN 910 AND 999
			AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
			GROUP BY
				det.coa_id ASC
			");
		return $data->result();
	}

	public function sum_other_expense2($date_from, $date_to){
		$data = $this->db->query("
			SELECT
				coa.coa_id,
				coa.name_coa,
				SUM(det.debit - det.credit) AS sum_other_expense2
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.subgroup_id BETWEEN 910 AND 999
			AND head.gl_date BETWEEN '".$date_from."' AND '".$date_to."'
			");
		return $data->row();
	}



	

	// PREV pendapatan lainnya 
	public function prev_other_income($date_to){
		$data = $this->db->query("
			SELECT
				coa.coa_id, coa.name_coa, SUM(det.credit-det.debit) as prev_other_income
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.subgroup_id BETWEEN 901 AND 904 
	 		AND head.gl_date BETWEEN (SELECT MAKEDATE(year(now()),1)) AND '".$date_to."'
			GROUP BY
				det.coa_id ASC
			");
		return $data->result();
	}

	public function sum_prev_other_income($date_to){
		$data = $this->db->query("
			SELECT
				coa.coa_id, coa.name_coa, SUM(det.credit-det.debit) as sum_prev_other_income
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.subgroup_id BETWEEN 901 AND 904 
	 		AND head.gl_date BETWEEN (SELECT MAKEDATE(year(now()),1)) AND '".$date_to."'

			");
		return $data->row();
	}

	// PREV other expense
	public function prev_other_expense($date_to){
		$data = $this->db->query("
			SELECT
				coa.coa_id, coa.name_coa, SUM(det.debit-det.credit) as prev_other_expense
			FROM
				gl_detail det
			JOIN gl_header head ON head.gl_no = det.gl_no
			JOIN coa ON coa.coa_id = det.coa_id
			JOIN subgroup ON coa.subgroup = subgroup.subgroup_id
			WHERE
				subgroup.subgroup_id = 905
	 		AND head.gl_date BETWEEN (SELECT MAKEDATE(year(now()),1)) AND '".$date_to."'
			GROUP BY
				det.coa_id ASC
			");
		return $data->result();
	}

}
?>