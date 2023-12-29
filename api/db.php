<?php
date_default_timezone_set("Asia/Taipei");
// session_save_path("/tmp");
session_start();
class DB
{
    // 定義class函數 "資料庫" "PDO" "TABLE"
    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=db15";
    protected $pdo;
    protected $table;
    //$sql 放置MariaDB 的查找語句
// 
// 執行DB時 將自訂義的table 帶入 DB函數
// PDO:PHP Data Objects_登入
    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, 'root', '');
    }
    // 
// 測試檢視資料表用
    function all($where = '', $other = '')
    {
        $sql = "select * from `$this->table` ";
        $sql = $this->sql_all($sql, $where, $other);
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function count($where = '', $other = '')
    {
        $sql = "select count(*) from `$this->table` ";
        $sql = $this->sql_all($sql, $where, $other);
        return $this->pdo->query($sql)->fetchColumn();
    }


    function math($math, $col = "*", $array = "", $other = "")
    {
        $sql = "select $math(`$col`) from `$this->table` ";
        $sql = $this->sql_all($sql, $array, $other);
        return $this->pdo->query($sql)->fetchColumn();
    }



    // SUM-計算table v col 中的 "數值"
// 要指定欄位
    function sum($col, $where = '', $other = '')
    {
        $sql = "select sum(`$col`) from `$this->table` ";
        $sql = $this->sql_all($sql, $where, $other);
        return $this->pdo->query($sql)->fetchColumn();
    }
    // 

    // max
// 要指定欄位
    function max($col, $where = '', $other = '')
    {
        $sql = "select max(`$col`) from `$this->table` ";
        $sql = $this->sql_all($sql, $where, $other);
        return $this->pdo->query($sql)->fetchColumn();
        ;
    }
    // 

    // min
// 要指定欄位
    function min($col, $where = '', $other = '')
    {
        $sql = "select min(`$col`) from `$this->table` ";
        $sql = $this->sql_all($sql, $where, $other);
        return $this->pdo->query($sql)->fetchColumn();
        ;
    }
    // 

    // 
    // 查找特定ID 或 條件
    function find($a)
    {
        $sql = "select * from `$this->table` ";

        if (is_array($a)) {
            $tmp = $this->a2s($a);
            $sql .= " where " . join(" && ", $tmp);
        } else if (is_numeric($a)) {
            $sql .= " where `id`='$a'";
        } else {
            echo "錯誤:參數的資料型態比須是數字或陣列";
        }
        //echo 'find=>'.$sql;
        $row = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    // 
    // 做儲存/更新用
    function save($array)
    {
        // 驗證資料是否存在
        // 若存在則更新內容
        if (isset($array['id'])) {
            $sql = "update `$this->table` set ";

            if (!empty($array)) {
                $tmp = $this->a2s($array);
            } else {
                echo "錯誤:缺少要編輯的欄位陣列";
            }

            $sql .= join(",", $tmp);
            $sql .= " where `id`='{$array['id']}'";
        }
        // 
        // 若不存在則新增一筆資料
        else {
            $sql = "insert into `$this->table` ";
            $cols = "(`" . join("`,`", array_keys($array)) . "`)";
            $vals = "('" . join("','", $array) . "')";

            $sql = $sql . $cols . " values " . $vals;
        }

        return $this->pdo->exec($sql);
    }
    // 
// 刪除資料
    function del($id)
    {
        $sql = "delete from `$this->table` where ";

        if (is_array($id)) {
            $tmp = $this->a2s($id);
            $sql .= join(" && ", $tmp);
        } else if (is_numeric($id)) {
            $sql .= " `id`='$id'";
        } else {
            echo "錯誤:參數的資料型態比須是數字或陣列";
        }
        //echo $sql;

        return $this->pdo->exec($sql);
    }
    // 
    // 受不了重複的程式碼
    // 其他
    function q($sql)
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    }
    // 簡化上述重複程式，詳細見上列函式
// array to sql
    private function a2s($array)
    {
        foreach ($array as $col => $value) {
            $tmp[] = "`$col`='$value'";
        }
        return $tmp;
    }
    //  
    // 
    private function sql_all($sql, $array, $other)
    {
        if (isset($this->table) && !empty($this->table)) {

            if (is_array($array)) {

                if (!empty($array)) {
                    $tmp = $this->a2s($array);
                    $sql .= " where " . join(" && ", $tmp);
                }
            } else {
                $sql .= " $array";
            }

            $sql .= $other;
            return $sql;
        } else {
            echo "錯誤:沒有指定的資料表名稱";
        }
    }
}

function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

// 記得:這裡的table是輸入字串，不是欄位
// 引入字串，字串才去帶`col`

function to($url)
{
    header("location:$url");
}

// 分頁標籤



$Total = new DB('total');

if(!isset($_SESSION['visited'])){
    if($Total->count(['date'=>date('Y-m-d')])>0){   
        $total=$Total->find(['date'=>date('Y-m-d')]);
        $total['total']++;
        $total->save($total);
    }else{
        $Total->save(['total'=>1,'date'=>date('Y-m-d')]);
    }

    // $_SESSION(['visited']) = 1;
    // wrong
    
    $_SESSION['visited'] = 1;

}

// if (!isset($_SESSION['visited'])) {
//     if ($Total->count(['date' => date('Y-m-d')]) > 0) {
//         $total = $Total->find(['date' => date('Y-m-d')]);
//         $total['total']++;
//         $Total->save($total);
//     } else {
//         $Total->save(['total' => 1, 'date' => date('Y-m-d')]);
//     }

//     $_SESSION['visited'] = 1;

// }


?>