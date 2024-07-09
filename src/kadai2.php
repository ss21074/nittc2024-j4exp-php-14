<!DOCTYPE HTML>
<html>
    <head>
        <title>イシュー管理システム</title>      
    </head>
    <body>
        <form action = "kadai2.php" method = "post" >
            <br>
            <label>ユーザ名:</label>
            <input type="text" name="ユーザ名" size=5>
            <br>
            <label>レポジトリ名:</label>
            <input type="text" name="レポジトリ名" size=5>
            <br>
            <label>イシュータイトル:</label>
            <input type="text" name="イシュータイトル" size=5>
            <br>
            <label>ラベル:</label>
            <select name="ラベル">
                <option> バグ</option>
                <option> 機能要求</option>
            </select>
            <br>
            <label>優先順位:</label>
            <input type="text" name="優先順位" size=5>
            <br>
            <label>担当者:</label>
            <input type="text" name="担当者" size=5>
            <br>
            <label>イシューコミットID:</label>
            <input type="text" name="イシューコミットID" size=5>
            <br>
        </from>
            <?php
                try {
                    $pdo = new PDO("pgsql:host=dpg-cq6cq24s1f4s73e07gbg-a;
                    dbname=nittc2024_j4exp_php_14_1;
                    user=nittc2024_j4exp_php_14_1_user;
                    password=GO8qoM9YpAPut8mQpOSKrYePfmmmr4cQ;");
                    echo "Connected successfully.";
                } 
                catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
                
                $result = $connect->query("SELECT * FROM order;");
                foreach($results as $row) {
                ?><?=row["item"]?> : <?=row["price"]?><br>
                <?php
                }


            ?>
        



    </body>
</html>