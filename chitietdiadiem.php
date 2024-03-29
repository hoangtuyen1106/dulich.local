<?php include('includes/mysqli_connect.php');?>
<?php include('includes/functions.php');?>

<?php 
$type = 'diadiem';
if($id = validate_id($_GET['id'])) {
      $set = get_location_by_id($id);
      $posts = array(); 

  if($set->num_rows > 0) {
    $location = mysqli_fetch_array($set, MYSQLI_ASSOC); 
    $title = the_excerpt($location['baiviet_diadiem_ten'], 80);
    $description = the_excerpt($location['baiviet_diadiem_mota'], 80) ;
    $image = $location['baiviet_diadiem_anh'];
    $diadiemten = $location['diadiem_ten'];
    $diadiemid = $location['catid'];

    $posts[] = array(
      'baiviet_diadiem_ten' => $location['baiviet_diadiem_ten'], 
      'baiviet_diadiem_noidung' => $location['baiviet_diadiem_noidung'], 
      'baiviet_diadiem_mota' => $location['baiviet_diadiem_mota'],
      'baiviet_diadiem_anh' => $location['baiviet_diadiem_anh'],
      'date' => $location['date'],
      'diadiem_ten' => $location['diadiem_ten'],
      'catid' => $location['catid'],
    );

  } else {
      $messages = "<p>Không có bài viết nào.</p>";
  }
} else {
    redirect_to();
}
             
 ?>
 <?php include('includes/header_sub.php');?>
<main id="main">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="wrapper-main">
          <div class="row m-0">
            <div class="col-md-4 p-0">
              <?php include('includes/sidebar.php'); ?>
            </div>
            <div class="col-md-8 p-0">
              <div class="wrapper-main-content">
                <div class="page-title pad clearfix">
                  <ul class="meta-single clearfix">
                    <?php 
                    echo "<li class='category'><a href='diadiem.php?id={$diadiemid}' rel='category tag'>{$diadiemten}</a></li>"; ?>
                  </ul>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="box-content">
                      <div class="detail-content">
                        <?php
                            foreach($posts as $post) {
                            echo "
                              <h3>{$post['baiviet_diadiem_ten']}</h3>
                              <p class='post-date'>
                                <time class='published'>{$post['date']}</time>
                              </p>
                              <p class='entry-detail-description'>{$post['baiviet_diadiem_mota']}</p>
                              <p class='entry-detail-content'>{$post['baiviet_diadiem_noidung']}</p>
                            ";
                          } // End foreach.
                        ?>
                        <?php if(isset($messages)) {echo $messages;} ?>
                        <script src="assets/lib/jssocials/jquery-1.10.1.min.js"></script>
                        <link rel="stylesheet" href="assets/lib/jssocials/jssocials.css" />
                        <link rel="stylesheet" href="assets/lib/jssocials/jssocials-theme-flat.css" />
                        <script type="text/javascript" src="assets/lib/jssocials/jssocials.min.js"></script>
                        <div id="share"></div>
                        <script type="text/javascript">
                          $("#share").jsSocials({
                              showLabel: false,
                              showCount: false,
                              shares: ["twitter", "facebook"]
                          });
                        </script>
                      </div>
                      <?php include('includes/comment_form.php'); ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include('includes/footer.php');?>