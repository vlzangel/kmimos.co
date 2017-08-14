<?php
	

	$conn_my = new mysqli("localhost", "root", "", 'kmimos_mg_est');

	if (!$conn_my) {
	  	echo "No conectado.\n";
	  	exit;
	}

	$result = $conn_my->query("select 
	s.id as estado_id,
    s.name as estado_descripcion,
    l.id as municipio_id,
    l.name as municipio_descripcion
from states as s 
	inner join locations as l on s.id = l.state_id");

	while ($row = $result->fetch_assoc()){
		echo $row['estado_id'].";".$row['estado_descripcion'].";".$row['municipio_id'].";".$row['municipio_descripcion'];
		echo '<br>';
	}