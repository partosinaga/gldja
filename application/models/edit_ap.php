  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
           A<small>ccount</small> P<small>ayable</small>
        </h1>
      </section>
      <section class="content">
       
        <div class="box box-success">

          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-book"></i> Payment Voucher</h3>
          </div>

          <div class="box-body">
            <div class="portlet box red">
              <div class="portlet-title">
                <div class="caption">
                  <i class="fa fa-edit"></i> Edit Invoice
                </div>
              </div>

              <div class="portlet-body">
                    
                <?php foreach ($editAph as $aph) { ?>
                <form action="javascript:;" method="post" id="form-entry" class="form-horizontal">
                  <div class="row">

                   <input type="hidden" name="posted_no" id=""  class="form-control input" value="<?php echo $aph->posted_no?>" readonly> 

                   <input type="hidden" name="id" id=""  class="form-control input" value="<?php echo $aph->id?>" readonly> 

                    <div class="col-md-3">
                      <label>No.Voucher</label>
                        <div class="input-icon">
                          <i class="fa fa-key font-green"></i>
                          <input type="text" name="no_voucher" id="no_voucher"  class="form-control input" value="<?php echo $aph->no_voucher ?>"  > 
                          
                        </div>
                    </div>

                    <div class="col-md-3">
                      <label>Date</label>
                        <div class="input-icon">
                          <i class="fa fa-calendar-plus-o font-green"></i>
                          <input type="date" name="date" id="date" class="form-control input" value="<?php echo $aph->date ?>" >
                        </div>
                    </div>

                    <div class="col-md-3">
                      <label for="single" class="control-label"><b>Bank Code</b></label>
                        <div class="input-icon">
                            <select id="bank_id" class="form-control select2" name ="bank_id">
                                  <option value="" selected >--select bank code-- </option>
                                  <?php
                                    foreach ($bank->result_array() as $data){
                                      echo "<option value=".$data["bank_id"]." >"
                                                           .$data["name"].
                                            "</option>";
                                    }
                                  ?>
                            </select>
                        </div>
                    </div>


                  </div><br>

                  <div class="row">
                    <div class="col-md-12">
                      <label>Desription</label>
                          <textarea class="form-control" name="description" id="description"   placeholder="Enter description of transaction" ><?php echo $aph->description ?></textarea>             
                    </div>
                  </div><br>

                  <div class="row">
                    <div class="col-md-4">
                      <label for="single" class="control-label"><b>Currency</b></label>
                        <div class="input-icon">
                            <select id="curr_id" class="form-control select2" name ="curr_id">
                              <option value="" selected >--select currency-- </option>
                                <?php
                                  foreach ($curr->result_array() as $data){
                                    echo "<option value=". $data["curr_id"]." >"
                                                           .$data["curr_name"].
                                            "</option>";
                                  }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                      <label>Total</label>
                        <div class="input-icon">
                          <i class="fa fa-calculator font-green"></i>
                          <input type="text" id="total"  value="<?php echo number_format($aph->total) ?>" pattern="[0-9]*" class="form-control input text-right" placeholder="Total" > 
                          <input type="hidden" name="total" id="display" value="<?php echo number_format($aph->total) ?>" class="form-control input text-right" placeholder="Total" > 
                        </div>
                    </div>

                    <div class="col-md-2">
                      <label>Exchange Rate</label>
                        <div class="input-icon">
                          <i class="fa fa-adjust font-green"></i>
                          <input type="bank_id" name="kurs" id="kurs" class="form-control input text-right" placeholder="Kurs" value="<?php echo $aph->kurs ?>" > 
                        </div>
                    </div>

                  </div><br>


                  <div class="row">
                    <div class="col-md-4">
                      <label>Paid To</label>
                        <div class="input-icon">
                          <i class=" fa fa-plus-square-o font-green"></i>
                          <input type="text" name="paid_to" id="receive_from" class="form-control input" placeholder="Receive from" value="<?php echo $aph->receive_from ?>"> 
                        </div>
                    </div>

                    <div class="col-md-4">
                      <label>No.Cek/Giro</label>
                        <div class="input-icon">
                          <i class="fa fa-dollar font-green"></i>
                          <input type="text" name="no_cek" id="no_cek" class="form-control input" placeholder="Cek/Giro Number" value="<?php echo $aph->no_cek ?>"> 
                        </div>
                    </div>


                    <div class="col-md-4">
                      <label>GL.Date</label>
                        <div class="input-icon">
                          <i class="fa fa-calendar-plus-o font-green"></i>
                          <input type="date" name="gl_date" id="gl_date" class="form-control input" placeholder="General Ledger Date" value="<?php echo $aph->gl_date ?>"> 
                        </div>
                    </div>
                  </div><br>

              <?php } ?>
                  
                  <div class="box-body">
                    <div class="page-content-inner">
                      <table class="table table-bordered table-striped" id="table_detail"> 
                           <thead>
                            <tr>
                              <th class="text-center hidden" width="100px" >NO.VOUCHER</th>
                              <th class="text-center" width="100px" >ACCOUNT</th>
                              <th class="text-center" >DESCRIPTION</th>
                              <th class="text-center" width="200px">DEBIT</th>
                              <th class="text-center" width="200px">CREDIT</th>
                              <th class="text-center" width="40px">ACTION</th>
                            </tr>
                           </thead>
                        <?php foreach ($editApd as $apd) { ?>
                          <tr>
                            <td class="hidden">
                              <input type="" class="form-control input" name="no_vouc[]" value="<?php echo $apd->no_voucher ?>">
                            </td>
                            <td><?php echo $apd->coa_id ?>
                              <input type="hidden" class="form-control input" name="coa_id[]" value="<?php echo $apd->coa_id ?>">
                            </td>
                            <td><?php echo $apd->name_coa ?></td>
                            <td> 
                                <input type="text" class=" handleMask form-control input mask_currency text-right input-sm" name="debit[]" value="<?php echo $apd->debit ?>" ;> 
                            </td>
                            <td >
                                <input type="text" class="form-control input mask_currency text-right input-sm" name="credit[]" value="<?php echo $apd->credit ?>" ;> 
                            </td>
                            <td><input type="button" value="x" onclick="deleteRow(this)"  class="btn red btn-sm text-center" /></td>
                          </tr>
                        <?php } ?>
                           <tfoot>
                             <th >
                               <a class="btn green red-stripe btn-xs" data-target="#static" data-toggle="modal"><b> Add Detail</b> </a>
                             </th>

                              <!-- <th class="text-right" >TOTAL</th>
                              <th>
                                <input type="text" class="form-control input input-sm text-right" placeholder="0" readonly>
                              </th>
                              <th>
                                <input type="text" class="form-control input input-sm text-right" placeholder="0" readonly>
                              </th> -->
                           </tfoot>
                          </table>
                          <button onclick="goBack()"  class="btn red  green-stripe"><i class="fa fa-remove" ></i> Cancel</button>
                          <button type="button" class="btn red green-stripe pull-right" name="save"><i class="fa fa-save" ></i> Save</button>
                      </div>
                  </div>  
                </form>


              </div> <!--/portlet-body-->

            </div> <!--/portlet box blue-hoki-->
          </div> <!--/box-body-->

        </div> <!--/box box success-->

      </section>
    </div>
  </div>
  <!--modals-->
     <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-width="760" data-keyboard="false">
      <div align="center" class="modal-header">
        <label >CHART OF ACCOUNT (COA)</label>
      </div>
      <div class="modal-body">
      <table id="example" class="table table-striped table-bordered" cellspacing="0">
      <thead bgcolor="#E7505A"> 
        <tr>
          <th width="50px">COA ID</th>
          <th>DESCRIPTION</th>
          <th width="200px">POSITION</th>
          <th width="10px">#</th>                         
        </tr>
      </thead>
      <tbody>
      <?php
        foreach ($coa as $c){?>
        <tr>
          <td align="center"><?php echo $c->coa_id ?></td>
          <td><?php echo $c->name_coa ?></td>
          <td align="center"><?php echo $c->name ?></td>
          <td>
            <a href="javascript:;"  >
            <button type="button" class="btn red btn-xs select_coa" 
              data-coa-id="<?php echo $c->coa_id?>" name_coa="<?php echo $c->name_coa?>" name="select"  > <i class="fa fa-plus-square " > </i>
            </button> </a>
          </td>
        </tr>
      <?php } ?>
      </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" form="form1" class="btn btn-outline dark">Cancel</button>
      </div>
    </div>
