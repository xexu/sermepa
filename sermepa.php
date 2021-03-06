<?php

class Sermepa{
    private $_setEntorno;
    private $_setImporte;
    private $_setMoneda;
    private $_setPedido;
    private $_setProductoDescripcion;
    private $_setTitular;
    private $_setFuc;
    private $_setTerminal;
    private $_setTransactionType;
    private $_setUrlNotificacion;
    private $_setClave;
    private $_setUrlOk;
    private $_setUrlKo;
    private $_setFirma;
    private $_setNombreComercio;
    private $_setIdioma;
    private $_setMethods;
    private $_setNameForm;
    private $_setSubmit;

    public function __construct(){
        
        $this->_setEntorno='https://sis-t.redsys.es:25443/sis/realizarPago';
        $this->_setMoneda ='978';
        $this->_setTerminal =1;
        $this->_setTransactionType=0;
        $this->_setIdioma = '001';
        $this->_setMethods='T';
        $this->_setNameForm = 'servired_form';
        $this->_setSubmit = '';
    }
    
    #-#####################
    # desc: Asignamos el idioma por defecto 001 = Español
    # param: codeidioma: codigo de idioma [Castellano-001, Inglés-002, Catalán-003, Francés-004, Alemán-005,
    #                   Holandés-006, Italiano-007, Sueco-008, Portugués-009, Valenciano-010, Polaco-011, Gallego-012 y Euskera-013.]
    public function set_idioma($codeidioma){
        $this->_setIdioma = $codeidioma;
    }#set_idioma()
    
    #-######################
    #desc: Asignamos que tipo de entorno vamos a usar si Pruebas o Real (por defecto esta en pruebas)
    #param: entorno (pruebas, real)
    public function set_entorno($entorno='pruebas'){
        if(trim($entorno) == 'real'){
            //real
            $this->_setEntorno='https://sis.sermepa.es/sis/realizarPago';
        }
        else{
            //pruebas
            $this->_setEntorno ='https://sis-t.redsys.es:25443/sis/realizarPago';
        }            
    }#-#set_entorno()

    #-######################
    #desc: Retornamos la URL que va en el form para los pagos ya sea en pruebas o real
    #return: URL sermepa (real o pruebas)    
    public function geturlentorno(){
        return $this->_setEntorno;    
    }#-#geturlentorno()
    
    #-######################
    #desc: Asignamos el importe de la compra
    #param: importe: total de la compra a pagar
    #return: Retornamos el importe ya modificado
    public function importe($importe=0){
        $importe = $this->parseFloat($importe);
        
        // sermepa nos dice Para Euros las dos últimas posiciones se consideran decimales.
        $importe = intval($importe*100);
        $this->_setImporte=$importe;
        return $importe;
    }#-#importe()
    
    
    #-##############################
    #desc: Asignamos el tipo de moneda
    #param: moneda: 978 para Euros, 840 para Dólares, 826 para libras esterlinas y 392 para Yenes.
    public function moneda($moneda='978'){
        if($moneda == '978' || $moneda =='840' || $moneda =='826' || $moneda =='392' ){
            $this->_setMoneda = $moneda;
        }
        else{
            throw new Exception('Moneda no valida');
        }
        
    }#-#moneda()
    
    #-##############################
    #desc: Asignamos el número de pedido a nuestra compra (Los 4 primeros dígitos deben ser numéricos)
    #param: pedido: Numero de pedido alfanumérico
    public function pedido($pedido=''){
        if(strlen(trim($pedido))> 0){
            $this->_setPedido = $pedido;
        }
        else{
            throw new Exception('Falta agregar el número de pedido');
        }
        
    }#-#pedido()
    
    #-##############################
    #desc: Asigmanos la descripción del producto (Obligatorio)
    #param: producto: Descripción del producto
    public function producto_descripcion($producto=''){
        if(strlen(trim($producto)) > 0){
            //asignamos el producto
            $this->_setProductoDescripcion = $producto;
        }
        else{
            throw new Exception('Falta agregar la descripción del producto');
        }
    }#-#producto_descripcion()
    
    #-###############################
    #desc: Asigmanos el nombre del usuario que realiza la compra (Obligatorio)
    #param: titular: Nombre del usuario (por ejemplo Juan Perez)
    public function titular($titular=''){
        if(strlen(trim($titular)) > 0){
            $this->_setTitular = $titular;
        }
        else{
            throw new Exception('Falta agregar el titular que realiza la compra');
        }
    }#-#titular()
    
