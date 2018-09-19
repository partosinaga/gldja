  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          A<small>ccount</small> R<small>eceivable</small>
          
        </h1>
      </section>
      <section class="content">
        <div class="box box-success ">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-book"></i> Receipt Voucher</h3>
          </div>
      
          <div class="box-body">
<!--PORTELT START HERE-->
<div class="portlet box green-meadow">
              <div class="portlet-title">
                <div class="caption">
                  <i class="fa fa-edit"></i> Edit Invoice
                </div>
              </div>

        <div class="portlet-body">
            <div id="message"></div>
            <?php foreach ($editArh as $arh) { ?>

            <form action="javascript:;" method="post" id="form-entry" name="form_edit" class="form-horizontal">
              <div class="row">

                <input type="hidden" name="id"   class="form-control input" value="<?php echo $arh->id?>" readonly> 

                <input type="hidden" name="id2"   class="form-control input" value="<?php echo $arh->no_voucher?>" readonly>

                <input type="hidden" name="posted_no"   class="form-control input" value="<?php echo $arh->posted_no?>" readonly> 

                <div class="col-md-3">
                  <label>No.Voucher</label>
                    <div class="input-icon">
                      <i class="fa fa-key font-green"></i>
                      <input type="text" name="no_voucher" id="no_voucher"  class="form-control input" value="<?php echo $arh->no_voucher; ?>" > 
                    </div>
                </div>

                <div class="col-md-3">
                  <label>Date</label>
                    <div class="input-icon">
                      <i class="fa fa-calendar-plus-o font-green"></i>
                      <input type="date" name="date" id="date" class="form-control input" value=<?php echo $arh->date ;?> >
                    </div>
                </div>

                <div class="col-md-3">
                  <label for="single" class="control-label"><b>Bank Code</b></label>
                    <div class="input-icon">
                        <select id="bank_id" class="form-control select2" name ="bank_id" required>
                          <option value="" selected >--select bank code-- </option>
                          <?php
                            foreach ($bank as $data){
                              echo '<option id="'.$data->account_code.'" value="'.$data->bank_id.'" >'
                                                   .$data->account_code.' - '.$data->name.
                                    '</option>';
                            }
                          ?>
                        </select>
                    </div>
                </div>


              </div><br>

              <div class="row">
                <div class="col-md-12">
                  <label>Desription</label>
                      <textarea class="form-control" name="description" id="description" required=""  placeholder="Enter description of transaction"  ><?php echo $arh->description; ?></textarea>             
                </div>
              </div><br>

              <div class="row">
                <div class="col-md-4">
                  <label for="single" class="control-label"><b>Currency</b></label>
                    <div class="input-icon">
                        <select id="curr_id"  class="form-control select2" name ="curr_id">
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
                      <input type="text" name="total" id="good" class="form-control input text-right" placeholder="Total" value="<?php echo $arh->total; ?>" > 
                    </div>
                </div>

                <div class="col-md-2">
                  <label>Exchange Rate</label>
                    <div class="input-icon">
                      <i class="fa fa-adjust font-green"></i>
                      <input type="bank_id" name="kurs" id="kurs" class="form-control input text-right" placeholder="Kurs" value ="<?php echo $arh->kurs; ?>" required=""> 
                    </div>
                </div>

              </div><br>


               <div class="row">
                <div class="col-md-4">
                  <label>Receive From</label>
                    <div class="input-icon">
                      <i class=" fa fa-plus-square-o font-green"></i>
                      <input type="text" name="receive_from" id="receive_from" class="form-control input" placeholder="Receive from" value="<?php echo $arh->receive_from; ?>"> 
                    </div>
                </div>

                <div class="col-md-4">
                  <label>No.Cek/Giro</label>
                    <div class="input-icon">
                      <i class="fa fa-dollar font-green"></i>
                      <input type="text" name="no_cek" id="no_cek" class="form-control input" placeholder="Cek/Giro Number" value="<?php echo $arh->no_cek; ?>" > 
                    </div>
                </div>


                <div class="col-md-4">
                  <label>GL.Date</label>
                    <div class="input-icon">
                      <i class="fa fa-calendar-plus-o font-green"></i>
                      <input type="date" name="gl_date" id="gl_date" class="form-control input" placeholder="General Ledger Date" value="<?php echo $arh->gl_date; ?>" required=""> 
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
                          <th class="text-center" width="30px"></th>
                        </tr>
                       </thead>
                       <tbody>
                        <?php foreach ($editArd as $ard) { ?>
                          <tr>
                            <td class="hidden">
                              <input type="" class="form-control input" name="no_vouc[]" value="<?php echo $ard->no_voucher ?>">
                            </td>
                            <td align="center"><?php echo $ard->coa_id ?>
                              <input type="hidden" class="form-control input" name="coa_id[]" value="<?php echo $ard->coa_id ?>">
                            </td>

                            <td>
                              <textarea class="form-control" name="desc[]"><?php echo $ard->description ?></textarea>
                            </td>

                            <td> 
                              <input type="text" class="form-control input text-right input-sm debit" name="debit[]" value="<?php echo $ard->debit ?>"> 
                            </td>

                            <td >
                              <input type="text"  class="form-control input text-right input-sm credit" name="credit[]" value="<?php echo $ard->credit ?>"> 
                            </td>
                            
                            <td>
                              <button type="button"  onclick="deleteRow(this)"  class="btn red btn-sm text-center"><i class="fa fa-remove"></i></button>
                            </td>
                          </tr>
                     <?php } ?>
                       </tbody> 

                       <tfoot>
                         <th colspan="5" >
                           <a class="btn blue-hoki green-stripe btn-xs" data-target="#static" data-toggle="modal"><b> Add Detail</b> </a>
                         </th>

                         <!--  <th class="text-right" >TOTAL</th>
                          <th>
                            <input type="text" class="form-control input input-sm text-right" placeholder="0" readonly>
                          </th>
                          <th>
                            <input type="text" class="form-control input input-sm text-right" placeholder="0" readonly>
                          </th> -->
                       </tfoot>
                      </table> <hr>
                      <a href="<?php echo site_url().'/ar/ar/ar_list' ?>">
                      <button type="button" class="btn red  green-stripe"><i class="fa fa-remove" ></i> Back</button></a>
                      <button type="submit" class="btn green red-stripe pull-right" name="save"><i class="fa fa-save" ></i> Save</button>
                      
                  </div>
              </div>  
           </form>
           
        </div>
      </div>

         
        </div>

        
<!--PORTLET END HERE-->
</div>

          </div>
        </div>
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
      <thead bgcolor="#1BBC9B"> 
        <tr>
          <th width="50px">COA ID</th>
          <th>DESCRIPTION</th>
          <th width="50px">POSITION</th>
          <th width="10px"></th>                         
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
      var handleMask = function () {
          $(".mask_currency").inputmask("numeric", {
              radixPoint: ".",
              autoGroup: true,
              groupSeparator: ",",
              digits: 0,
              groupSize: 3,
              removeMaskOnSubmit: true,
              autoUnmask: true
          });
      }

      $('#good').inputmask("numeric", {
          radixPoint: ".",
          autoGroup: true,
          groupSeparator: ",",
          digits: 0,
          groupSize: 3,
          removeMaskOnSubmit: true,
          autoUnmask: true
      });
      $('.debit').inputmask("numeric", {
          radixPoint: ".",
          autoGroup: true,
          groupSeparator: ",",
          digits: 0,
          groupSize: 3,
          removeMaskOnSubmit: true,
          autoUnmask: true
      });
      $('.credit').inputmask("numeric", {
          radixPoint: ".",
          autoGroup: true,
          groupSeparator: ",",
          digits: 0,
          groupSize: 3,
          removeMaskOnSubmit: true,
          autoUnmask: true
      });
      //get data selected
      $(document).ready(function () {
          $('.select_coa').on("click", function () {
              console.log("coa-id" + $(this).attr("data-coa-id"));
          })
      });
      //new row
      var tbody = $('#table_detail').children('tbody')
      var table = tbody.length ? tbody : $('#table_detail');
      $('button[name="select"]').on('click', function () {

          var coaID = $(this).attr("data-coa-id");
          var name_coa = $(this).attr("name_coa");
          var desc = $('textarea[name="description"]').val();
          var newRowContent =
              "<tr>" +

              "<td align=\"center\"> <input type=hidden name=\"coa_id[]\"  id = \"coa_id\" value= " + coaID + ">  " + coaID + " </td>" +
              "<td> <textarea class=\"form-control\" name=\"desc[]\" >" + desc + "</textarea>  </td>" +

              "<td><input type=\"text\" class=\"form-control input input-sm text-right mask_currency debit\" name=\"debit[]\"  value = 0 ></td>" +


              "<td><input type=text class=\"form-control input input-sm text-right mask_currency credit\" name=\"credit[]\" id=\"credit\" value = 0 ></td>" +


              "<td> <button  value=\"x\" onclick=\"deleteRow(this)\"  class=\"btn red btn-sm text-center\" ><i class=\"fa fa-remove\"></i></button> </td>" +
              "<td class=\"hidden\"> <input type=\"hidden\" name=\"\" id=\"\"  class=\"form-control input\" value=\"\"> </td>" +
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
      }
      ;


      //save
      $('button[name="save"]').on('click', function (e) {
          //TO CHECK DR CR BALANCE
          //1.SUM DEBIT
          var sums = $('.debit'); // this will need to be the class of the things you want to sum
          var sum_debit = 0;
          for (var i = 0; i < sums.length; i++) {
              if(sums[i].value == null){//validate if input text is null
                  sums[i].value = 0;
              };
              sum_debit += parseFloat(sums[i].value);// or maybe parseFloat(sums[i].innerHTML)
          }
          $('#total-debit').val(sum_debit);


          //2. SUM CREDIT
          var sums2 = $('.credit'); // this will need to be the class of the things you want to sum
          var sum_credit = 0;

          for (var i = 0; i < sums2.length; i++) {
              if(sums2[i].value == null){//validate if input text is null
                  sums2[i].value = 0;
              };
              sum_credit += parseFloat(sums2[i].value);// or maybe parseFloat(sums[i].innerHTML)
          }

          $('#total-credit').val(sum_credit);


          if (sum_debit == sum_credit) {
              var bank = $('select[name="bank_id"]').val();
              if (bank == '') {
                  alert('Bank Code must be fill out');
              } else {
                  e.preventDefault();
                  var url = '<?php echo base_url('/index.php/ar/ar/save_edit');?>';
                  $("#form-entry").attr("method", "post");
                  $('#form-entry').attr('action', url).submit();
              }
          } else {
              toastr.error('Debit & Credit not balance (' + (sum_debit - sum_credit) + ') ');
          }

      });

      $(document).ready(function () {
          $('#example').DataTable();
      });
  </script>