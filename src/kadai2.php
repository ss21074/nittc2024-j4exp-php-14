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
            <input type="submit" value="送信">
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
                try{
                    $ユーザ名 = $_POST['ユーザ名'];
                    $レポジトリ名 = $_POST['レポジトリ名'];
                    $イシュータイトル = $_POST['イシュータイトル'];
                    $ラベル = $_POST['ラベル'];
                    $優先順位 = $_POST['優先順位'];
                    $担当者 = $_POST['担当者'];
                    $イシューコミットID = $_POST['イシューコミットID'];
                    print $ユーザ名;
                    print $レポジトリ名;
                    print $イシュータイトル;

                    $stmt = $pdo->prepare('INSERT INTO issues (title,label,priority,issue_commit) VALUES (:イシュータイトル, :ラベル, :優先順位, :イシューコミットID)');
                    
                    $stmt->bindParam('イシュータイトル', $イシュータイトル, PDO::PARAM_STR);
                    $stmt->bindParam('ラベル', $ラベル, PDO::PARAM_STR);
                    $stmt->bindParam('優先順位', $優先順位, PDO::PARAM_INT);
                    $stmt->bindParam('イシューコミットID', $イシューコミットID, PDO::PARAM_STR);
                    
                    $stmt->execute();

                    $stmt = $pdo->prepare("INSERT INTO repos (username, reponame) VALUES (:ユーザ名, :レポジトリ名)");

                    $stmt->bindParam('ユーザ名', $ユーザ名, PDO::PARAM_STR);
                    $stmt->bindParam('レポジトリ名', $レポジトリ名, PDO::PARAM_STR);
                    
                    $stmt->execute();

                }
                catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
                
                





            ?>
        



    </body>
</html>