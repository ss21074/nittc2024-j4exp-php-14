<!DOCTYPE HTML>
<html>
    <head>
        <title>イシュー管理システム</title>      
    </head>
    <body>
        <form action = "kadai2.php" method = "post" >
            <br>
            <label>ユーザ名:</label>
            <input type="text" name="user" size=5>
            <br>
            <label>レポジトリ名:</label>
            <input type="text" name="repo" size=5>
            <br>
            <label>イシュータイトル:</label>
            <input type="text" name="title" size=5>
            <br>
            <label>ラベル:</label>
            <select name="label">
                <option> bug</option>
                <option> feature</option>
            </select>
            <br>
            <label>優先順位:</label>
            <input type="text" name="priority" size=5>
            <br>
            <label>イシューコミットID:</label>
            <input type="text" name="ID" size=5>
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
                    $user = $_POST['user'];
                    $repo = $_POST['repo'];
                    $title = $_POST['title'];
                    $label = $_POST['label'];
                    $priority = $_POST['priority'];
                    $ID = $_POST['ID'];
                    
                    $stmt = $pdo->prepare("INSERT INTO issues (title, label, priority, issue_commit) VALUES (:title, :label, :priority, :issue_commit)");
                    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
                    $stmt->bindParam(':label', $label, PDO::PARAM_STR);
                    $stmt->bindParam(':priority', $priority, PDO::PARAM_INT);
                    $stmt->bindParam(':issue_commit', $ID, PDO::PARAM_STR);
                    $stmt->execute();

                    $stmt = $pdo->prepare("INSERT INTO repos (username, reponame) VALUES (:username, :reponame)");
                    $stmt->bindParam(':username', $user, PDO::PARAM_STR);
                    $stmt->bindParam(':reponame', $repo, PDO::PARAM_STR);
                    $stmt->execute();

                }
                catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
                
            ?>

    </body>
</html>
