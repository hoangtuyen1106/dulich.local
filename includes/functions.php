<?php 
	// Xac dinh hang so cho dia chi tuyet doi
    define('BASE_URL', 'http://localhost/dulich/');

    //Tái định hướng người dùng về trang mặc đinh là index
    function redirect_to($page = 'index.php') {
        $url = BASE_URL . $page;
        header("Location: $url");
        exit();
    }

    function validate_id($id) {
        if(isset($id) && filter_var($id, FILTER_VALIDATE_INT, array('min_range' =>1))) {
            $val_id = $id;
            return $val_id;
        } else {
            return NULL;
        }
    } // End validate_id

    function the_excerpt($text, $string = 400) {
        $sanitized = strip_tags($text);
        if(strlen($sanitized) > $string) {
            $cutString = substr($sanitized,0,$string);
            $words = substr($sanitized, 0, strrpos($cutString, ' '));
            return $words .'...';
        } else {
            return $sanitized;
        }
       
    } // End the_excerpt

    function is_logged_in() {
        if(!isset($_SESSION['id'])) {
            redirect_to('login.php');
        }
    } 

    // Ham tao ra de kiem tra xem co phai la admin hay khong
    function is_admin() {
        return isset($_SESSION['role']) && ($_SESSION['role'] == 2);
    }

    function is_editor() {
        return isset($_SESSION['role']) && (($_SESSION['role'] == 1) || ($_SESSION['role'] == 2));
    }

    function is_user() {
        return isset($_SESSION['role']) && (($_SESSION['role'] == 2) || ($_SESSION['role'] == 1) || ($_SESSION['role'] == 0));
    }
    
    // Kiem tra xem nguoi dung co the vao trang admin hay khong?
    function admin_access() {
        if(!is_admin()) {
            redirect_to('admin/login.php');
        }
    }

    function editor_access() {
        if(!is_editor()) {
            redirect_to('admin/login.php');
        }
    }

    function user_access() {
        if(!is_user()) {
            redirect_to('admin/login.php');
        }
    }

    function confirm_query($result, $query) {
        global $dbc;
        if(!$result) {
            die("Query {$query} \n<br/> MySQL Error: " .$dbc->error);
        } 
    }


    function fetch_news($display = 5) {
        global $dbc;
        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;
        
        $query = "SELECT n.tintuc_hot ,n.id ,n.tintuc_ten, n.tintuc_mota, n.tintuc_noidung, n.tintuc_anh, DATE_FORMAT(n.tintuc_ngaytao, '%d Tháng %m, %y') AS date, c.danhmuc_ten, c.id AS catid ";
        $query .= " FROM tintuc AS n "; 
        $query .= " LEFT JOIN danhmuc AS c "; 
        $query .= " ON n.danhmuc_id = c.id ORDER BY n.tintuc_hot DESC LIMIT {$start}, {$display}";
        $result = $dbc->query($query);
        confirm_query($result, $query);

        if($result->num_rows > 0) {
            $posts = array();
            while($results = $result->fetch_array(MYSQLI_ASSOC)) {
                $posts[] = $results;
            }
            return $posts;
        } else {
            return FALSE;
        }

    }

    function fetch_news_all() {
        global $dbc;
        $query = "SELECT n.tintuc_hot ,n.id ,n.tintuc_ten, n.tintuc_mota, n.tintuc_noidung, n.tintuc_anh, DATE_FORMAT(n.tintuc_ngaytao, '%d Tháng %m, %y') AS date, c.danhmuc_ten, c.id AS catid ";
        $query .= " FROM tintuc AS n "; 
        $query .= " LEFT JOIN danhmuc AS c "; 
        $query .= " ON n.danhmuc_id = c.id ORDER BY n.tintuc_hot DESC ";
        $result = $dbc->query($query);
        confirm_query($result, $query);

        if($result->num_rows > 0) {
            $posts = array();
            while($results = $result->fetch_array(MYSQLI_ASSOC)) {
                $posts[] = $results;
            }
            return $posts;
        } else {
            return FALSE;
        }

    }

    function fetch_location($display = 5) {
        global $dbc;
        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;
        
        $query = "SELECT l.baiviet_diadiem_hot ,l.id, l.baiviet_diadiem_ten, l.baiviet_diadiem_mota, l.baiviet_diadiem_noidung, l.baiviet_diadiem_anh, DATE_FORMAT(l.baiviet_diadiem_ngaytao, '%d Tháng %m, %y') AS date, c.diadiem_ten, c.id AS catid ";
        $query .= " FROM baiviet_diadiem AS l "; 
        $query .= " LEFT JOIN diadiem AS c "; 
        $query .= " ON l.diadiem_id = c.id ORDER BY l.baiviet_diadiem_hot DESC LIMIT {$start}, {$display}";
        $result = $dbc->query($query);
        confirm_query($result, $query);
        
        if($result->num_rows > 0) {
            $posts = array();
            while($results = $result->fetch_array(MYSQLI_ASSOC)) {
                $posts[] = $results;
            }
            return $posts;
        } else {
            return FALSE;
        }

    }

    function fetch_story($display = 5) {
        global $dbc;
        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;
        
        $query = "SELECT s.id, s.user_id ,s.cauchuyen_tieude, s.cauchuyen_noidung, DATE_FORMAT(s.cauchuyen_ngay, '%d Tháng %m, %y') AS date, u.name AS uname, u.id AS uid ";
        $query .= " FROM cauchuyen AS s ";  
        $query .= " LEFT JOIN user AS u ON s.user_id = u.id ";  
        $query .= " WHERE cauchuyen_trangthai = 1 ";  
        $query .= " ORDER BY cauchuyen_hot DESC LIMIT {$start}, {$display}";
        $result = $dbc->query($query);
        confirm_query($result, $query);
        
        if($result->num_rows > 0) {
            $posts = array();
            while($results = $result->fetch_array(MYSQLI_ASSOC)) {
                $posts[] = $results;
            }
            return $posts;
        } else {
            return FALSE;
        }

    }

    function fetch_categories_news($display = 5, $id) {
        global $dbc;
        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;
        
        $query = "SELECT n.tintuc_hot ,n.id ,n.tintuc_ten, n.tintuc_mota, n.tintuc_noidung, n.tintuc_anh, DATE_FORMAT(n.tintuc_ngaytao, '%d Tháng %m, %y') AS date, c.danhmuc_ten, c.id AS catid ";
        $query .= " FROM tintuc AS n "; 
        $query .= " INNER JOIN danhmuc AS c "; 
        $query .= " ON n.danhmuc_id = c.id WHERE c.id = '{$id}' ORDER BY n.tintuc_hot DESC LIMIT {$start}, {$display}";
        $result = $dbc->query($query);
        confirm_query($result, $query);

        if($result->num_rows > 0) {
            // Tao ra array de luu lai ket qua
            $posts = array();
            // Neu co gia tri de tra ve
            while($results = $result->fetch_array(MYSQLI_ASSOC)) {
                $posts[] = $results;
            } // End while
            return $posts;
        } else {
            return FALSE; // Neu khong co thong tin nguoi dung trong CSDL
        }
    }

    function fetch_categories_location($display = 5, $id) {
        global $dbc;
        $start = (isset($_GET['s']) && filter_var($_GET['s'], FILTER_VALIDATE_INT, array('min_range' => 1))) ? $_GET['s'] : 0;
        
        $query = "SELECT l.baiviet_diadiem_hot ,l.id ,l.baiviet_diadiem_ten, l.baiviet_diadiem_mota, l.baiviet_diadiem_noidung, l.baiviet_diadiem_anh, DATE_FORMAT(l.baiviet_diadiem_ngaytao, '%d Tháng %m, %y') AS date, c.diadiem_ten, c.id AS catid";
        $query .= " FROM baiviet_diadiem AS l "; 
        $query .= " INNER JOIN diadiem AS c "; 
        $query .= " ON l.diadiem_id = c.id WHERE c.id = '{$id}' ORDER BY l.baiviet_diadiem_hot DESC LIMIT {$start}, {$display}";
        $result = $dbc->query($query);
        confirm_query($result, $query);

        if($result->num_rows > 0) {
            // Tao ra array de luu lai ket qua
            $posts = array();
            // Neu co gia tri de tra ve
            while($results = $result->fetch_array(MYSQLI_ASSOC)) {
                $posts[] = $results;
            } // End while
            return $posts;
        } else {
            return FALSE; // Neu khong co thong tin nguoi dung trong CSDL
        }
    }


    function get_news_by_id($id) {
        global $dbc;
        $query = " SELECT n.tintuc_ten, n.tintuc_mota, n.tintuc_noidung, n.tintuc_anh, n.tintuc_ngaytao, c.danhmuc_ten, c.id AS catid, "; 
        $query .= " DATE_FORMAT(n.tintuc_ngaytao, '%d Tháng %m, %y') AS date ";
        $query .= " FROM tintuc AS n ";
        $query .= " LEFT JOIN danhmuc AS c ";
        $query .= " ON n.danhmuc_id = c.id ";
        $query .= " WHERE n.id = {$id}";
        $query .= " LIMIT 1";
        $result = $dbc->query($query);
        confirm_query($result, $query);
        return $result;
    } 

    function get_story_by_id($id) {
        global $dbc;
        $query = " SELECT s.id , s.cauchuyen_tieude, u.name AS uname, u.id AS uid, s.cauchuyen_noidung, "; 
        $query .= " DATE_FORMAT(s.cauchuyen_ngay, '%d Tháng %m, %y') AS date ";
        $query .= " FROM cauchuyen AS s ";
        $query .= " LEFT JOIN user AS u ON s.user_id = u.id ";
        $query .= " WHERE s.id = {$id}";
        $query .= " LIMIT 1";
        $result = $dbc->query($query);
        confirm_query($result, $query);
        return $result;
    } 

    function get_location_by_id($id) {
        global $dbc;
        $query = " SELECT l.baiviet_diadiem_ten, l.baiviet_diadiem_mota, l.baiviet_diadiem_noidung, l.baiviet_diadiem_anh, l.baiviet_diadiem_ngaytao, c.diadiem_ten, c.id AS catid, "; 
        $query .= " DATE_FORMAT(l.baiviet_diadiem_ngaytao, '%d Tháng %m, %y') AS date ";
        $query .= " FROM baiviet_diadiem AS l ";
        $query .= " LEFT JOIN diadiem AS c ";
        $query .= " ON l.diadiem_id = c.id ";
        $query .= " WHERE l.id = {$id}";
        $query .= " LIMIT 1";
        $result = $dbc->query($query);
        confirm_query($result, $query);
        return $result;
    } 


    // Count Comment
    function countComment($type, $id) {
        global $dbc;
        $query = "SELECT count(id) FROM binhluan WHERE binhluan_kieu = '{$type}' AND foreign_id = {$id}";
        $result = $dbc->query($query);
        if($result->num_rows > 0) {
            list($count) = $result->fetch_array(MYSQLI_NUM);
          return $count;
        } else{
          return FALSE;
        }
    }

    function statistic($table) {
        global $dbc;
        $query = "SELECT count(id) FROM {$table}";
        $result = $dbc->query($query);
        confirm_query($result, $query);
        if($result->num_rows > 0) {
            list($count) = $result->fetch_array(MYSQLI_NUM);
            return $count;
        } else{
            return FALSE;
        }
    }

    function statisticComment($type) {
        global $dbc;
        $query = "SELECT count(id) FROM binhluan WHERE binhluan_kieu = '{$type}'";
        $result = $dbc->query($query);
        confirm_query($result, $query);
        if($result->num_rows > 0) {
            list($count) = $result->fetch_array(MYSQLI_NUM);
            return $count;
        } else{
            return FALSE;
        }
    }
    function statisticUser() {
        global $dbc;
        $query = "SELECT count(id) FROM user WHERE role = 0";
        $result = $dbc->query($query);
        confirm_query($result, $query);
        if($result->num_rows > 0) {
            list($count) = $result->fetch_array(MYSQLI_NUM);
            return $count;
        } else{
            return FALSE;
        }
    }

    function statisticUserApproval() {
        global $dbc;
        $query = "SELECT count(id) FROM user WHERE role = 0 AND active IS NULL";
        $result = $dbc->query($query);
        confirm_query($result, $query);
        if($result->num_rows > 0) {
            list($count) = $result->fetch_array(MYSQLI_NUM);
            return $count;
        } else{
            return FALSE;
        }
    }

    // Phan trang
    function pagination($id = NULL ,$display = 5, $table){
        global $dbc; global $start;
        if(isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
            $page = $_GET['p'];
        } else {
          
            $query = "SELECT COUNT(id) FROM {$table}";
            $result = $dbc->query($query);
            confirm_query($result, $query);
            list($record) = $result->fetch_array(MYSQLI_NUM);
            
            if($record > $display) {
                $page = ceil($record/$display);
            } else {
                $page = 1;
            }
        }
        
        $output = "<nav class='pagination clearfix'><div class='wp-pagenavi'>";
        if($page > 1) {
            $current_page = ($start/$display) + 1;
            
            // Nếu không phải ở trang đầu (hoặc 1) thì sẽ hiển thị Trang trước.
            if($current_page != 1) {
                $output .= "<a class='previouspostslink' rel='prev' href='?id={$id}&s=".($start - $display)."&p={$page}'>«</a>";
            }
            
            // Hiển thị những phần số còn lại của trang
            for($i = 1; $i <= $page; $i++) {
                if($i != $current_page) {
                    $output .= "<a class='page larger' href='?id={$id}&s=".($display * ($i - 1))."&p={$page}'>{$i}</a>";
                } else {
                    $output .= "<a class='page larger current'>{$i}</a>";
                }
            }// END FOR LOOP
            
            // Nếu không phải trang cuối, thì hiển thị trang kế.
            if($current_page != $page) {
                $output .= "<a class='nextpostslink ".$current_page."' rel='next' href='?id={$id}&s=".($start + $display)."&p={$page}'>»</a>";
            }
        } // END pagination section
            $output .= "</div></nav>";
            
            return $output;
    } // END pagination  

    // Phan trang
    function pagination_story($id = NULL ,$display = 5, $table){
        global $dbc; global $start;
        if(isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
            $page = $_GET['p'];
        } else {
          
            $query = "SELECT COUNT(id) FROM {$table} WHERE cauchuyen_trangthai = 1 ";
            $result = $dbc->query($query);
            confirm_query($result, $query);
            list($record) = $result->fetch_array(MYSQLI_NUM);
            
            if($record > $display) {
                $page = ceil($record/$display);
            } else {
                $page = 1;
            }
        }
        
        $output = "<nav class='pagination clearfix'><div class='wp-pagenavi'>";
        if($page > 1) {
            $current_page = ($start/$display) + 1;
            
            // Nếu không phải ở trang đầu (hoặc 1) thì sẽ hiển thị Trang trước.
            if($current_page != 1) {
                $output .= "<a class='previouspostslink' rel='prev' href='?id={$id}&s=".($start - $display)."&p={$page}'>«</a>";
            }
            
            // Hiển thị những phần số còn lại của trang
            for($i = 1; $i <= $page; $i++) {
                if($i != $current_page) {
                    $output .= "<a class='page larger' href='?id={$id}&s=".($display * ($i - 1))."&p={$page}'>{$i}</a>";
                } else {
                    $output .= "<a class='page larger current'>{$i}</a>";
                }
            }// END FOR LOOP
            
            // Nếu không phải trang cuối, thì hiển thị trang kế.
            if($current_page != $page) {
                $output .= "<a class='nextpostslink ".$current_page."' rel='next' href='?id={$id}&s=".($start + $display)."&p={$page}'>»</a>";
            }
        } // END pagination section
            $output .= "</div></nav>";
            
            return $output;
    } // END pagination 

    function pagination_category($id = NULL ,$display = 5, $table, $cat){
        global $dbc; global $start;
        if(isset($_GET['p']) && filter_var($_GET['p'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
            $page = $_GET['p'];
        } else {
          
            $query = "SELECT COUNT(id) FROM {$table} WHERE {$cat}_id = $id";
            $result = $dbc->query($query);
            confirm_query($result, $query);
            list($record) = $result->fetch_array(MYSQLI_NUM);
            
            if($record > $display) {
                $page = ceil($record/$display);
            } else {
                $page = 1;
            }
        }
        
        $output = "<nav class='pagination clearfix'><div class='wp-pagenavi'>";
        if($page > 1) {
            $current_page = ($start/$display) + 1;
            
            // Nếu không phải ở trang đầu (hoặc 1) thì sẽ hiển thị Trang trước.
            if($current_page != 1) {
                $output .= "<a class='previouspostslink' rel='prev' href='?id={$id}&s=".($start - $display)."&p={$page}'>«</a>";
            }
            
            // Hiển thị những phần số còn lại của trang
            for($i = 1; $i <= $page; $i++) {
                if($i != $current_page) {
                    $output .= "<a class='page larger' href='?id={$id}&s=".($display * ($i - 1))."&p={$page}'>{$i}</a>";
                } else {
                    $output .= "<a class='page larger current'>{$i}</a>";
                }
            }// END FOR LOOP
            
            // Nếu không phải trang cuối, thì hiển thị trang kế.
            if($current_page != $page) {
                $output .= "<a class='nextpostslink ".$current_page."' rel='next' href='?id={$id}&s=".($start + $display)."&p={$page}'>»</a>";
            }
        } // END pagination section
            $output .= "</div></nav>";
            
            return $output;
    } // END pagination 

?>

