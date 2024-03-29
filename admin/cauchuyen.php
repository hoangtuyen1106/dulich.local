<?php $title = "Câu chuyện"; ?>
<?php include('../includes/mysqli_connect.php');?>
<?php include('../includes/functions.php');?>
<?php include('includes/header.php');?>
<?php include('includes/top-header.php');?>
<?php include('includes/left-sidebar.php');?>
<?php editor_access();?>
<div class="be-content">
    <div class="main-content container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-table">
            <div class="card-header">Câu chuyện</div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover" style="font-size: 13px">
                    <thead>
                        <tr>
                            <th style="width:20%;"><a href="cauchuyen.php?sort=title">Tiêu đề</a></th>
                            <th style="width:10%;"><a href="cauchuyen.php?sort=name">Tác giả</a></th>
                            <th>Nội dung</th>
                            <th style="width:95px;" class="actions"><a href="cauchuyen.php?sort=status">Trạng thái</a></th>
                            <th style="width:95px;" class="actions"><a href="cauchuyen.php?sort=hot">Nổi bật</a></th>
                            <th style="width:80px;" class="actions"><a href="cauchuyen.php?sort=by">Ngày tạo</a></th>
                            <th style="width:75px;" class="actions"></th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php 
                        //sap xep theo thu tu
                      if(isset($_GET['sort'])) {
                        switch ($_GET['sort']) {
                          case 'title':
                            $order_by = 'cauchuyen_tieude';
                            break;

                          case 'name':
                            $order_by = 'uname';
                            break;

                          case 'status':
                            $order_by = 'cauchuyen_trangthai';
                            break;

                          case 'hot':
                            $order_by = 'cauchuyen_hot';
                            break;

                          case 'by':
                            $order_by = 'date';
                            break;
                          
                          default:
                            $order_by = 'id';
                            break;
                        }
                      } else {
                        $order_by = 'id';
                      }

                        // truy vấn CSDL
                        $query = "SELECT s.id, s.cauchuyen_tieude, u.name AS uname, u.id AS uid, s.cauchuyen_noidung, DATE_FORMAT(s.cauchuyen_ngay, '%d/%m/%y') AS date , s.cauchuyen_trangthai, s.cauchuyen_hot ";
                        $query .= " FROM cauchuyen AS s ";
                        $query .= " LEFT JOIN user AS u ";
                        $query .= " ON s.user_id = u.id ";
                        $query .= " ORDER BY {$order_by} ASC";
                          if ($result = $dbc->query($query)) {
                            $status = array(0 => 'Chưa kiểm duyệt', 1 => 'kiểm duyệt');
                            $hot = array(0 => 'Không', 1 => 'có');
                            while ($cauchuyens = $result->fetch_array(MYSQLI_ASSOC)) {
                              echo "
                                <tr>
                                  <td>".the_excerpt($cauchuyens['cauchuyen_tieude'], 50)."</td>
                                  <td>".the_excerpt($cauchuyens['uname'], 50)."</td>
                                  <td>".the_excerpt($cauchuyens['cauchuyen_noidung'], 200)."</td>
                                  <td class='actions'>".$status[($cauchuyens['cauchuyen_trangthai'])]."</td>
                                  <td class='actions'>".$hot[($cauchuyens['cauchuyen_hot'])]."</td>
                                  <td class='actions'>{$cauchuyens['date']}</td>
                                  <td class='actions'>
                                    <span style='padding:0 3px'>
                                    <a href='suacauchuyen.php?id={$cauchuyens['id']}' class='icon' href='#'><i class='mdi mdi-edit'></i></a>
                                    </span>
                                    <span style='padding:0 3px'>
                                    <a id='{$cauchuyens['id']}' class='icon remove'><i class='mdi mdi-delete'></i>
                                    </a>
                                    </span>
                                  </td>
                              </tr>
                              ";
                            }
                          }
                       ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
      </div>
    </div>
</div>
<script>
  $(document).ready(function() {
    functions.remove_story();
  });
</script>
<?php include('includes/right-sidebar.php');?>
<?php include('includes/footer.php');?>