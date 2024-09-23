<?php
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect('localhost', 'shaked', 'noneed', 'demo');
    if(!$conn)
        echo 'Couldn\'t connect: ' . mysqli_connect_error();

    if(isset($_POST['submit']))
    {
        $sql = "INSERT INTO list(title) VALUES('" . trim(htmlspecialchars($_POST['title'])) . "');";
        mysqli_query($conn, $sql);
        $id = mysqli_insert_id($conn);
        
        $entries = explode(',', $_POST['entries']);
        foreach($entries as $entry)
        {
            $sql = "INSERT INTO entry(entry_text, list_id) VALUES('" . trim(htmlspecialchars($entry)) . "', " . $id . ");";
            mysqli_query($conn, $sql);
        }
    }

    $sql = 'SELECT * FROM list';
    $result = mysqli_query($conn, $sql);
    $lists = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $sql = 'SELECT * FROM entry';
    $result = mysqli_query($conn, $sql);
    $entries = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $entries_by_id = array();
    foreach($entries as $entry)
    {
        if(!isset($entries_by_id[$entry['list_id']]))
            $entries_by_id[$entry['list_id']] = array();
        array_push($entries_by_id[$entry['list_id']], $entry);
    }

    mysqli_free_result($result);

    mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>hello</title>
</head>
<body>
    <form action="" method="POST">
        <input name="title" type="text">
        <input name="entries" type="text">
        <input name="submit" type="submit">
    </form>
    <ul>
        <?php 
            foreach($lists as $list):
        ?>
        <li>
            <h3><?php echo $list['title'] ?></h3>
            <?php 
                $id = $list['id'];
                $list_entries = $entries_by_id[$id];
                echo '<ul>';
                foreach($list_entries as $entry)
                    echo '<li>' . $entry['entry_text'] . '</li>';
                echo '</ul>';
            ?>
        </li>

        <?php endforeach; ?>
    </ul>
</body>
</html>
