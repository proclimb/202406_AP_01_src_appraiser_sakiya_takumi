<?php
// 画面を遷移させるための関数定義一覧。stock\model.phpで定義した関数も使用されている

// 仕入管理画面(仕入管理画面のトップページ)
//
function subStock()
{
    $param = getStockParam();

    if ($_REQUEST['act'] == 'guide') {
        // 案内日の開始日を1ヶ月前に設定
        $param["sGuideDTFrom"] = date('Y/m/d', mktime(0, 0, 0, date('m') - 1, date('d'), date('Y')));
    }

    if ($param["sDel"] == '') {
        $param["sDel"] = 1;
    }

    if (!$param["sPage"]) {
        $param["sPage"] = 1;
    }

    if (!$param["orderBy"]) {
        $param["orderBy"] = 'STOCKNO';
        $param["orderTo"] = 'DESC';
    }

    subMenu();
    subStockView($param);
}

//
// 仕入管理編集画面
//
function subStockEdit()
{
    $param = getStockParam();

    $param["stockNo"] = $_REQUEST['stockNo'];

    if ($param["stockNo"]) {
        $sql = fnSqlStockEdit($param["stockNo"]);
        $res = mysqli_query($param["conn"], $sql);
        $row = mysqli_fetch_array($res);

        // 登録された値を受け取り、表示させる。
        $param["charge"] = htmlspecialchars($row[0]);
        $param["rank"] = htmlspecialchars($row[1]);
        $param["article"] = htmlspecialchars($row[2]);
        $param["articleFuri"] = htmlspecialchars($row[3]);
        $param["room"] = htmlspecialchars($row[4]);
        $param["area"] = htmlspecialchars($row[5]);
        $param["station"] = htmlspecialchars($row[6]);
        $param["distance"] = htmlspecialchars($row[7]);
        $param["agent"] = htmlspecialchars($row[8]);
        $param["store"] = htmlspecialchars($row[9]);
        $param["cover"] = htmlspecialchars($row[10]);
        $param["visitDT"] = htmlspecialchars($row[11]);
        $param["deskPrice"] = htmlspecialchars($row[12]);
        $param["vendorPrice"] = htmlspecialchars($row[13]);
        $param["note"] = htmlspecialchars($row[14]);
        $param["how"] = htmlspecialchars($row[15]);
        $param["del"] = htmlspecialchars($row[16]);

        $param["purpose"] = '更新';
        $param["btnImage"] = 'btn_load.png';
    } else {
        $param["purpose"] = '登録';
        $param["btnImage"] = 'btn_enter.png';
    }

    subMenu();
    subStockEditView($param);
}

//
// 仕入管理編集完了処理
//
function subStockEditComplete()
{
    // mysqli_connectでDB接続する

    $conn = fnDbConnect();
    // var_dump($conn);
    /* object(mysqli)#1 (18) { ["affected_rows"]=> int(0) ["client_info"]=> string(14) "mysqlnd 7.4.33" ["client_version"]=> int(70433)
    ["connect_errno"]=> int(0) ["connect_error"]=> NULL ["errno"]=> int(0) ["error"]=> string(0) "" ["error_list"]=> array(0) { }
    ["field_count"]=> int(0) ["host_info"]=> string(25) "Localhost via UNIX socket" ["info"]=> NULL ["insert_id"]=> int(0) ["server_info"]=> string(6) "8.0.36" ["server_version"]=> int(80036)
    ["sqlstate"]=> string(5) "00000" ["protocol_version"]=> int(10) ["thread_id"]=> int(10) ["warning_count"]=> int(1) }
    */
    // HTMLエスケープ & $_REQUESTから$paramへ代入
    $param["sDel"] = htmlspecialchars($_REQUEST['sDel']);
    $param["sInsDTFrom"] = htmlspecialchars($_REQUEST['sInsDTFrom']);
    $param["sInsDTTo"] = htmlspecialchars($_REQUEST['sInsDTTo']);
    $param["sCharge"] = htmlspecialchars($_REQUEST['sCharge']);
    $param["sRank"] = $_REQUEST['sRank'];
    $param["sArticle"] = htmlspecialchars($_REQUEST['sArticle']);
    $param["sArticleFuri"] = htmlspecialchars($_REQUEST['sArticleFuri']);
    $param["sAreaFrom"] = htmlspecialchars($_REQUEST['sAreaFrom']);
    $param["sAreaTo"] = htmlspecialchars($_REQUEST['sAreaTo']);
    $param["sStation"] = htmlspecialchars($_REQUEST['sStation']);
    $param["sDistance"] = $_REQUEST['sDistance'];
    $param["sAgent"] = htmlspecialchars($_REQUEST['sAgent']);
    $param["sStore"] = htmlspecialchars($_REQUEST['sStore']);
    $param["sCover"] = htmlspecialchars($_REQUEST['sCover']);
    $param["sVisitDTFrom"] = htmlspecialchars($_REQUEST['sVisitDTFrom']);
    $param["sVisitDTTo"] = htmlspecialchars($_REQUEST['sVisitDTTo']);
    $param["sHow"] = $_REQUEST['sHow'];

    $param["orderBy"] = $_REQUEST['orderBy'];
    $param["orderTo"] = $_REQUEST['orderTo'];
    $param["sPage"] = $_REQUEST['sPage'];

    $param["stockNo"] = mysqli_real_escape_string($conn, $_REQUEST['stockNo']); // 仕入番号
    $param["charge"] = mysqli_real_escape_string($conn, $_REQUEST['charge']); // 担当者
    $param["rank"] = mysqli_real_escape_string($conn, $_REQUEST['rank']); // ランク
    $param["article"] = mysqli_real_escape_string($conn, $_REQUEST['article']); // 物件名
    $param["articleFuri"] = mysqli_real_escape_string($conn, $_REQUEST['articleFuri']); // 物件名(よみ)
    $param["room"] = mysqli_real_escape_string($conn, $_REQUEST['room']); // 部屋
    $param["area"] = mysqli_real_escape_string($conn, $_REQUEST['area']); // 面積
    $param["station"] = mysqli_real_escape_string($conn, $_REQUEST['station']); // 最寄駅
    $param["distance"] = mysqli_real_escape_string($conn, $_REQUEST['distance']); // 距離
    $param["agent"] = mysqli_real_escape_string($conn, $_REQUEST['agent']); // 業者名
    $param["store"] = mysqli_real_escape_string($conn, $_REQUEST['store']); // 店舗名
    $param["cover"] = mysqli_real_escape_string($conn, $_REQUEST['cover']); // 担当者名
    $param["visitDT"] = mysqli_real_escape_string($conn, $_REQUEST['visitDT']); // 内見
    $param["deskPrice"] = mysqli_real_escape_string($conn, $_REQUEST['deskPrice']); // 机上金額
    $param["vendorPrice"] = mysqli_real_escape_string($conn, $_REQUEST['vendorPrice']); // 売主希望金額
    $param["note"] = mysqli_real_escape_string($conn, $_REQUEST['note']); // 備考
    $param["how"] = mysqli_real_escape_string($conn, $_REQUEST['how']); // 仕入経緯
    $param["del"] = mysqli_real_escape_string($conn, $_REQUEST['del']); // 除外

    if ($param["stockNo"]) {
        $sql = fnSqlStockUpdate($param);
        $res = mysqli_query($conn, $sql);
    } else {
        // 次の番号を得る
        $param["stockNo"] = fnNextNo('STOCK');
        // INSERT文作成
        $sql = fnSqlStockInsert($param);
        var_dump($sql);
        /* string(394) "INSERT INTO TBLSTOCK(STOCKNO,CHARGE,RANK,ARTICLE,ARTICLEFURI,ROOM,AREA,STATION,DISTANCE,AGENT,STORE,COVER,VISITDT,DESKPRICE,VENDORPRICE,NOTE,HOW,INSDT,UPDT,DEL)
        VALUES('1','担当A','1','ファーストコート','ふぁーすとこーと','203','40','上野','1','エディックス','上野店','担当者A','2024/01/17','100','1000','登録確認','1',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,'1')" */
        // SQL文実行、結果を$resへ代入
        $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        // SQL 構文にエラーがあります。MySQL サーバーのバージョンに対応するマニュアルで、1 行目の 'RANK,ARTICLE,ARTICLEFURI,ROOM,AREA,STATION,DISTANCE,AGENT,STORE,COVER,VISITDT,DE' 付近の正しい構文を確認してください。
        // var_dump($res); // bool(false)
    }

    $_REQUEST['act'] = 'stockSearch';
    subStock();
}

