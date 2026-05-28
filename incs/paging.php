<link rel="stylesheet" href="../css/paging.css"/>


<?php
if (!defined('INCLUDED')) {
  http_response_code(404);
  include '404.html';
  exit();
}
echo "<nav aria-label='Page navigation example'>";
echo "<ul class='pagination'>";

if($page>1){
    echo "<li class='page-item'><a class='page-link' href='{$page_url}page=1' title='Vai alla prima pagina.'>";
        echo "Inizio";
    echo "</a></li>";
}

$total_pages = ceil($total_rows / $records_per_page);

$range = 2;

$initial_num = $page - $range;
$condition_limit_num = ($page + $range)  + 1;

for ($x=$initial_num; $x<$condition_limit_num; $x++) {

    if (($x > 0) && ($x <= $total_pages)) {

        if ($x == $page) {
            echo "<li class='page-item active'><a class='page-link' href=\"#\">$x <span class=\"sr-only\"></span></a></li>";
        }

        else {
            echo "<li class='page-item'><a class='page-link' href='{$page_url}page=$x'>$x</a></li>";
        }
    }
}

if($page<$total_pages){
    echo "<li class='page-item'><a class='page-link' href='" .$page_url. "page={$total_pages}'>";
        echo "Fine";
    echo "</a></li>";
}

echo "</ul>";
echo "</nav>";
?>
