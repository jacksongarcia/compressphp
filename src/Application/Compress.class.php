<?php
	namespace Application;

	require_once 'Application\Format\CompressZip.class.php';

	class Compress {

		/**
		 * Retorna a instancia do formato especificado no parametro,
		 * indicado declarar a instancia dessa classe 
		 * "(new Compress)->getInstanceFormat(formato)".
		 * @param: formato desejado (ZIP, RAR, BZIP) - $format
		 */
		public function getInstanceFormat($format) {
			if($format != null) {
				if($format == 'zip') {
					return new formats\CompressZip;
				}
			}
		}
	}
?>