//
// 仕入管理削除処理
//
function subStockDelete()
{
    $conn = fnDbConnect();

    $param["stockNo"] = $_REQUEST['stockNo'];

    $sql = fnSqlStockDelete($param["stockNo"]);
    $res = mysqli_query($conn, $sql);

    $_REQUEST['act'] = 'stockSearch';
    subStock();
}

//
// 仕入管理一括削除処理
//
function subStockListDelete()
{
    $conn = fnDbConnect();

    $delStockList = $_REQUEST['delStockList'];
    $delStockListArray = array();

    $delStockListArray = explode(",", $delStockList);
    $sql = fnSqlStockListDelete($delStockListArray);
    $res = mysqli_query($conn, $sql);

    $_REQUEST['act'] = 'stockSearch';
    subStock();
}

//
// 画面間引継ぎ情報
// $param配列を初期化し、DBに接続後、連想配列として値を代入
function getStockParam()
{
    // 初期化
    $param = array();

    // DB接続
    $param["conn"] = fnDbConnect();

    // 検索情報 連想配列として値を代入
    $param["sDel"] = htmlspecialchars($_REQUEST['sDel']);
    $param["sInsDTFrom"] = htmlspecialchars($_REQUEST['sInsDTFrom']);
    $param["sInsDTTo"] = htmlspecialchars($_REQUEST['sInsDTTo']);
    $param["sCharge"] = htmlspecialchars($_REQUEST['sCharge']);
    $param["sRank"] = $_REQUEST['sRank'];
    $param["sArticle"] = htmlspecialchars($_REQUEST['sArticle']);
    $param["sArticleFuri"] = htmlspecialchars($_REQUEST['sArticleFuri']);
    $param["sAreaFrom"] = htmlspecialchars($_REQUEST['sAreaFrom']);
    $param["sAreaTo"] = htmlspecialchars($_REQUEST['sAreaTo']);
    $param["sStation"] = htmlspecialchars($_REQUEST['sStation']);
    $param["sDistance"] = $_REQUEST['sDistance'];
    $param["sAgent"] = htmlspecialchars($_REQUEST['sAgent']);
    $param["sStore"] = htmlspecialchars($_REQUEST['sStore']);
    $param["sCover"] = htmlspecialchars($_REQUEST['sCover']);
    $param["sVisitDTFrom"] = htmlspecialchars($_REQUEST['sVisitDTFrom']);
    $param["sVisitDTTo"] = htmlspecialchars($_REQUEST['sVisitDTTo']);
    $param["sHow"] = $_REQUEST['sHow'];

    $param["orderBy"] = $_REQUEST['orderBy'];
    $param["orderTo"] = $_REQUEST['orderTo'];
    $param["sPage"] = $_REQUEST['sPage'];

    // 完成した連想配列が戻り値
    return $param;
}
