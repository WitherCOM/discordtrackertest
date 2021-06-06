<!doctype html>
<?php
$names = [
    '/fesz' => 'A feszültségkülönbség 1 dzsúl',
    '/noboti' => 'NoBotiIzAllowed',
    '/cave' => 'The Seagull Cave'
];
date_default_timezone_set("Europe/Budapest");
$dbhost = 'wither.ddns.net';
$dbport = 33060;
$dbuser = 'python_log';
$dbpass = '2000';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, 'python_log', $dbport);
mysqli_set_charset($conn, 'utf8mb4');

?>
<html lang="en">

<head>
    <?php if (array_key_exists($_SERVER['REQUEST_URI'], $names)) : ?>
        <?php if ($_SERVER['REQUEST_URI'] == '/fesz') : ?>
            <title>Our Discord Data</title>
        <?php endif ?>
        <?php if ($_SERVER['REQUEST_URI'] == '/noboti') : ?>
            <title>Our Discord Data</title>
            <link rel="icon" href="noboti.gif" type="image/gif" />
        <?php endif ?>
	<?php if ($_SERVER['REQUEST_URI'] == '/cave') : ?>
            <title>Our Discord Data</title>
        <?php endif ?>

    <?php else : ?>
        <title>Nothing to see here</title>
        <link rel="icon" href="karika.png" type="image/png" />
    <?php endif ?>


    <meta charset="utf-32">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="site.css">
</head>

<body class="bg-dark text-white">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <div class="container m-auto">
        <?php if (array_key_exists($_SERVER['REQUEST_URI'], $names)) : ?>
            <?php
            $guild = $names[$_SERVER['REQUEST_URI']];
            $color = "";
            if ($guild == 'NoBotiIzAllowed') {
                $color = 'bg-danger';
            } else if ($guild == 'A feszültségkülönbség 1 dzsúl') {
                $color = 'bg-success';
            }
	      else{
		$color = 'bg-warning';
	    }

            $query1 = "select user,nickname,isjoin,time from python_log.logs where guild='$guild' and date='" . date("Y-m-d") . "' order BY time desc";
            $query1on = "SELECT t1.* 
      FROM python_log.logs t1
      INNER JOIN
        (SELECT
            guild
           , discordID
           , MAX(ID) AS ID
         FROM python_log.logs
         GROUP BY guild, discordID
        ) AS t2
      ON t1.guild = t2.guild
      AND t1.discordID = t2.discordID
      AND t1.ID = t2.ID
      WHERE t1.guild = '$guild' order by isjoin desc";
            $result1 = mysqli_query($conn, $query1);
            $result1on = mysqli_query($conn, $query1on);
            ?>
            <?php if (mysqli_num_rows($result1) >= 0) : ?>

                <div class="row justify-content-center">
                    <div class="col"></div>

                    <div class=<?= '"col border border-secondary rounded my-3 ' . $color . '"' ?>>
                        <h4 class="text-center"><?= $guild ?></h4>
                    </div>
                    <div class="col"></div>
                </div>
                <div class="row">
                    <div class="col-10 col-sm-10 col-md-8">
                        <table class="table table-striped table-bordered table-dark">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Operation</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Nickname</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                while ($row = mysqli_fetch_array($result1)) : ?>
                                    <tr>
                                        <th scope="row"><?= $i ?></th>
                                        <td><?= mb_convert_encoding($row['user'], 'utf-8', "auto") ?></td>
                                        <td><?php if ($row['isjoin'] == 1) {
                                                echo 'Joined';
                                            } else {
                                                echo 'Left';
                                            } ?></td>
                                        <td><?= mb_convert_encoding($row['time'], "UTF-8", "auto") ?></td>
                                        <td><?= mb_convert_encoding($row['nickname'], "UTF-8", "auto") ?></td>

                                    </tr>
                                <?php $i = $i + 1;
                                endwhile ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="border border-secondary rounded col-4 col-sm-4 col-md-4">
                        <h3 class="text-center">People</h3>
                        <table class="table w-100 table-striped table-bordered table-dark">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">State</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_array($result1on)) : ?>
                                    <tr <?php if ($row['isjoin'] == 1) {
                                            echo 'class="bg-success"';
                                        } else {
                                            echo 'class="bg-danger"';
                                        } ?>>
                                        <th scope="row"> <?= mb_convert_encoding($row['user'], "UTF-8", "auto") ?></th>
                                        <td><?php if ($row['isjoin'] == 1) {
                                                echo 'Joined';
                                            } else {
                                                echo 'Not Joined';
                                            } ?></td>

                                    </tr>
                                <?php endwhile ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row justify-content-center">                        
                    <div class="col-9 col-lg-6 border border-secondary rounded my-3">
                        <h4 class="text-center"><?="Yesterday's chart " . date("Y-m-d",strtotime("-1 days")) ?></h4>
                        <img class="mw-100 mb-3" src=<?= '"' . $names[$_SERVER['REQUEST_URI']] . '.png"' ?> />
                    </div>
                    <div class="col-9 col-lg-6 border border-secondary rounded my-3 ">
                        <h4 class="text-center"><?="All chart "?></h4>
                        <img class="mw-100 mb-3" src=<?= '"' . $names[$_SERVER['REQUEST_URI']] . '_all.png"' ?> />
                    </div>
                </div>
            <?php mysqli_free_result($result1);
                mysqli_free_result($result1on);
            endif ?>
        <?php else : ?>
            <h2>
                No!!!!
            </h2>
        <?php endif ?>
    </div>
</body>

</html>
<?php


mysqli_close($conn);
?>