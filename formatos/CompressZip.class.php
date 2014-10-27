<?php

	namespace compress\formats;
	
	require_once '\CompressSuper.class.php';
	require_once 'InterfaceMetodosFormato.class.php';
	
	class CompressZip extends \compress\CompressSuper implements \InterfaceMetodosFormato {

		private $zip;

		/**
		 * Construtor responsavel po iniciar a instancia, caso o arquivo
		 * php.ini não tenha sido configurado corretamente para aceitar extensão
		 * .zip, será retornado uma excessão.
		*/
		public function __construct() {
			if (!extension_loaded('zip')) {
				throw new Exception('php_zip.dll -> Not ablitade, php.ini -> Line descoment "extension=php_zip.dll"');   
			} else
				$this->zip = new \ZipArchive();
		}
		
		public function open_format_create ($file_name) {
			if(isset($this->zip)) {
				// Cria um zip, caso ja exista será apagado
				if ($this->zip->open($file_name.'.zip', \ZIPARCHIVE::OVERWRITE ) === true)
			        return true;
			    else
					return false;
			} else
				return false;
		}
		
		public function close_format () {
			$this->zip->close();
		}

		/**
		 * Implementa o metodo que foi assinado na super, chama 
		 * o metodo especifico para adcionar no zip.
		 * @param: diretório do arquivo - $file
		 */
		public function adc_file($file) {
			$this->zip->addFile($file);	
		}
		
		public function extr_file ($dir_file) {
			$this->zip->extractTo($dir_file);
		}
		
		public function open_format ($file_name) {
			if(isset($this->zip)) {
				if ($this->zip->open($file_name.'.zip') === true)
			        return true;
			    else
					return false;
			} else
				return false;
		}
		
		/**
		 * Retorna a instancia do ZipArchive.
		 * @return: retorna a instancia - $zip
		*/
		public function getZip() {
			if($this->zip != null)
				return $this->zip;
		}
	}
?>