<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>R6 Randomizer</title>
    <style>
        body{
            background: #252526;
        }
        p{
            font-size: 24px;
            font-family: "Comic Sans MS";
            color: white;
        }

    </style>

</head>
<body>

<?php
$details = isset($_GET["details"]) ? $_GET["details"] : null;
?>

<form action="index.php" method="get">
    <p>
        <input type="submit" name="attacker" value="Attacker">
        <input type="submit" name="defender" value="Defender">
        <input type="submit" name="quick" value="Quick Match">
        <input type="submit" name="ranked" value="Ranked">
        <input type="checkbox" name="details" <?php echo (isset($details) ? "checked" : "") ?>>

    </p>
</form>
<?php

//database
// FIELDS: Name, Role,
//         Primary_1, Primary_2, Primary_3,
//         Secondary_1, Secondary_2, Secondary_3,
//         Gadget_1, Gadget_2, Gadget_3
//
$servername = /* ENTER SERVER NAME*/ ; 
$username = /* ENTER USERNAME */ ;
$password = /* ENTER PASSWORD*/ ;
$dbname = /* ENTER DATABASE NAME */ ;
$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die ("Connection failed");
}

$attacker = isset($_GET["attacker"]) ? $_GET["attacker"] : null;
$defender = isset($_GET["defender"]) ? $_GET["defender"] : null;
$quick = isset($_GET["quick"]) ? $_GET["quick"] : null;
$ranked = isset($_GET["ranked"]) ? $_GET["ranked"] : null;
$a_ban_1 = isset($_GET["a_ban_1"]) ? $_GET["a_ban_1"] : null;
$a_ban_2 = isset($_GET["a_ban_2"]) ? $_GET["a_ban_2"] : null;
$d_ban_1 = isset($_GET["d_ban_1"]) ? $_GET["d_ban_1"] : null;
$d_ban_2 = isset($_GET["d_ban_2"]) ? $_GET["d_ban_2"] : null;

//get attackers
$sql = "SELECT * FROM `Operators` WHERE (Role='Attacker');";
$result = $conn->query($sql);
$attackers = array();
if($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attackers[] = $row;
    }
} else { die("No Attackers"); }

//get defenders
$sql = "SELECT * FROM `Operators` WHERE (Role='Defender');";
$result = $conn->query($sql);
$defenders = array();
if($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $defenders[] = $row;
    }
} else { die ("No Defenders"); }


if(isset($attacker)) {
    get_random_operator($attackers, $details);
}
else if(isset($defender)) {
    get_random_operator($defenders, $details);
}
elseif(isset($quick)) {
    get_random_operator($attackers, $details);
    get_random_operator($attackers, $details);
    get_random_operator($attackers, $details);
    get_random_operator($defenders, $details);
    get_random_operator($defenders, $details);
    get_random_operator($defenders, $details);
}
elseif(isset($ranked)) {
    ?>
    <form action="index.php" method="get">
        <p><input type="text" name="a_ban_1" placeholder="attacker ban 1"></p>
        <p><input type="text" name="a_ban_2" placeholder="attacker ban 2"></p>
        <p><input type="text" name="d_ban_1" placeholder="defender ban 1"></p>
        <p><input type="text" name="d_ban_2" placeholder="defender ban 2"></p>
        <p><input type="submit"></p>
        <p><input type="checkbox" name="details" <?php echo (isset($details) ? "checked" : "")?>></p>

    </form>
    <?php
}
elseif(isset($a_ban_1) && isset($a_ban_2) && isset($d_ban_1) && isset($d_ban_2)) {
    ranked_random_operator($attackers, $details, $a_ban_1, $a_ban_2);
    ranked_random_operator($defenders, $details, $d_ban_1, $d_ban_2);
}


function get_random_operator($operators, $details)
{
    $random_entry = rand(0, count($operators) - 1);

    echo "<p>";
    echo "Operator: " . $operators[$random_entry]["Name"];

    if (isset($details)) {
        echo "<br>";

        if ($operators[$random_entry]["Primary_3"] !== "") {
            echo "Primary: " . $operators[$random_entry]["Primary_" . rand(1, 3)] . "<br>";
        } elseif ($operators[$random_entry]["Primary_2"] !== "") {
            echo "Primary: " . $operators[$random_entry]["Primary_" . rand(1, 2)] . "<br>";
        } else {
            echo "Primary: " . $operators[$random_entry]["Primary_" . "1"] . "<br>";
        }

        if ($operators[$random_entry]["Secondary_3"] !== "") {
            echo "Secondary: " . $operators[$random_entry]["Secondary_" . rand(1, 3)] . "<br>";
        } elseif ($operators[$random_entry]["Secondary_2"] !== "") {
            echo "Secondary: " . $operators[$random_entry]["Secondary_" . rand(1, 2)] . "<br>";
        } else {
            echo "Secondary: " . $operators[$random_entry]["Secondary_" . "1"] . "<br>";
        }

        if ($operators[$random_entry]["Gadget_3"] !== "") {
            echo "Gadget: " . $operators[$random_entry]["Gadget_" . rand(1, 3)] . "<br>";
        } elseif ($operators[$random_entry]["Gadget_2"] !== "") {
            echo "Gadget: " . $operators[$random_entry]["Gadget_" . rand(1, 2)] . "<br>";
        } else {
            echo "Gadget: " . $operators[$random_entry]["Gadget_" . "1"] . "<br>";
        }
    }


    echo "</p>";

}

function ranked_random_operator($operators, $details, $ban_1, $ban_2)
{
    for ($i = 0; $i < count($operators); $i++) {
        if ($operators[$i]["Name"] == $ban_1 || $operators[$i]["Name"] == $ban_2) {
            array_splice($operators, $i, 1);
        }
    }
    get_random_operator($operators, $details);
    get_random_operator($operators, $details);
    get_random_operator($operators, $details);
}
?>
</body>
</html>
