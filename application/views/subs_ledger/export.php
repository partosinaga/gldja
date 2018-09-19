<?php
 
 header("Content-type: application/vnd-ms-excel");
 
 header("Content-Disposition: attachment; filename=DJA-Subsidiary Ledger.xls");
 
 header("Pragma: no-cache");
 
 header("Expires: 0");
 
 ?>



<body>
  <div id="div1">
    <div>  
      <table style="width:100%;">
        <tr>
          <th colspan="6"><b>PT DHARMA JASA ANUGERAH</b></th>
        </tr>
        <tr>
          <th colspan="6"><b>SUBSIDIARY LEDGER</b></th>
        </tr>
        <tr>
          <th colspan="6"><b>Date From</b> : <?php echo $date_from ?>  <b>To</b> : <?php echo $date_to ?></th>
        </tr>
        <tr>
          <th colspan="6"><b>COA From</b> : <?php echo $coa_from ?> <b>To</b> : <?php echo $coa_to ?></th>
        </tr>
      </table>
    </div>

    <div id="body">        
          <?php 
            $group=''; 
            $i=1;
            $balanceTotal=0;
            foreach ($subsled as $sl) {
          ?>                  
          <?php
            $result = '';
           
              if ($group != $sl->coa_id ) {
                $balanceTotal=0;
                $prevBalanceDebit=0;
                $prevBalanceCredit=0;

                $result.='
                        <tfoot>
                          <tr>
                            <td colspan="6" style="border-bottom: 1px solid black;border-top: 1px solid black;    border-collapse: collapse;"></td>
                          </tr>
                        <tfoot>';
                foreach ($balance as $bl) {
                  if($sl->coa_id == $bl->coa_id){
                    $prevBalanceDebit = $bl->balance_debit;
                    $prevBalanceCredit = $bl->balance_credit; 
                    $balanceTotal = 0;
                    if($sl->kelompok == 1 OR $sl->kelompok == 6 OR $sl->kelompok == 7 OR $sl->kelompok == 8) { //SALDO DEBIT
                        $balanceTotal += ($prevBalanceDebit - $prevBalanceCredit);
                      } else{
                        $balanceTotal += ($prevBalanceCredit - $prevBalanceDebit);
                      };
                    break;
                  }
                }

                 $result .= 
                '<table border="1" margin="10px" style="width:100%;margin-bottom: 20px;" >
                  <thead >
                    <tr bgcolor="#94A0b2">
                      <td class="coa-name" colspan="3"><b>'. strtoupper($sl->name_coa).'</b></td>
                      <th class="coa-id" colspan="3"><b>'.$sl->coa_id.'</b></th>
                    </tr>
                    <tr bgcolor="#94A0b2">
                      <th >VOUCHER NO.</th>
                      <th width="80px" >DATE</th>
                      <th>DESCRIPTION</th>
                      <th width="100px"  >DEBIT</th>
                      <th width="100px" >CREDIT</th>
                      <th width="100px" >BALANCE</th>
                    </tr>                
                  </thead>';

                  
                  $result .= 
                  '<tbody>
                    <tr>
                      <td></td>
                      <td></td>
                      <td align="right" colspan=""><b>BEGINING BALANCE</b> </td>';        
                      $result .= '
                      <td align="right"><b>'.number_format($prevBalanceDebit).'</b></td>
                      <td align="right"><b>'.number_format($prevBalanceCredit).'</b></td>
                      <td align="right"><b>'.number_format($balanceTotal).'<b></td >
                      </td>
                    </tr>';
                    $group=$sl->coa_id;
              } else {
                $result .= '';
              };
                      if($sl->kelompok == 1 OR $sl->kelompok == 6 OR $sl->kelompok == 7 OR $sl->kelompok == 8  ) { //SALDO DEBIT
                          $balanceTotal += ($sl->debit - $sl->credit);
                      } else{
                          $balanceTotal += ($sl->credit - $sl->debit);
                      };

                    $result .= '
                    <tr>
                      <td align="center">'.$sl->gl_no.'</td>
                      <td align="center">'.$sl->gl_date.'</td>
                      <td>'.$sl->description.'</td>
                      <td align="right">'.number_format($sl->debit).'</td>
                      <td align="right">'.number_format($sl->credit).'</td>
                      <td align="right"><b>'.number_format($balanceTotal).'</b></td>
                    </tr>                 
                  </tbody>';
                  echo $result;

          ?>

        <?php $i++; } ?>
      </table>
      <?php 
        echo  '
                <tr align="right">
                  <td>Exported by:</td>
                  <td><i>'.$this->session->userdata('username').'</i></td>
                </tr><br>
                <tr>
                  <td>Date/time: </td>
                  <td><i>'.date('d-M-Y'). ' / ' . date('H;i;sa').'</i></td>
                </tr>
             ';;
       ?>
    </div>
  </div>
</body>