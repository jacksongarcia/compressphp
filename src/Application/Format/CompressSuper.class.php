<?php

	// Definido o namespace
	namespace Application\Format;

	class ErrorCreatingFile extends \Exception {}
	class ErrorDirectory extends \Exception {}
	class ErrorOpeningFile extends \Exception {}
	class ErrprFileExists extends \Exception {}

	/**
	 * Classe pai, responsavel por defenir quais serão as ações 
	 * que todas as classes filhas terão que fazer, ZIP, BZIP2 ...
	 * Foram escolhidos metodos básicos para se trabalhar com 
	 * arquivos comprimidos, os mais usados quando se trabalhar
	 * programas do winrar.
	 * @author: Jackson Garcia Pinheiro Junior <jacksongarciajr@gmail.com>
	 * @version 0.1
	 * @copyright MIT @ 2014
	 * @access protected
	 * @package Format
	*/
	abstract class CompressSuper {

		/**
		 * Cria um arquivo compactando todo o diretório passado
		 * ou o arquivo.
		 * Metodo reponsavel por comprimir um diretório ou apenas
		 * um arquivo, caso os diretório de salvamento ou nome a ser
		 * ser definido do arquivo não for passado sera inserido valores
		 * default definidos no código.
		 * @param $dir_raiz diretório onde se encontra os arquivos 
		 * @param $dir_save local onde será salvo o arquivo compactado 
		 * @param $file_name nome do arquivo compactado
		 * @todo Atualmente esse metodo só funciona corretamente com
		 * arquivo que estão no mesmo nivel de diretório que essa classe.
		 * @exception ErrorCreatingFile não foi possivel criar o arquivo no
		 * diretório.
		 * @exception ErrorDirectory diretório nao acessivel ou não foi encontrado.
		*/
		public function testAddToFiles ($dir_raiz, $dir_save=null, $file_name=null) {	
			if (is_file($dir_raiz)) {
				if ($file_name == null)
					$file_name = dirname ( realpath ( $dir_raiz) );
					
				if ($dir_save != null) $file_name = $dir_save.'\\'.basename($file_name);
				else $file_name = basename($file_name);	
				
				if ($this->open_format_create ($file_name)) {
					$this->adc_file ($dir_raiz);
					$this->close_format();
				} else
					throw new \ErrorCreatingFile('Error creating file name -> '.$file_name);
				
			} else if (is_dir($dir_raiz)) {
				if ($file_name == null) $file_name = $dir_raiz;
				if ($dir_save != null) $file_name = $dir_save.'\\'.$file_name;

				if($this->open_format_create ($file_name) == true) {
					$this->comprim_dir($dir_raiz);
					$this->close_format();
				} else
					throw new \ErrorCreatingFile('Error creating file name -> '.$file_name);
			} else
				throw new \ErrorDirectory('Error in directory -> '.$dir_raiz);
		}
		
		/**
		 * Percorre o diretório recursivamente, inserindo os dados
		 * no arquivo compactado.
		 * @param $cwd diretório do arquivo
		*/
		private function comprim_dir($cwd) {
			$open = opendir($cwd);
		    while($folder = readdir($open)) {
		        if ($folder != '.' && $folder != '..') {
		            if (is_dir($cwd.'\\'.$folder)) {
		                $dir = str_replace('./', '',($cwd.'\\'.$folder));
		                $this->comprim_dir($dir);
		            } 
		            else if (is_file($cwd.'\\'.$folder)) {
		                $arq = str_replace('./', '',$cwd.'\\'.$folder); 						
		                $this->adc_file ($arq);                                                                
		            }
		        }
			}
		}
		
		/**
		 * Cria um arquivo compactado com o nome padrão do arquivo.
		 * @param $dir_raiz recebe o diretório do arquivo a ser compactado.
		*/
		public function add_to_name ($dir_raiz) {
			$this->add_to_files ($dir_raiz);
		}
		
		/**
		 * Extrae o arquivo compactado em um diretório definido em código,
		 * pu dinamicamente.
		 * @param $dir_raiz diretório raiz do arquivo compactado
		 * @param $dir_save diretório onde será salvo o arquivo
		 * descomprimido.
		*/
		public function extract_files ($dir_raiz, $dir_save=null) {	
			if ($dir_save == null) $dir_save = '.\\';
			if ($this->open_format ($dir_raiz)) {
				$this->extr_file ($dir_save);
				$this->close_format();
			} else
				throw new \ErrorOpeningFile('Error opening file -> '.$dir_raiz);
		}
		
		/**
		 * Extrae o arquivo no diretório atual.
		 * @param $dir_raiz diretório do arquivo compactado
		*/
		public function extract_here ($dir_raiz) {
			if ($this->open_format ($dir_raiz)) {
				$this->extr_file ($dir_save);
				$this->close_format();
			} else
				throw new \ErrorOpeningFile('Error opening file -> '.$dir_raiz);
		}
		
		/**
		 * Extrae o arquivo com o nome padrão da pasta 
		 * ou do próprio arquivo.
		 * @param $dir_raiz diretório do arquivo compactado
		*/
		public function extract_to_name ($dir_raiz) {
			if ($this->open_format ($dir_raiz)) {
				if (!file_exists (basename($dir_raiz))) {
					mkdir('.\\'.basename($dir_raiz), 0777);
					$this->extr_file (basename($dir_raiz));
					$this->close_format();
				} else 
					throw new \ErrprFileExists('file already exists error -> '.$dir_raiz);
			} else
				throw new \ErrorOpeningFile('Error opening file -> '.$dir_raiz);
		}
		
		public function add_to_archive ($dir_raiz, $dir_file) {
			if ($this->open_format ($dir_raiz)) {
				$this->adc_file ($dir_file);
				$this->close_format();
			} else
				throw new \ErrorOpeningFile('Error opening file -> '.$dir_raiz);
		}
		
		public function extract_file_archive ($dir_raiz, $dir_file, $file_name) {
			if ($this->open_format ($dir_raiz)) {
				if (!file_exists (basename($dir_raiz))) {
					mkdir('.\\'.basename($dir_raiz), 0777);
					$this->extr_file (basename($dir_raiz), $file_name);
					$this->close_format();
				} else 
					throw new \ErrprFileExists('file already exists error -> '.$dir_raiz);
			} else
				throw new \ErrorOpeningFile('Error opening file -> '.$dir_raiz);
		}
		
		public function delete_file ($dir_raiz, $file_name) {
			if ($this->open_format ($dir_raiz)) {
				$this->deleteName ($file_name);
				$this->close_format();
			} else
				throw new \ErrorOpeningFile('Error opening file -> '.$dir_raiz);
		}
		
		public function load_file ($dir_raiz, $file_name=null, $dir_index=null) {
			if ($this->open_format ($dir_raiz)) {
				if ($file_name != null) return $this->getFileName ($file_name);
				else if ($dir_index !=) return $this->getFileName ($dir_index);
				$this->close_format();
			} else
				throw new \ErrorOpeningFile('Error opening file -> '.$dir_raiz);
		}

	}
?>