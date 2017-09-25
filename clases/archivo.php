<?php
class Archivo{

	public static function Subir()
	{
		$retorno["Exito"] = TRUE;
		
//---------INDICO CUAL SERA EL DESTINO DEL ARCHIVO SUBIDO--------------------------------------------
		$destino = "archivos/" . $_FILES["archivo"]["name"];
		$destinoFinal = time().".jpg";
		$tipoArchivo = pathinfo($destino, PATHINFO_EXTENSION);
		$uploadOk=TRUE;
//-----------------------------------------------------------------------------------------------------------------


//-----------------VERIFICO EL TAMAÑO MAXIMO QUE PERMITO SUBIR-------------------------------------------------------
		if ($_FILES["archivo"]["size"] > 5000000) 
		{
			echo "ERROR - El archivo es demasiado grande.";
			$uploadOk = FALSE;
		}
//-----------------------------------------------------------------------------------------------------------------

//----------OBTIENE EL TAMAÑO DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA IMAGEN, RETORNA FALSE--------------------------
		$esImagen = getimagesize($_FILES["archivo"]["tmp_name"]);

		if($esImagen === FALSE) 
			{//NO ES UNA IMAGEN
				$retorno["Exito"] = FALSE;
				$retorno["Mensaje"] = "S&oacute;lo son permitidas IMAGENES.";
				return $retorno;
			}
			else 
				{// ES UNA IMAGEN
				//SOLO PERMITO CIERTAS EXTENSIONES
				if($tipoArchivo != "jpg" && $tipoArchivo != "jpeg" && $tipoArchivo != "gif"&& $tipoArchivo != "png") 
					{
						echo "Solo son permitidas imagenes con extension JPG, JPEG, PNG o GIF.";
						$uploadOk = FALSE;
					}
			}
//------------------VERIFICO SI HUBO ALGUN ERROR, CHEQUEANDO $uploadOk----------------------------------------------
		if ($uploadOk === FALSE) 
			{
				echo "<br/>NO SE PUDO SUBIR EL ARCHIVO.";
			} 
		else 
			{

//-------------------MUEVO EL ARCHIVO DEL TEMPORAL AL DESTINO FINAL------------------------------------------------
			if (move_uploaded_file($_FILES["archivo"]["tmp_name"], "archivos/".$destinoFinal)) 
				{
					$retorno["Exito"]=TRUE;
					echo "<br/>El archivo ". basename( $_FILES["archivo"]["name"]). " ha sido subido exitosamente.";
					$retorno["PathTemporal"]=$destinoFinal;
				}
		else 
			{
				echo "<br/>Lamentablemente ocurri&oacute; un error y no se pudo subir el archivo.";
			}
			}
			return $retorno;
			}
//-----------------------------------------------------------------------------------------------------------------

//-------------- FUNCION ESTATICA BORRAR ---------------------------------------------------------------------
	public static function Borrar($path)
	{
		return unlink($path);
	}
//-----------------------------------------------------------------------------------------------------

//----------------- FUNCION ESTATICA MOVER -----------------------------------------------------------------
	public static function Mover($pathOrigen, $pathDestino)
	{
		return copy($pathOrigen, $pathDestino);
	}
//---------------------------------------------------------------------------------------------------------------
}
?>