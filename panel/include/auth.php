<?php
	


	function auth(){
		if( isset($_POST['usuario']) && isset($_POST['clave']) ){
			$sts = 0;
			$_SESSION['auth'] = $sts;
			$_SESSION['url'] = 'login';

			$usuario = md5($_POST['usuario']);
			$clave = md5($_POST['clave']);

			if( !empty($usuario) && !empty($clave) ){
				$sql = "select * from usuarios_externos where md5(user)='{$usuario}' and pass='{$clave}' and status=1 limit 1";
				$result = get_fetch_assoc($sql);
				if(array_key_exists('rows', $result)){
					foreach ($result['rows'] as $row) {
						$_SESSION['auth'] = 1;					
						$_SESSION['url'] = $row['url'];
						$_SESSION['perfil'] = [
							"nombre" => "",
							"apellido" => "",
							"dni" => "",
						];
						$sts = 1;
					}
				}
			}
		}
		return $sts;
	}