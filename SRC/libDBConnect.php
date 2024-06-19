<?php

//
// ログイン
//
function fnSqlLogin($id, $pw)
{
    $id = addslashes($id);
    $sql = "SELECT USERNO,AUTHORITY FROM TBLUSER";
    $sql .= " WHERE DEL = 1";
    $sql .= " AND ID = '$id'";
    $sql .= " AND PASSWORD = '$pw'";

    return ($sql);
}

//
// ユーザー情報リスト
//
function fnSqlAdminUserList()
{
    $sql = "SELECT USERNO,NAME,ID,PASSWORD,AUTHORITY FROM TBLUSER";
    $sql .= " WHERE DEL = 1";
    $sql .= " ORDER BY AUTHORITY ASC,NAME ASC";

    return ($sql);
}

//
// ユーザー情報詳細
//
function fnSqlAdminUserEdit($userNo)
{
    $sql = "SELECT NAME,ID,PASSWORD,AUTHORITY FROM TBLUSER";
    $sql .= " WHERE USERNO = $userNo";

    return ($sql);
}

//
// ユーザー情報更新
//
function fnSqlAdminUserUpdate($userNo, $name, $id, $password, $authority)
{
    $pass = addslashes(hash('adler32', $password));
    $sql = "UPDATE TBLUSER";
    $sql .= " SET NAME = '$name'";
    $sql .= ",ID = '$id'";
    $sql .= ",PASSWORD = '$pass'";
    $sql .= ",AUTHORITY = '$authority'";
    $sql .= ",UPDT = CURRENT_TIMESTAMP";
    $sql .= " WHERE USERNO = '$userNo'";

    return ($sql);
}

//
// ユーザー情報登録
//
function fnSqlAdminUserInsert($userNo, $name, $id, $password, $authority)
{
    $pass = addslashes(hash('adler32', $password));
    $sql = "INSERT INTO TBLUSER(";
    $sql .= "USERNO,NAME,ID,PASSWORD,AUTHORITY,INSDT,UPDT,DEL";
    $sql .= ")VALUES(";
    $sql .= "'$userNo','$name','$id','$pass','$authority',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,1)";

    return ($sql);
}

//
// ユーザー情報削除
//
function fnSqlAdminUserDelete($userNo)
{
    $sql = "UPDATE TBLUSER";
    $sql .= " SET DEL = 0";
    $sql .= ",UPDT = CURRENT_TIMESTAMP";
    $sql .= " WHERE USERNO = '$userNo'";

    return ($sql);
}

//
// 次の番号を得る
//
function fnNextNo($t)
{
    $conn = fnDbConnect();

    $sql = "SELECT MAX(" . $t . "NO) FROM TBL" . $t;
    $res = mysqli_query($conn, $sql);
    // var_dump($res);
    $row = mysqli_fetch_array($res);
    // var_dump($row);
    // 最大値があった場合、最大値に1を足して次の番号を得る
    if ($row[0]) {
        $max = $row[0] + 1;
        // 最大値がなかった場合、最初の仕入番号になる
    } else {
        $max = 1;
    }
    // 次の番号を返す
    return ($max);
}
