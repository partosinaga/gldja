<!DOCTYPE html>
<html>
<head>
  <title>&nbsp</title>
    <style type="text/css">
      .no_border {
          border-collapse: collapse;
      }

      .no_border, .td_no-border {
          border: 1px solid black;
      }
      
      body {
        background-color: #fff;
        margin: 15px 15px 15px 15px;
        font: 11px normal Helvetica, Arial, sans-serif;
        color: #000000;
      }
    </style>
</head>
  <body onload="window.print();"  >   
    <div>
      <p align="center" style="font-size: 12pt"> <u>PT DHARMA JASA ANUGERAH</u><br>RECEIPT VOUCHER </p>
    </div>
      <table width="100%" style="bottom: 0px">
        <tr>
          <td width="15%">NO. VOUCHER</td>
          <td>:
            <?php foreach ($header as $h) {
              echo $h->no_voucher;
            } ?>
          </td>
          <td width="10%">NO. GL</td>
          <td width="15%">:</td>
        </tr>
        <tr>
          <td>TANGGAL</td>
          <td>:
            <?php foreach ($header as $h) {
              $date = new DateTime($h->date);
              echo $date->format('d-m-Y');
            } ?>
          </td>
          <td>TANGGAL</td>
          <td>:</td>
        </tr>
      </table><br>




      <table class="no_border" width="100%">
        <tr class="td_no-border">
          <td class="td_no-border" width="20%">KEPADA</td>
          <td class="td_no-border" colspan="3"> <?php foreach ($header as $h) {echo $h->name;} ?></td>
          <td class="td_no-border" width="20%" align="center">PENERIMA</td>
        </tr>
        <tr class="td_no-border">
          <td class="td_no-border">JUMLAH</td>
          <td class="td_no-border" width="3%">Rp</td>
          <td class="td_no-border"><?php foreach ($header as $h) {echo number_format((float)$h->total);} ?></td>
          <td class="td_no-border" width="30%">BG/CEK :</td>
          <td class="td_no-border" rowspan="2"></td>
        </tr>
        <tr class="td_no-border">
          <td class="td_no-border" height="50px" style="text-align: left;vertical-align: top">KETERANGAN</td>
          <td class="td_no-border" colspan="3"  style="text-align: left;vertical-align: top"><?php foreach ($header as $h) {echo $h->description;} ?></td>
        </tr>
      </table><br>

      <table class="no_border" width="100%">
        <tr class="td_no-border">
          <td class="td_no-border" width="20%" align="center">NO. PERKIRAAN</td>
          <td class="td_no-border" align="center">PERKIRAAN</td>
          <td class="td_no-border" width="20%" align="center">DEBIT</td>
          <td class="td_no-border" width="20%" align="center">CREDIT</td>
        </tr>
        <?php
          foreach ($detail as $d) {?>
        <tr>
          <td class="td_no-border" class="td_no-border" align="center"> <?php echo $d->coa_id ?> </td>  
          <td class="td_no-border" > <?php echo $d->name_coa ?> </td> 
          <td class="td_no-border" align="right"> <?php echo number_format((int)$d->debit) ?> </td>  
          <td class="td_no-border" align="right"> <?php echo number_format((int)$d->credit) ?> </td>  
        </tr>
        <?php } ?>
        <tr>
          <td class="td_no-border" class="td_no-border" align="center">&nbsp</td>  
          <td class="td_no-border" ></td> 
          <td class="td_no-border" align="right"></td>  
          <td class="td_no-border" align="right"></td>  
        </tr>
        <tr>
          <td class="td_no-border" class="td_no-border" align="center">&nbsp</td>  
          <td class="td_no-border" ></td> 
          <td class="td_no-border" align="right"></td>  
          <td class="td_no-border" align="right"></td>  
        </tr>
        <tr>
          <td class="td_no-border" class="td_no-border" align="center">&nbsp</td>  
          <td class="td_no-border" ></td> 
          <td class="td_no-border" align="right"></td>  
          <td class="td_no-border" align="right"></td>  
        </tr>
        <tr>
          <td class="td_no-border" class="td_no-border" align="center">&nbsp</td>  
          <td class="td_no-border" ></td> 
          <td class="td_no-border" align="right"></td>  
          <td class="td_no-border" align="right"></td>  
        </tr>
        <tr>
          <td class="td_no-border" class="td_no-border" align="center">&nbsp</td>  
          <td class="td_no-border" ></td> 
          <td class="td_no-border" align="right"></td>  
          <td class="td_no-border" align="right"></td>  
        </tr>
        <tr>
          <td class="td_no-border" class="td_no-border" align="center">&nbsp</td>  
          <td class="td_no-border" ></td> 
          <td class="td_no-border" align="right"></td>  
          <td class="td_no-border" align="right"></td>  
        </tr>
        <tr>
          <td class="td_no-border" class="td_no-border" align="center">&nbsp</td>  
          <td class="td_no-border" ></td> 
          <td class="td_no-border" align="right"></td>  
          <td class="td_no-border" align="right"></td>  
        </tr>
      </table><br>





  <table class="no_border" width="100%" bordercolor="black" border="1">
      <tr>
        <td align="center" width="240px" >DISIAPKAN</td>
        <td width="240px" align="center">DIPERIKSA</td>
        <td align="center" width="240px">DESETUJUI</td>
      </tr>
      <tr align="center">
        <td class="td_no-border" height="50px" ><br>
         <br>
        </td>
        <td class="td_no-border" height="50px"><br>
         <br>
        </td>
        <td class="td_no-border" height="50px"> <br>
          <br>
        </td>
      </tr>
    </table>
    <!-- END OF TOP -->
</body>
</html>



<style type="text/css">
  .bottom{
  padding-top: 150px;
  }
</style>