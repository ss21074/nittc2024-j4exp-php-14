<!DOCTYPE HTML>
<html>
    <head>
        <title>イシュー管理システム</title>
        <style>
        body {
        background-color: #f0f8ff;
        font-family: Arial, sans-serif;
        margin: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
            margin-top: 20px;
        }
        label {
            display: inline-block;
            width: 100px;
            text-align: center;
            margin-right: 10px;
        }
        input[type="text"] {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a {
            color: #0000ff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        </style>      
    </head>
    <body>
    <h1>イシュー管理システム</h1>
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
                $pdo = new PDO("pgsql:host=dpg-cq6cq24s1f4s73e07gbg-a;dbname=nittc2024_j4exp_php_14_1;user=nittc2024_j4exp_php_14_1_user;password=GO8qoM9YpAPut8mQpOSKrYePfmmmr4cQ;");
                echo "Connected successfully.";
            } 
            catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }

            try {
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $user = $_POST['user'] ?? '';
                    $repo = $_POST['repo'] ?? '';
                    $title = $_POST['title'] ?? '';
                    $label = $_POST['label'] ?? '';
                    $priority = $_POST['priority'] ?? '';
                    $ID = $_POST['ID'] ?? '';
                    
                    if (!empty($user) && !empty($repo) && !empty($title) && !empty($label) && !empty($priority) && !empty($ID)) {
                        $stmt_issues = $pdo->prepare("INSERT INTO issues (title, label, priority, issue_id) VALUES (:title, :label, :priority, :ID)");
                        $stmt_issues->bindParam(':title', $title);
                        $stmt_issues->bindParam(':label', $label);
                        $stmt_issues->bindParam(':priority', $priority);
                        $stmt_issues->bindParam(':ID', $ID);
                        $stmt_issues->execute();

                        $stmt_repos = $pdo->prepare("INSERT INTO repos (username, reponame, id) VALUES (:username, :reponame, :id)");
                        $stmt_repos->bindParam(':username', $user);
                        $stmt_repos->bindParam(':reponame', $repo);
                        $stmt_repos->bindParam(':id', $ID);
                        $stmt_repos->execute();
                    }

                    if (isset($_POST['update_id'])) {
                        $update_id = $_POST['update_id'];
                        $re_priority = $_POST['re_priority'] ?? '';
                        $re_status = $_POST['re_status'] ?? '';
                        $pID = $_POST['pID'] ?? '';

                        $update_query = "UPDATE issues SET ";
                        $params = [];

                        if (!empty($re_priority)) {
                            $update_query .= "priority = :priority, ";
                            $params[':priority'] = $re_priority;
                        }
                        if (!empty($re_status)) {
                            $update_query .= "status = :status, ";
                            $params[':status'] = $re_status;
                        }
                        if (!empty($pID)) {
                            $update_query .= "complete_commit = :complete_commit, ";
                            $params[':complete_commit'] = $pID;
                        }

                        $update_query = rtrim($update_query, ', ');
                        $update_query .= " WHERE issue_id = :id";
                        $params[':id'] = $update_id;

                        $stmt_update = $pdo->prepare($update_query);
                        foreach ($params as $key => &$val) {
                            $stmt_update->bindParam($key, $val);
                        }
                        $stmt_update->execute();
                    }
                }

                $new_table = "SELECT * FROM repos JOIN issues ON issues.issue_id = repos.id ORDER BY priority DESC";
                $stmt = $pdo->prepare($new_table);
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
                    echo "<td>コミットのURL</td>";
                    echo "<td>ワークツリーのURL</td>";
                    echo "<td>コミット間差分</td>";
                echo "</tr>";

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // 値の前後のスペースを削除
                    $username = trim(htmlspecialchars($row['username']));
                    $reponame = trim(htmlspecialchars($row['reponame']));
                    $issue_id = trim(htmlspecialchars($row['issue_id']));
                    $complete_commit = trim(htmlspecialchars($row['complete_commit']));

                    $commit_url = "https://github.com/" . $username . "/" . $reponame . "/commits/" . $issue_id;
                    $tree_url = "https://github.com/" . $username . "/" . $reponame . "/tree/" . $issue_id;
                    $compare_url = "https://github.com/" . $username . "/" . $reponame . "/compare/" . $issue_id . "..." . $complete_commit;
                    
                    echo "<tr>";
                        echo "<td>" . $username . "</td>";
                        echo "<td>" . $reponame . "</td>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . ($row['label'] == "bug" ? "バグ" : "機能要求") . "</td>";
                        echo "<td><form action='kadai2.php' method='post'>
                                <input type='text' name='re_priority' value='" . htmlspecialchars($row['priority']) . "'>
                              </td>";
                        echo "<td>" . $issue_id . "</td>";
                        echo "<td><select name='re_status'>
                                <option value='not_started'" . ($row['status'] == 'not_started' ? ' selected' : '') . ">未着手</option>
                                <option value='in_progress'" . ($row['status'] == 'in_progress' ? ' selected' : '') . ">着手中</option>
                                <option value='completed'" . ($row['status'] == 'completed' ? ' selected' : '') . ">完了</option>
                              </select></td>";
                        echo "<td><input type='text' name='pID' size=5 value='" . $complete_commit . "'></td>";
                        echo "<td>
                                <input type='hidden' name='update_id' value='" . $issue_id . "'>
                                <input type='submit' value='更新' name='reload'>
                              </form></td>";
                        echo "<td><a href='" . $commit_url . "' target='_blank'>コミットURL</a></td>";
                        echo "<td><a href='" . $tree_url . "' target='_blank'>ワークツリーURL</a></td>";
                        echo "<td><a href='" . $compare_url . "' target='_blank'>コミット間差分</a></td>";
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



