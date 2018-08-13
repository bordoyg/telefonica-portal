<?php
include('dispatcher.php');

if(!isset($GLOBALS['dispatcher'])){
    $GLOBALS['dispatcher']=new Dispatcher();
}
$dispatcher = $GLOBALS['dispatcher'];



$htmlFile=$dispatcher->resolveAction();
isset($htmlFile) ? include $htmlFile :'';
?>
<?php 
    // Customer Portal no permite usar variables de session custom para esta version del producto, por eso se usan cookies
    //ni tampoco crear paginas que no tengan header y body
?>
<head>
</head>
<body>
</body>

