<!doctype html>
<a href="index.php">Main</a>
<a href="number_in_list.php">Number</a>
<link rel="stylesheet" href="style.css">

<div class="container message">
    <form action="">
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        include 'DB.php';
        $db = new DB('localhost', 'root', 'root', 'mysql_db');
        $db->fetchAll('messages'); //saņem visas vērtības un ieraksta masīvā, neko neatgriež

        if (array_key_exists('update', $_GET)) { //pārbaude, priekš update, ja ir tad pievieno tos echo
            $id = $_GET['update'];
            $message = $db->find($id); //dabū vērtību pēc id
            if ($message !== []) {
                echo "<h3><a href='/'>&lt;-</a> Atjauninam ierakstu ar id $id</h3>";    //virsrakstam vēl pielikta klāt linka bultiņa, lai varētu atgrieties uz sākotnējo failu
                echo "<input type='hidden' name='update-id' value='$id'>";  //šis ir neredzams, bet to vajag, jo lai update uztaisītu, vajag visas 3 vērtības
            }
        } else {
            $message = [];
        }


        ?>
        <label for="email">Epasts</label>
        <input id="email" type="email" name="email" value="<?= text(@$message['email']); ?>">

        <label for="phone">Tālrunis</label>
        <input id="phone" type="text" name="phone" value="<?= text(@$message['phone']); ?>">

        <label for="apraksts">Apraksts</label>
        <textarea id="apraksts" type="text" name="apraksts" value="<?= text(@$message['apraksts']); ?>"></textarea>

        <button type="submit">submit</button>
    </form>

    <?php
    if (
        array_key_exists('email', $_GET) &&
        array_key_exists('phone', $_GET) &&
        array_key_exists('apraksts', $_GET) &&
        is_string($_GET['email']) &&
        is_string($_GET['phone']) &&
        is_string($_GET['apraksts'])
    ) {
        if (
            array_key_exists('update-id', $_GET) &&
            is_numeric($_GET['update-id'])
        ) {
            $db->update(    //updeito ierakstu, kas jau ir datubāzē, bet to taisa tikai ja adrešu joslā ir update-id
                'messages',
                [
                    'id' => $_GET['update-id'],
                    'email' => $_GET['email'],
                    'phone' => $_GET['phone'],
                    'description' => $_GET['apraksts']
                ]
            );
        } else {
            $db->add(
                'messages',
                [
                    'email' => $_GET['email'],
                    'phone' => $_GET['phone'],
                    'description' => $_GET['apraksts']
                ]
            );
        }
    }

    if (array_key_exists('delete', $_GET)) {
        $id = (int) $_GET['delete'];
        $db->delete('messages', $id);
    }

    foreach ($db->getAll() as $row) {
        echo "<p>";
        echo "<b>" . $row['id'] . "</b>";
        echo " E-pasts: " . text($row['email']);
        echo " Telefons: " . text($row['phone']);
        echo " Apraksts: " . text($row['description']);
        echo "<a href='?delete=" . $row['id'] . "'> delete</a>";
        echo " <a href='?update=" . $row['id'] . "'> update</a>";
        echo "</p>";
    }

    function text($string)
    {
        return htmlentities($string);
    }

    ?>
</div>