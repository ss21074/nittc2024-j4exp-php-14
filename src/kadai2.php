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
                    
                    if($user != null && $repo != null && $title != null $label != null && $priority != null $ID != null){
                        $stmt = $pdo->prepare("INSERT INTO issues (title, label, priority, issue_id) VALUES (:title, :label, :priority, :ID)");
                        $stmt->bindParam(':title', $title);
                        $stmt->bindParam(':label', $label);
                        $stmt->bindParam(':priority', $priority);
                        $stmt->bindParam(':ID', $ID);
                        $stmt->execute();

                        $stmt = $pdo->prepare("INSERT INTO repos (username, reponame,id) VALUES (:username, :reponame, :id)");
                        $stmt->bindParam(':username', $user);
                        $stmt->bindParam(':reponame', $repo);
                        $stmt->bindParam(':id', $ID);
                        $stmt->execute();

                    }


                    $new_table = "SELECT * FROM repos JOIN issues ON issues.issue_id = repos.id"
                    $stmt = $pdo->prepare($new_table);
                    $stmt->execute();
                    
                    


                }
                catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }



                
            ?>

    </body>
</html>