    #-###########################
    #desc: Asignamos el código FUC del comercio (Obligatorio)
    #param: fuc: Código fuc que nos envía sermepa
    public function codigofuc($fuc=''){
        if(strlen(trim($fuc)) > 0){
            $this->_setFuc = $fuc;
        }
        else{
            throw new Exception('Falta agregar el código FUC');
        }  
    }#-#codigofuc
    
    #-###########################
    #desc: Asignar el tipo de terminal por defecto 1 en Sadabell (Obligatorio)
    #param: terminal: Número de terminal que le asignará su banco.
    public function terminal($terminal=1){
        if(intval($terminal) !=0){
            $this->_setTerminal = $terminal;
        }
        else{
            throw new Exception('El terminal no es valido');
        }
    }#-#terminal()
    
    #-##############################
    #desc: Asignar el tipo de transacción por defecto 0 que es autorización (Obligatorio)
    #param: transactiontype: tipo de transacción
    public function transaction_type($transactiontype=0){
        if(strlen(trim($transactiontype)) > 0){
            $this->_setTransactionType= $transactiontype;
        }
        else{
            throw new Exception('Falta agregar el tipo de transacción');
        }
    }#-#transaction_type()
    
    #-################################
    # desc: Nombre del comercio que será reflejado en el ticket del comercio (Opcional)
    # param: nombrecomercio = Nombre del comercio
    public function nombre_comercio($nombrecomercio=''){
        $nombrecomercio = trim($nombrecomercio);
        $this->_setNombreComercio = $nombrecomercio;
    }#-#nombre_comercio()
    
    #-################################
    #desc: Si el comercio tiene notificación "on-line". URL del comercio que recibirá un post con los datos de la transacción (Obligatorio).
    #param: url_notificacion: Url para guardar los datos recibidos por el comercio.
    public function url_notificacion($url_notificacion=''){
        if(strlen(trim($url_notificacion)) > 0){
            $this->_setUrlNotificacion = $url_notificacion;
        }
        else{
            throw new Exception('Falta agregar url de notificacion');
        }
    }#-#url_notificacion()
    
    #-################################
    #desc: Si se envía será utilizado como URLOK ignorando el configurado en el módulo de administración en caso de tenerlo (Opcional).
    #param: url: Url donde mostrar un mensaje si se realizo correctamente el pago
    public function url_ok($url=''){
        $this->_setUrlOk = $url;
    }#-#url_ok()
    
    #-################################
    #desc: Si se envía será utilizado como URLKO ignorando el configurado en el módulo de administración en caso de tenerlo (Opcional).
    #param: url: Url donde mostrar un mensaje si se produjo un problema al realizar el pago
    public function url_ko($url=''){
        $this->_setUrlKo = $url;
    }#-#url_ko()
    
    #-###############################
    #desc: Clave proporcionada por el banco (Obligatorio)
    #param: clave: Clave asignada
    public function clave($clave=''){
        if(strlen(trim($clave)) > 0){
            $this->_setClave = $clave;
        }
        else{
            throw new Exception('Falta agregar la clave proporcionada por sermepa');
        }
    }#-#clave()

    #-################################
    # desc: Tipo de pago [T = Pago con Tarjeta, R = Pago por Transferencia, D = Domiciliacion]
    #       por defecto es T
    public function methods($metodo='T'){
        $this->_setMethods= $metodo;
    }#-#methods()
    
        
    #-################################
    #desc: Firma que se le envía a sermepa (Obligatorio)    
    public function firma(){
                
        $mensaje = $this->_setImporte . $this->_setPedido . $this->_setFuc . $this->_setMoneda . $this->_setTransactionType . $this->_setUrlNotificacion . $this->_setClave;
        if(strlen(trim($mensaje)) > 0){
            // Cálculo del SHA1		    		
    		$this->_setFirma = strtoupper(sha1($mensaje));	   
        }
        else{
            throw new Exception('Falta agregar la firma, Obligatorio');
        }
    }#-#firma()
    
    public function getFirma()
    {
        return $this->_setFirma;
    }
    /**
     * Asignar el nombre del formulario
     * @param string nombre Nombre y ID del formulario
     */

    public function set_nameform($nombre = 'servired_form')
    {
        $this->_setNameForm = $nombre;
    }

    /**
    * Generar boton submit
    * @param string nombre Nombre y ID del botón submit
    * @param string texto Texto que se mostrara en el botón
    */

