<?php
$cards = array(
    array("Front 1", "Back 1"),
    array("Front 2", "Back 2"),
    array("Front 3", "Back 3"),
    array("Front 4", "Back 4"),
);

$maxRows = count($cards);
$maxCols = 0;

foreach ($cards as $row) {
    $maxCols = max($maxCols, count($row));
}

for ($row = 0; $row < $maxRows; $row++) {
    echo "<p><b>Row number $row</b></p>";
    echo "<ul>";
    for ($col = 0; $col < $maxCols; $col++) {
        if (isset($cards[$row][$col])) {
            echo "<li>".$cards[$row][$col]."</li>";
        } else {
            echo "<li>Empty</li>";
        }
    }
    echo "</ul>";
}

?>