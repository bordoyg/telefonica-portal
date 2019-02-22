<?php

require_once(APPPATH . 'widgets/custom/library/dispatcher.php');
require_once(APPPATH . 'widgets/custom/library/basicInit.php');

// Customer Portal no permite usar variables de session custom para esta version del producto, por eso se usan cookies
//ni tampoco crear paginas que no tengan header y body
?>

<head>
</head>
<body>
    <rn:widget path="/custom/WorkOrders/PopupCancelarOrden"/> 
    <rn:widget path="/custom/WorkOrders/PopupContactarCallCenter"/> 
</body>

