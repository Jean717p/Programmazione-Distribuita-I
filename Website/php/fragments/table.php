 <?php
    if(!defined('DIR_BASE')){
        require_once '../../config.php';
    }
    require_once DIR_PHP_FUNCTIONS.'session_manager.php';
    require_once DIR_PHP_FUNCTIONS.'db_manager.php';
    require_once DIR_PHP_FUNCTIONS.'lib.php';
    start_session();

    $conn = new DatabaseInterface();
    $x = TABLE_X;
    $y = TABLE_Y;
    $rect_len = RECT_LENGHT;
    $editable = false;
    $result_set;
    try{
        $result_set = get_rects($conn->query("SELECT * FROM rectangles;"));
    }catch(Exception $e){
        $result_set = 0;
        echo $e->getMessage();
    }
    $matrix=  array();
    $username = "";
    foreach (range(0,$y-1) as $row) {
        foreach (range(0,$x-1) as $col) {
            $matrix[$row][$col][0] = 0;
        }
    }
    if (is_logged()){
        $username = $_SESSION['username'];
        if(isset($_GET['ajax']) || is_page("moves.php")){
            $editable = true;
        }
    }
    foreach($result_set as $item){
        if($username!="" && $username == $item->username){
            $mVal = 2;
        }
        else{
            $mVal = 1;
        }
        if($item->x0 == $item->x1){
            foreach (range($item->y0,$item->y1) as $row){
                $matrix[$row][$item->x0][0] = $mVal;
                $matrix[$row][$item->x0][1] = $item->username;
            }
        }
        else{
            foreach (range($item->x0,$item->x1) as $col){
                $matrix[$item->y0][$col][0] = $mVal;
                $matrix[$item->y0][$col][1] = $item->username;
            }
        }
    }
    ?>
    <div id="game_table" class="table-responsive">
         <table id='gameboard'
                data-tab-h="<?php echo TABLE_Y; ?>"
                data-tab-w="<?php echo TABLE_X; ?>"
                data-rect_lenght="<?php echo RECT_LENGHT; ?>"
                class="table table-bordered">
             <?php
                echo '<tbody>';
                echo '<tr><td class="table-light my_td first_row"></td>';
                foreach (range(0,$x-1)as $value){
                    echo '<td class="table-light my_td first_row">'.$value.'</td>';
                }
                echo '</tr>';
                foreach (range(0,$y-1) as $row) {
                    echo '<tr>';
                    echo '<td class="table-light my_td first_column">'.$row.'</td>';
                    foreach (range(0,$x-1) as $col) {
                        switch ($matrix[$row][$col][0]){
                            case 0:
                                if($username == "" || $editable==false){
                                    echo '<td id="'.$row.'-'.$col.'" class="my_td table-light"></td>';
                                }
                                else{
                                    echo '<td id="'.$row.'-'.$col.'" class="my_td table-light avaible_td"></td>';
                                }
                                break;
                            case 1:
                                if($username!= ""){
                                    echo '<td id="'.$row.'-'.$col.'" title="'.$matrix[$row][$col][1].'" class="my_td table-dark"></td>';
                                }
                                else{
                                    echo '<td id="'.$row.'-'.$col.'" class="my_td table-dark"></td>';
                                }
                                break;
                            case 2:
                                echo '<td id="'.$row.'-'.$col.'" class="my_td bg-success"></td>';
                                break;
                            default:
                        }
                    }
                    echo '</tr>';
                }
                echo '</tbody>';
                ?>
        </table>
    </div>
