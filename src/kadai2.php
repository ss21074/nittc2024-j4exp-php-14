<!DOCTYPE HTML>
<html>
    <head>
        <title>イシュー管理システム</title>      
    </head>
    <body>
        <form action = "listgen.php" method = "post" >
            <br>
            <label>ユーザ名:</label>
            <input type="text" name="ユーザ名" size=5>
            <label>レポジトリ名:</label>
            <input type="text" name="レポジトリ名" size=5>
            <br>
            <label>イシュータイトル:</label>
            <input type="text" name="イシュータイトル" size=5>
            <label>ラベル:</label>
            <select name="ラベル">
                <option> バグ</option>
                <option> 機能要求</option>
            </select>
            <input type="text" name="優先順位" size=5>
            <label>担当者:</label>
            <input type="text" name="担当者" size=5>
            <label>イシューコミットID:</label>
            <input type="text" name="イシューコミットID" size=5>
            <br>
        </from>
    

    </body>
</html>