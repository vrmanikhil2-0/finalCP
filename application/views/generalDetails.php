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

          <form method="post" action="<?php echo base_url('functions/updateGeneralDetails'); ?>">

            <div class="row">



              <div class="col-md-12 control-group form-group">
                <div class="controls">
                  <label>Full Name:</label>
                  <input type="text" class="form-control" value="<?php echo $generalData['name']; ?>" disabled>
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="col-md-6 control-group form-group">
                <div class="controls">
                  <label>E-Mail Address:</label>
                  <input type="email" class="form-control" value="<?php echo $generalData['email']; ?>" disabled>
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="col-md-6 control-group form-group">
                <div class="controls">
                  <label>Mobile Number:</label>
                  <input type="text" maxlength="10" class="form-control" value="<?php echo $generalData['mobile']; ?>" disabled>
                  <p class="help-block"></p>
                </div>
              </div>

              <div class="col-md-12 control-group form-group">
                <div class="controls">
                  <label>Career Objective:</label>
                  <textarea class="form-control" id="careerObjective" name="careerObjective" required>
                     <?php echo $generalData['careerObjective']; ?>
                  </textarea>
                  <p class="help-block"></p>
                </div>
              </div>





            </div>

            <button type="submit" class="btn btn-lg btn-primary" id="sendMessageButton" style="float: right;">Update General Details</button>
          </form>

        </div>
      </div>

    </div>

    <?php echo $footer; ?>

    <?php echo $footerFiles; ?>

    <script src="<?= base_url('assets/ckeditor/ckeditor.js')?>"></script>
    <script>
      $(document).ready(function(){
        editor = CKEDITOR.replace('careerObjective');
      });
      </script>

  </body>

</html>
