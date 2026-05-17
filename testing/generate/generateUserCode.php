<?php
function generateUserCode()
{
    return (bin2hex(random_bytes(16)));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div><?= htmlspecialchars(generateUserCode()) ?></div>
    <form method="post">
        <button type="submit">Generate</button>
    </form>
</body>

</html>