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

            <input type="radio" name="label" value = "bug">バグ
            <input type="radio" name="label" value = "feature">機能要求

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
                    
                    if (!empty($user) && !empty($repo) && !empty($title) && !empty($label) && !empty($priority) && !empty($ID)) {
                        $stmt_issues = $pdo->prepare("INSERT INTO issues (title, label, priority, issue_id) VALUES (:title, :label, :priority, :ID)");
                        $stmt_issues->bindParam(':title', $title);
                        $stmt_issues->bindParam(':label', $label);
                        $stmt_issues->bindParam(':priority', $priority);
                        $stmt_issues->bindParam(':ID', $ID);
                        $stmt_issues->execute();

                        $stmt_repos = $pdo->prepare("INSERT INTO repos (username, reponame,id) VALUES (:username, :reponame, :id)");
                        $stmt_repos->bindParam(':username', $user);
                        $stmt_repos->bindParam(':reponame', $repo);
                        $stmt_repos->bindParam(':id', $ID);
                        $stmt_repos->execute();
                    }



                    $new_table = "SELECT * FROM repos JOIN issues ON issues.issue_id = repos.id";
                    $stmt = $pdo->prepare($new_table);
                    $stmt->execute();

                    $sql = "$new_table ORDER BY priority DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    echo "<br>";
                    echo "<table border='1'>";
                            echo "<tr>";
                                echo "<td>" . "ユーザ名" . "</td>";
                                echo "<td>" . "レポジトリ名" . "</td>";
                                echo "<td>" . "イシュータイトル" . "</td>";
                                echo "<td>" . "ラベル" . "</td>";
                                echo "<td>" . "優先順位" . "</td>";
                                echo "<td>" . "イシューコミットID" . "</td>";
                                echo "<td>" . "状態" . "</td>";
                                echo "<td>" . "完了コミットID" . "</td>";
                                echo "<td>" . "更新ボタン" . "</td>";
                            echo "</tr>";

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                            echo "<td>" . $row['username'] . "</td>";
                            echo "<td>" . $row['reponame'] . "</td>";
                            echo "<td>" . $row['title'] . "</td>";
                            if($row['label'] == "bug"){
                                $row['label'] = "バグ";
                            }
                            else if($row['label'] == "feature"){
                                $row['label'] = "機能要求";
                            }
                            echo "<td>" . $row['label'] . "</td>";
                            echo "<td>"."<input type='text' name='priority' value=".$row['priority'].">"."</td>";
                            echo "<td>" . $row['issue_id'] . "</td>";
                            echo "<td>"."<select name='status'>";
                                echo "<option>未着手</option>";
                                echo "<option>着手中</option>";
                                echo "<option>完了</option>";
                            echo "</select>"."</td>";
                            echo "<td>"."<input type='text' name='pID' size=5>"."</td>";
                            echo "<td>"."<input type='submit' value='更新'>"."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    

                }
                catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }






            ?>

    </body>
</html>


