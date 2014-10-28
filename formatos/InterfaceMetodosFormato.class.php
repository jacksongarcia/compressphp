<?php
	Interface InterfaceMetodosFormato {
		public function open_format_create ($file_name);
		
		public function close_format ();
		
		/**
		 * Chama o metodo especifico de adcionar um arquivo na 
		 * compactação.
		 * @param: diretório do arquivo - $file
		*/
		public function adc_file ($dir_file);
		
		public function extr_file ($dir_file);
		
		public function open_format ($file_name);
		
		public function delete_file ($file_name);
		
		public function getFileName ($file_name);
		
		public function getFileIndex ($file_index);
	}
?>