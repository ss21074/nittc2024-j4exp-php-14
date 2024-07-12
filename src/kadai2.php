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
                <option> bug</option>
                <option> feature</option>
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
                    $user = $_POST['ユーザ名'];
                    $repo = $_POST['レポジトリ名'];
                    $title = $_POST['イシュータイトル'];
                    $label = $_POST['ラベル'];
                    $priority = $_POST['優先順位'];
                    $ID = $_POST['イシューコミットID'];
                    print $ユーザ名;
                    print $レポジトリ名;
                    print $イシュータイトル;
                    
                    $stmt = $pdo->prepare("INSERT INTO issues (title, label, priority, issue_commit) VALUES (:title, :label, :priority, :issue_commit)");
    
                    // パラメータをバインド
                    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
                    $stmt->bindParam(':label', $label, PDO::PARAM_STR);
                    $stmt->bindParam(':priority', $priority, PDO::PARAM_INT);
                    $stmt->bindParam(':issue_commit', $ID, PDO::PARAM_STR);
                    
                    // ステートメントを実行
                    $stmt->execute();

                    // 'repos' テーブルにデータを挿入する準備
                    $stmt = $pdo->prepare("INSERT INTO repos (username, reponame) VALUES (:username, :reponame)");

                    // パラメータをバインド
                    $stmt->bindParam(':username', $user, PDO::PARAM_STR);
                    $stmt->bindParam(':reponame', $repo, PDO::PARAM_STR);


                    /*$stmt->bindParam('イシュータイトル', $イシュータイトル, PDO::PARAM_STR);
                    $stmt->bindParam('ラベル', $ラベル, PDO::PARAM_STR);
                    $stmt->bindParam('優先順位', $優先順位, PDO::PARAM_INT);
                    $stmt->bindParam('イシューコミットID', $イシューコミットID, PDO::PARAM_STR);
                    
                    $stmt->execute();

                    $stmt = $pdo->prepare("INSERT INTO repos (username, reponame) VALUES (:ユーザ名, :レポジトリ名)");

                    $stmt->bindParam('ユーザ名', $ユーザ名, PDO::PARAM_STR);
                    $stmt->bindParam('レポジトリ名', $レポジトリ名, PDO::PARAM_STR);
                    */
                    $stmt->execute();

                }
                catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
                
            ?>

    </body>
</html>
