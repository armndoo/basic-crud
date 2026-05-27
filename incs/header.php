
<?php if (!defined('INCLUDED')) {
  http_response_code(404);
  include '../status-pages/404.html';
  exit();
}
?>

<!doctype html>


<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $page_title; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


    <link rel="stylesheet" href=<?php echo "$page_css"; ?> />

</head>
<body>
    <div class="container">

        <?php
        echo "<div class='page-header'>
                <h1>{$page_title}</h1>
            </div>";
        ?>