<!--modals-->


<script type="text/javascript">
 var rowIndex = <?php echo (isset($rowIndex) ? $rowIndex : 0) ; ?>;

var handleMask = function() {
          $(".mask_currency").inputmask("numeric",{
              radixPoint:".",
              autoGroup: true,
              groupSeparator: ",",
              digits: 0,
              groupSize: 3,
              removeMaskOnSubmit:true,
              autoUnmask: true
          });
      }

//get data selected
$(document).ready(function(){
    $('.select_coa').on("click", function(){
        console.log("coa-id" + $(this).attr("data-coa-id"));
    })
  });
//new row
var tbody = $('#table_detail').children('tbody')
var table = tbody.length ? tbody : $('#table_detail');
$('button[name="select"]').on('click', function(){

    var coaID = $(this).attr("data-coa-id");
    var name_coa = $(this).attr("name_coa");
    var newRowContent = 
    "<tr>" +
      
      "<td> <input type=hidden name=\"coa_id[]\"  id = \"coa_id\" value= "+ coaID +">  "+ coaID +" </td>"+
      "<td>"+ name_coa +"</td>"+ 

      "<td><input type=\"text\"  class=\"form-control input input-sm mask_currency text-right\" name=\"debit[]\" id=\"debit\" placeholder=0 ></td>"+

      "<td><input type=\"text\" class=\"form-control input input-sm mask_currency text-right\" name=\"credit[]\" id=\"credit\" placeholder=0 ></td>" + 

      "<td> <input type=\"button\" value=\"x\" onclick=\"deleteRow(this)\"  class=\"btn red btn-sm text-center\" /> </td>"+  
      "<td class=\"hidden\"> <input type=\"hidden\" name=\"\" id=\"no_voucher\"  class=\"form-control input\" value=\"\"> </td>"+
    "</tr>";
      
      //Add row
      $('#table_detail tbody').append(newRowContent);
      rowIndex++;
      handleMask();
      $('#static').modal('hide');
});

//delete row
function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
};


//save
$('button[name="save"]').on('click', function(e){
  e.preventDefault();
  var url = '<?php echo base_url('/index.php/ap/ap/save_edit');?>';
  $("#form-entry").attr("method", "post");
  $('#form-entry').attr('action', url).submit();
});


 $(document).ready(function() {
    $('#example').DataTable();
} );



//number format
document.getElementById("total").onblur =function (){    
    this.value = parseFloat(this.value.replace(/,/g, ""))
                    .toFixed()
                    .toString()
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    
    document.getElementById("display").value = this.value.replace(/,/g, "")
    
}
function goBack() {
    window.history.back();
}
</script>