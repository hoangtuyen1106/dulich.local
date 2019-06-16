<?php include('includes/mysqli_connect.php');?>
<?php include('includes/functions.php');?>
<?php include('includes/header.php');?>

<?php 
if($id = validate_id($_GET['id'])) {
    $display = 4;
    $posts = fetch_categories_location($display, $id);
    $type = 'diadiem';
    $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;
} else {
    redirect_to();
}
             
 ?>
<main id="main">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="wrapper-main">
          <div class="row m-0">
            <div class="col-md-4 p-0">
              <?php include('includes/sidebar.php');?>
            </div>
            <div class="col-md-8 p-0">
              <div class="wrapper-main-content">
                <h2 class="title"><?php echo isset($posts[0]["diadiem_ten"]) ? $posts[0]["diadiem_ten"] : "ĐỊA ĐIỂM"; ?></h2>
                <div class="row">
                  <div class="col-md-12">
                    <div class="box-content">
                      <div class='location-layout'>
                        <div class="row">
                          <?php  
                          if(!empty($posts)){
                            foreach ($posts as $post) {
                              $countComment = countComment($type, $post['baiviet_diadiem_id']);
                              if($countComment > 0) {
                                $count = $countComment;
                              } else {
                                $count = 0;
                              }
                             echo "
                                  
                              <div class='item_wrap clearfix'>
                                <div class='image_wrap'>
                                  <a href='chitietdiadiem.php?id={$post['baiviet_diadiem_id']}'>
                                    <img src='".BASE_URL."/admin/uploads/images/{$post['baiviet_diadiem_anh']}' class='img-fluid' alt=''>
                                  </a>
                                  <a class='post-comments' href='chitietdiadiem.php?id={$post['baiviet_diadiem_id']}#disscuss'><span><i class='fa fa-comments-o'></i>{$count}</span></a>
                                  <a href='diadiem.php?id={$post['diadiem_id']}'><div class='location-icon'><i class='fa fa-map-marker' aria-hidden='true'></i> {$post['diadiem_ten']}</div></a>
                                </div>
                                <div class='details_wrap'>
                                  <div class='information_wrapper'>
                                      <h2 class='name'><a href='chitietdiadiem.php?id={$post['baiviet_diadiem_id']}'>{$post['baiviet_diadiem_ten']}</a></h2>
                                      <div class='description'>{$post['baiviet_diadiem_mota']}</div>
                                  </div>
                                </div>
                              </div>
                              ";
                            } 
                          } else {
                            $messages = "<p>Không có bài viết nào</p>";
                          }
                          ?>
                          <?php if(isset($messages)) {echo $messages;} ?>
                        </div>
                      </div>
                      <?php echo pagination_category($id ,$display , 'baiviet_diadiem', 'diadiem');  ?>
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