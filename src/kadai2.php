<!DOCTYPE HTML>
<html>
<head>
    <title>イシュー管理システム</title>
</head>
<body>
    <form action="kadai2.php" method="post">
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
        <input type="radio" name="label" value="bug">バグ
        <input type="radio" name="label" value="feature">機能要求
        <br>
        <label>優先順位:</label>
        <input type="text" name="priority" size=5>
        <br>
        <label>イシューコミットID:</label>
        <input type="text" name="ID" size=5>
        <br>
        <input type="submit" value="送信">
    </form>

    <?php
    try {
        $pdo = new PDO("pgsql:host=dpg-cq6cq24s1f4s73e07gbg-a;dbname=nittc2024_j4exp_php_14_1;", "nittc2024_j4exp_php_14_1_user", "GO8qoM9YpAPut8mQpOSKrYePfmmmr4cQ");
        echo "Connected successfully.";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // フォームからデータを受け取る
            $user = $_POST['user'];
            $repo = $_POST['repo'];
            $title = $_POST['title'];
            $label = $_POST['label'];
            $priority = $_POST['priority'];
            $ID = $_POST['ID'];

            // データがすべて入力されている場合にのみ処理を行う
            if (!empty($user) && !empty($repo) && !empty($title) && !empty($label) && !empty($priority) && !empty($ID)) {
                // issuesテーブルに挿入
                $stmt_issues = $pdo->prepare("INSERT INTO issues (title, label, priority, issue_id) VALUES (:title, :label, :priority, :ID)");
                $stmt_issues->bindParam(':title', $title);
                $stmt_issues->bindParam(':label', $label);
                $stmt_issues->bindParam(':priority', $priority);
                $stmt_issues->bindParam(':ID', $ID);
                $stmt_issues->execute();

                // reposテーブルに挿入
                $stmt_repos = $pdo->prepare("INSERT INTO repos (username, reponame, id) VALUES (:username, :reponame, :id)");
                $stmt_repos->bindParam(':username', $user);
                $stmt_repos->bindParam(':reponame', $repo);
                $stmt_repos->bindParam(':id', $ID);
                $stmt_repos->execute();
            }

            // 更新処理
            if (isset($_POST['reload'])) {
                $re_priority = $_POST['re_priority'];
                $update_id = $_POST['update_id'];

                // 優先順位の更新
                $update_priority = $pdo->prepare("UPDATE issues SET priority = :priority WHERE issue_id = :id");
                $update_priority->bindParam(':priority', $re_priority);
                $update_priority->bindParam(':id', $update_id);
                $update_priority->execute();

                // 他の更新処理を追加する場合はここに記述する
            }

            // テーブル表示
            $sql = "SELECT * FROM repos JOIN issues ON issues.issue_id = repos.id ORDER BY priority DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            echo "<br>";
            echo "<table border='1'>";
            echo "<tr>";
            echo "<td>ユーザ名</td>";
            echo "<td>レポジトリ名</td>";
            echo "<td>イシュータイトル</td>";
            echo "<td>ラベル</td>";
            echo "<td>優先順位</td>";
            echo "<td>イシューコミットID</td>";
            echo "<td>状態</td>";
            echo "<td>完了コミットID</td>";
            echo "<td>更新ボタン</td>";
            echo "</tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['reponame']) . "</td>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . ($row['label'] == "bug" ? "バグ" : "機能要求") . "</td>";
                echo "<td><input type='text' name='re_priority' value='" . htmlspecialchars($row['priority']) . "'></td>";
                echo "<td>" . htmlspecialchars($row['issue_id']) . "</td>";
                echo "<td><select name='re_status'>
                          <option>未着手</option>
                          <option>着手中</option>
                          <option>完了</option>
                        </select></td>";
                echo "<td><input type='text' name='pID' size=5></td>";
                echo "<td><form action='' method='post'>
                          <input type='hidden' name='update_id' value='" . $row['issue_id'] . "'>
                          <input type='submit' value='更新' name='reload'>
                        </form></td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    ?>

</body>
</html>