    public function submit($nombre = 'submitsermepa',$texto='Enviar')
    {
        if(strlen(trim($nombre))==0)
            throw new Exception('Asigne nombre al boton submit');

        $btnsubmit = '<input type="submit" name="'.$nombre.'" id="'.$nombre.'" value="'.$texto.'" />';
        $this->_setSubmit = $btnsubmit;
    }
    
    /**
    * Ejecutar la redirección hacia el tpv
    */
    
    public function ejecutarRedireccion() {
    	echo $this->create_form();
	    echo '<script>document.forms["'.$this->_setNameForm.'"].submit();</script>';
    }


    #-##########################
    #des: Generamos el form a incluir en nuestro html
    public function create_form(){
        $formulario='
        <form action="'.$this->_setEntorno.'" method="post" id="'.$this->_setNameForm.'" name="'.$this->_setNameForm.'" >
            <input type="hidden" name="Ds_Merchant_Amount" value="'.$this->_setImporte.'" />
            <input type="hidden" name="Ds_Merchant_Currency" value="'.$this->_setMoneda.'" />
            <input type="hidden" name="Ds_Merchant_Order" value="'.$this->_setPedido.'" />
            <input type="hidden" name="Ds_Merchant_MerchantCode" value="'.$this->_setFuc.'" />
            <input type="hidden" name="Ds_Merchant_Terminal" value="'.$this->_setTerminal.'" />
            <input type="hidden" name="Ds_Merchant_TransactionType" value="'.$this->_setTransactionType.'" />
            <input type="hidden" name="Ds_Merchant_Titular" value="'.$this->_setTitular.'" />
            <input type="hidden" name="Ds_Merchant_MerchantName" value="'.$this->_setNombreComercio.'" />
            <input type="hidden" name="Ds_Merchant_MerchantURL" value="'.$this->_setUrlNotificacion.'" />
            <input type="hidden" name="Ds_Merchant_ProductDescription" value="'.$this->_setProductoDescripcion.'" />
            <input type="hidden" name="Ds_Merchant_ConsumerLanguage " value="'.$this->_setIdioma.'" />
            <input type="hidden" name="Ds_Merchant_UrlOK" value="'.$this->_setUrlOk.'" />
            <input type="hidden" name="Ds_Merchant_UrlKO" value="'.$this->_setUrlKo.'" />
            <input type="hidden" name="Ds_Merchant_PayMethods" value="'.$this->_setMethods.'" />
            <input type="hidden" name="Ds_Merchant_MerchantSignature" value="'.$this->_setFirma.'" />       
        ';
        $formulario.=$this->_setSubmit;
        $formulario.='
        </form>        
        ';
        return $formulario;
    }#-#create_form()
    
    private function parseFloat($ptString)
    {
    	if (strlen($ptString) == 0) {
    		return false;
    	}
    	$pString = str_replace(" ", "", $ptString);
    	if (substr_count($pString, ",") > 1)
    	$pString = str_replace(",", "", $pString);
    	if (substr_count($pString, ".") > 1)
    	$pString = str_replace(".", "", $pString);
    	$pregResult = array();
    	$commaset = strpos($pString,',');
    	if ($commaset === false) {
    		$commaset = -1;
    	}
    	$pointset = strpos($pString,'.');
    	if ($pointset === false) {
    		$pointset = -1;
    	}
    	$pregResultA = array();
    	$pregResultB = array();
    	if ($pointset < $commaset) {
    		preg_match('#(([-]?[0-9]+(\.[0-9])?)+(,[0-9]+)?)#', $pString, $pregResultA);
    	}
    	preg_match('#(([-]?[0-9]+(,[0-9])?)+(\.[0-9]+)?)#', $pString, $pregResultB);
    	if ((isset($pregResultA[0]) && (!isset($pregResultB[0])
    	|| strstr($pregResultA[0],$pregResultB[0]) == 0
    	|| !$pointset))) {
    		$numberString = $pregResultA[0];
    		$numberString = str_replace('.','',$numberString);
    		$numberString = str_replace(',','.',$numberString);
    	}
    	elseif (isset($pregResultB[0]) && (!isset($pregResultA[0])
    	|| strstr($pregResultB[0],$pregResultA[0]) == 0
    	|| !$commaset)) {
    		$numberString = $pregResultB[0];
    		$numberString = str_replace(',','',$numberString);
    	}
    	else {
    		return false;
    	}
    	$result = (float)$numberString;
    	return $result;
    }    
}

?>
