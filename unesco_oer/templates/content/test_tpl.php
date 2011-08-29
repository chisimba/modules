

<body>

<h1>My First Heading</h1>

<p>My first paragraph .</p>
<?php
echo "deals";
echo $this->objPagination->getPaginationString($page = 1,$totalitems=10, $limit = 15, $adjacents = 3, $targetpage = "test_tpl.php", $pagestring = "?page=");
    ?>

</body>
</html>



