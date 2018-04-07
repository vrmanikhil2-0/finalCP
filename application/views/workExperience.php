<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $pageTitle."|Backoffice-CampusPuppy"; ?></title>

    <?php echo $headerFiles; ?>

  </head>

  <body>

    <?php echo $nav; ?>

    <div class="container" style="margin-top: 10px;">


      <?php if($message['content']!=''){?>
      <ol class="breadcrumb" style="background-color: white !important; margin-top: 20px; border: 1px solid <?=$message['color']?>;">
        <li style="color: <?=$message['color']?>;"><?=$message['content']?></li>
      </ol>
    	<?php }?>

      <div class="row">

        <?php echo $sidebar; ?>

        <div class="col-lg-9 mb-4">

          <h3 class="mt-4 mb-3" style="float: right;"><?php echo $pageTitle; ?></h3>
          <div class="clearfix"></div>
          <hr>


            <div class="row">


              <?php if(!empty($workExperience)){
                foreach($workExperience as $experience){
                  ?>
              <div class="col-lg-12 mb-4">
              <div class="card">
                <h6 class="card-header"><b><?= $experience['companyName']?></b> <?php if($experience['status'] == 2){?><i class="fa fa-check-circle"></i><?php }?></h6>
                <div class="card-body">
                  <p class="card-text"><b>Duration: </b><?= $experience['startMonth']?> <?= $experience['startYear']?> - <?php if($experience['currentlyWorking'] == 1){echo "Present";}else{?><?= $experience['endMonth']?> <?= $experience['endYear']?><?php } ?></p>
                  <p class="card-text"><b>Position: </b><?= $experience['position']?></p>
                  <p class="card-text"><b>Role: </b><?= $experience['role']?></p>
                </div>
                <div class="card-footer">
                  <a href="<?= base_url('functions/deleteWorkExperience?id='.$experience['workExperienceID'])?>" class="btn btn-danger" style="float: right; margin: 5px;"><i class="fa fa-trash"></i></a>
                  <button class="btn btn-success editWorkEx" data = '<?= json_encode($experience);?>' style="float: right; margin: 5px;"><i class="fa fa-pencil"></i></button>
                </div>
              </div>
              </div>
              <?php } }else{
                echo "No Work Experience Added.";
              }?>
              <div class="col-lg-12 mb-4">
              <button type="button" class="btn btn-primary" data-toggle="modal" style="float: right;" data-target="#education">
                Add Work Experience
              </button>
              </div>

              <div class="modal fade" id="education" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Add Work Experience</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form method = "POST" action = "<?= base_url('functions/addWorkExperience');?>" enctype ="multipart/form-data">
                    <div class="modal-body workEx">

                        <div class="row">
                        <div class="col-md-12 control-group form-group">
                          <div class="controls">
                            <label>Company Name:</label>
                            <input type="text" class="form-control" id= "companyName" name="companyName" required>
                          </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-12 control-group form-group">
                          <div class="controls">
                            <label>Position:</label>
                            <input type="text" class="form-control" id = "position"  name="position" required>
                          </div>
                        </div>
                        </div>

                        <div class="row">
                        <div class="col-md-12">
                        <label><b>Start</b></label>
                      </div>

                        <div class="col-md-8 control-group form-group">
                          <div class="controls">
                            <label>Start Month:</label>
                            <select class="form-control" name="startMonth" id = "startMonth" required>
                              <option value="January">January</option>
                              <option value="February">February</option>
                              <option value="March">March</option>
                              <option value="April">April</option>
                              <option value="May">May</option>
                              <option value="June">June</option>
                              <option value="July">July</option>
                              <option value="August">August</option>
                              <option value="September">September</option>
                              <option value="October">October</option>
                              <option value="November">November</option>
                              <option value="December">December</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-4 control-group form-group">
                          <div class="controls">
                            <label>Start Year:</label>
                            <input type="number" class="form-control" id = "startYear" name="startYear" required min="1960" max="<?php echo date("Y"); ?>">
                          </div>
                        </div>

                        </div>

                        <div class="row">
                          <div class="col-md-12">
                          <label><b>End</b></label>
                        </div>
                        <div class="col-md-8 control-group form-group">
                          <div class="controls">
                            <label>End Month:</label>
                            <select class="form-control" name="endMonth" id= "endMonth">
                              <option value="January">January</option>
                              <option value="February">February</option>
                              <option value="March">March</option>
                              <option value="April">April</option>
                              <option value="May">May</option>
                              <option value="June">June</option>
                              <option value="July">July</option>
                              <option value="August">August</option>
                              <option value="September">September</option>
                              <option value="October">October</option>
                              <option value="November">November</option>
                              <option value="December">December</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-4 control-group form-group">
                          <div class="controls">
                            <label>End Year:</label>
                            <input type="number" class="form-control" id = "endYear" name="endYear" min="1960" max="<?php echo date("Y"); ?>">
                          </div>
                        </div>

                        <div class="col-md-12 control-group form-group">
                          <div class="controls">
                            <input type="checkbox" name="currentWorking" id = "currentWorking" value="1">
                            <label> Currently Work Here</label>
                          </div>
                        </div>

                        </div>


                        <div class="row">
                        <div class="col-md-12 control-group form-group">
                          <div class="controls">
                            <label>Role:</label>
                            <textarea class="form-control" id="role" name="role" required>
                            </textarea>
                          </div>
                        </div>
                        </div>


                        <div class="row">
                        <div class="col-md-12 control-group form-group">
                          <div class="controls">
                            <label>Supporting Document:</label>
                            <input type="file" class="form-control" name = "file" placeholder="Score">
                            <p class="help-block"></p>
                          </div>
                        </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" id = "clearModal" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Add Work Experience</button>
                    </div>
                  </form>
                  </div>
                </div>
              </div>





            </div>


        </div>
      </div>

    </div>

    <?php echo $footer; ?>

    <?php echo $footerFiles; ?>

    <script src="<?= base_url('assets/ckeditor/ckeditor.js')?>"></script>
    <script>
      $(document).ready(function(){
        editor = CKEDITOR.replace('role');
      });
      </script>

      <script type="text/javascript">
        $(document).ready(function(){
          $('.editWorkEx').on('click', function(res){
            data = $(this).attr('data');
            data = JSON.parse(data);
            console.log(data);
            $('#companyName').val(data.companyName)
            $('#position').val(data.position)
            $('#startMonth').val(data.startMonth)
            $('#startYear').val(data.startYear)
            if(!(data.currentlyWorking == 1)){
              $('#endMonth').val(data.endMonth)
              $('#endYear').val(data.endYear)
              $('#currentWorking').attr('checked', false)
            }else{
              $('#currentWorking').attr('checked', true)
            }
            CKEDITOR.instances.editor.setData("hello")
            $('#education').modal('show');
          })
        });
      </script>

      <script type="text/javascript">
         $('#education').on('hidden.bs.modal', function () {
          $(this).find('form').trigger('reset');
        })
      </script>

  </body>

</html>
