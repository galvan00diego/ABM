<?php
class Producto
{
//--------------------------------------------------------------------------------//
//--ATRIBUTOS
	private $codBarra;
 	private $nombre;
	private $pathFoto;
	
//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--GETTERS Y SETTERS
	public function GetCodBarra()
	{
		return $this->codBarra;
	}
	public function GetNombre()
	{
		return $this->nombre;
	}
	public function GetPathFoto()
	{
		return $this->pathFoto;
	}
	public function GetId()
	{
		return $this->id;
	}
	public function SetId($valorId)
	{
		$this->id=$valorId;
	}

	public function SetCodBarra($valor)
	{
		$this->codBarra = $valor;
	}
	public function SetNombre($valor)
	{
		$this->nombre = $valor;
	}
	public function SetPathFoto($valor)
	{
		$this->pathFoto = $valor;
	}

//--------------------------------------------------------------------------------//
//--CONSTRUCTOR
	public function __construct($codBarra=NULL, $nombre=NULL, $pathFoto=NULL)
	{
		if($codBarra !== NULL && $nombre !== NULL){
			$this->codBarra = $codBarra;
			$this->nombre = $nombre;
			$this->pathFoto = $pathFoto;
		}
	}

//--------------------------------------------------------------------------------//
//--TOSTRING	
  	public function ToString()
	{
	  	return $this->codBarra." - ".$this->nombre." - ".$this->pathFoto."\r\n";
	}
//--------------------------------------------------------------------------------//

//--------------------------------------------------------------------------------//
//--METODOS DE CLASE
	public static function Guardar($obj)
	{
		$resultado = FALSE;
		
		//ABRO EL ARCHIVO
		$ar = fopen("archivos/productos.txt", "a");
		
		//ESCRIBO EN EL ARCHIVO
		$cant = fwrite($ar, $obj->ToString());
		
		if($cant > 0)
		{
			$resultado = TRUE;			
		}
		//CIERRO EL ARCHIVO
		fclose($ar);
		
		return $resultado;
	}

//-------------------------------------------------------------------------------------------------	
	public static function TraerTodosLosProductos()
	{

		$ListaDeProductosLeidos = array();
//----------TRAER PRODUCTOS DESDE BASE DE DATOS--------------------------------------
		// $con=@mysql_connect("localhost","root","");
		// $res=mysql_db_query("productos","SELECT * FROM producto");
		// while($row = mysql_fetch_object($res)){
            
        //     array_push($ListaDeProductosLeidos,$row);
		// }
		// mysql_close($con);
//-----------------------------------------------------------------------------------

//---------------------LEO TODOS LOS PRODUCTOS DEL ARCHIVO----------------------------------------------
		$archivo=fopen("archivos/productos.txt", "r");
		
		while(!feof($archivo))
		{
			$archAux = fgets($archivo);
			$productos = explode(" - ", $archAux);
			//http://www.w3schools.com/php/func_string_explode.asp
			$productos[0] = trim($productos[0]);
			if($productos[0] != ""){
				$ListaDeProductosLeidos[] = new Producto($productos[0], $productos[1],$productos[2]);
			}
		}
		fclose($archivo);
		
		return $ListaDeProductosLeidos;
		
	}

//-------------------MODIFICAR-----------------------------------------------------------------------
	public static function Modificar($obj)
	{
		$resultado = TRUE;
//------------MODIFICAMOS LA TABLA DE LA BASE DE DATOS----------------------------------------------
		// $con=@mysql_connect("localhost","root","");
		// $query = "UPDATE producto SET codigo_barra=$obj->codigo_barra, nombre=$obj->nombre, path_foto=$obj->path_foto
		// WHERE id =$obj->id";
		// $res=mysql_db_query("producto",$query);
		
//--------------------OBTENGO TODOS LOS PRODUCTOS--------------------------------------------------------
		$TodosProd = Producto::TraerTodosLosProductos();
		$ListaModificada = array();
		$imgBorrada = NULL;
//--------------RECORRO Y BUSCO LA IMAGEN ANTERIOR------------------------------------------------------
//-------------------REEMPLAZO POR EL OBJ. MODIFICADO---------------------------------------------------
		for($i=0; $i<count($TodosProd); $i++)
		{
			if($TodosProd[$i]->codBarra == $obj->codBarra)
			{
				$imgBorrada = trim("archivos/".$TodosProd[$i]->pathFoto);
				continue;
			}
			$ListaModificada[$i] = $TodosProd[$i];
		}

		array_push($ListaModificada, $obj);
//----------------------BORRO LA IMAGEN ANTERIOR--------------------------------------------------------
		unlink($imgBorrada);
//------------------------ABRO EL ARCHIVO------------------------------------------------------------
		$ar = fopen("archivos/productos.txt", "w");

//-----------------------ESCRIBO EN EL ARCHIVO---------------------------------------------------------
		foreach($ListaModificada as $item)
		{
			$lineas = fwrite($ar, $item->ToString());
			
			if($lineas < 1)
			{
				$resultado = FALSE;
				break;
			}
		}

//CIERRO EL ARCHIVO
fclose($ar);
		return $resultado;
	}
//--------------------------------------------------------------------------------------------------------

//----------------------ELIMINAR PRODUCTO---------------------------------------------------------------
	public static function Eliminar($codBarra)
	{
		$resultado = TRUE;
		
		//OBTENGO TODOS LOS PRODUCTOS
		$TodosProd=Producto::TraerTodosLosProductos();
		$imgBorrada=NULL;
		$ListaModificada=array();
		//RECORRO Y BUSCO LA IMAGEN ANTERIOR. 
		for($i=0;$i<count($TodosProd);$i++)
		{
			if($TodosProd[$i]->codBarra==$codBarra)
			{
				//SE QUITAN LOS ESPACIOS EN BLANCO
				$imgBorrada=trim("archivos/".$TodosProd[$i]->pathFoto);
				//unset($TodosProd[$i]);
				continue;
			}
			$ListaModificada[$i]=$TodosProd[$i];
		}
		//BORRO LA IMAGEN ANTERIOR
		unlink($imgBorrada);
		//ABRO EL ARCHIVO
		$archivo=fopen("archivos/productos.txt", "w");
		foreach($ListaModificada as $elemento)
		{
			//ESCRIBO EN EL ARCHIVO
			$lineas=fwrite($archivo,$elemento->ToString());
			if($lineas<1)
			{
				$resultado=FALSE;
				break;
			}
		}
		//CIERRO EL ARCHIVO
		fclose($archivo);

		return $resultado;
	}
//--------------------------------------------------------------------------------//
}