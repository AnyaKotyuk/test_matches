<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<table>
    <h1>Matches</h1>
    <tr>
        <th>Date</th>
        <th>Home team</th>
        <th>Guest team</th>
        <th>Stadium id</th>
    </tr>
    <?php foreach ($matches as $k=>$match) { ?>
    <tr>
        <td><?php echo $match->dt; ?></td>
        <td><?php echo $match->homeTeam; ?></td>
        <td><?php echo $match->awayTeam; ?></td>
        <td><?php echo $match->stadiumID; ?></td>
    </tr>
    <? } ?>
</table>
</body>
</html>

