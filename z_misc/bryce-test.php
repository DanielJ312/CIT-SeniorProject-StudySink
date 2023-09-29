<?php 
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    insert($_POST);
}
$list = retrieve();
?>

<html>
<h1>AWS RDS Database Insert Testing</h2>
<div>
    <div>
        <h3>Insert Data</h3>
        <p>This form will insert the submitted value to a database.</p> 
        <p>It assumes there is a databased called <b>test</b> and a table called <b>table_t</b> with attributes <b>id</b> and <b>content</b>.</p>
        <form method="post">
            <p>Content: <input type="text" name="content"></p>
            <input type="submit" formnovalidate value="Submit">
        </form>
    </div>
    <div>
        <h3>All current values will be listed below</h3>
        <table>
            <tr>
                <th>id</th><th>content</th>
            </tr>
            <?php for ($i=0; $i < sizeof($list); $i++): ?>
            <tr>
                <td><?=$list[$i]->id?></td>
                <td><?=$list[$i]->content?></td>
            </tr>
            <?php endfor; ?>
        </table>
    </div>
</div>
</html>

<?php 
function insert($data) {
    run_database("CREATE TABLE IF NOT EXISTS table_t (id INT PRIMARY KEY, content VARCHAR(255));");
    $values['id'] = rand(10000, 99999);
    $values['content'] = $data['content'];
    $query = "INSERT INTO table_t (id, content) VALUES (:id, :content)";
    run_database($query, $values);
}

function retrieve() {
    $query = "SELECT * FROM table_t";
    return run_database($query);
}

function run_database($query, $values = array()) {;
    $dbhost = "localhost";
    $dbport = "3306";
    $dbname = "test";
    $dbusername = "root";
    $dbpassword = "";
    
    $server = "mysql:host=$dbhost;port=$dbport;dbname=$dbname;";
    $connection = new PDO($server, $dbusername, $dbpassword);

    if (!$connection)  {
        return false;
    }

    $statement = $connection->prepare($query);
    $check = $statement->execute($values);

    if ($check) {
        $data = $statement->fetchAll((PDO::FETCH_OBJ));
        if (count($data) > 0) {
            return $data;
        }
    }

    return false;
}
    
?>