<?php include('includes/mysqli_connect.php');?>
<?php include('includes/functions.php');?>
<?php include('includes/header.php');?>
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
								<h2 class="title"> DÂN ĐI PHƯỢT <span class="post-subheading">Blog</span></h2>
								<div class="row">
									<div class="col-md-12">
										<div class="box-content">
				                          <?php 
				                          if(isset($_GET['cid']) && filter_var($_GET['cid'], FILTER_VALIDATE_INT, array('min_range' =>1))) {


				                          } elseif (isset($_GET['nid']) && filter_var($_GET['nid'], FILTER_VALIDATE_INT, array('min_range' =>1))) {

				                          } else { ?>
											<div class="featured">
											    <article class="clearfix post">
											    	<?php 
											    		$posts = fetch_news();
											    		$items = $posts[0];

										    			echo"
												        <div class='post-inner post-hover'>
												            <div class='post-thumbnail featured-img-thumb-large'>
												                <a href='single.php?nid={$items['tintuc_id']}'>
												                    <img src='".BASE_URL."/admin/uploads/images/{$items['tintuc_anh']}' class='img-fluid' alt=''>
												                </a>
												                <a class='post-comments' href=''><span><i class='fa fa-comments-o'></i>0</span></a>
												            </div>
												         

												            <div class='post-meta clearfix'>
												                <p class='post-category'><a href='single.php?cid={$items['danhmuc_id']}'>{$items['danhmuc_ten']}</a></p>
												                <p class='post-date'>
												                    <time class='published' datetime=''>{$items['date']}</time>
												                </p>
												            </div>
												           

												            <h2 class='post-title'>
        														<a href='single.php?nid={$items['tintuc_id']}' title='{$items['tintuc_ten']}'>{$items['tintuc_ten']}</a>
        													</h2>
												           
												            <div class='excerpt'>
												                <p>{$items['tintuc_mota']}</p>
												            </div>
												            
												        </div>
												        ";
											    	 ?>
											        <!--/.post-inner-->
											    </article>
											    <!--/.post-->
											</div>
											<div id="grid-wrapper" class="post-list clearfix">
												<div class="row">
												<?php  
						                        $posts = fetch_news();
						                        unset($posts[0]);
						                        foreach ($posts as $post) {
										            echo "
										                <div class='col-md-6'>
						                                    <article class='clearfix post'>
						                                      <div class='post-inner post-hover'>
						                                          <div class='post-thumbnail featured-img-thumb-large'>
						                                              <a href='single.php?nid={$post['tintuc_id']}' title=''>
						                                                  <img src='".BASE_URL."/admin/uploads/images/{$post['tintuc_anh']}' class='img-fluid' alt=''>
						                                              </a>
						                                              <a class='post-comments' href=''><span><i class='fa fa-comments-o'></i>0</span></a>
						                                          </div>
						                                          <div class='post-meta clearfix'>
						                                              <p class='post-category'><a href='single.php?cid={$post['danhmuc_id']}'>{$post['danhmuc_ten']}</a></p>
						                                              <p class='post-date'>
						                                                  <time class='published'>{$post['date']}</time>
						                                              </p>
						                                          </div>
						                                          <h2 class='post-title'>
						                                            <a href='single.php?nid={$post['tintuc_id']}' title='{$post['tintuc_ten']}'>{$post['tintuc_ten']}</a>
						                                          </h2>
						                                          <div class='excerpt'>
						                                              <p>{$post['tintuc_mota']}</p>
						                                          </div>
						                                      </div>
						                                    </article>
						                                </div>
										            ";
										        }
											    ?>
						            
						                        <?php } ?>
												</div>
											</div>
											<nav class="pagination clearfix">
											    <div class="wp-pagenavi">
											        <span class="current">1</span>
											        <a class="page larger" href="">2</a>
											        <a class="page larger" href="">3</a>
											        <a class="page larger" href="">4</a>
											        <a class="page larger" href="">5</a>
											        <span class="extend">...</span>
											        <a class="larger page" href="">10</a>
											        <span class="extend">...</span>
											        <a class="nextpostslink" rel="next" href="http://dandiphuot.com/page/2">»</a>
											        <a class="last" href="http://dandiphuot.com/page/15">Trang cuối »</a>
											    </div>
											</nav>
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