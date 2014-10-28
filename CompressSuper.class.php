<?php

	// Definido o namespace
	namespace compress;

	/**
	 * Classe pai, responsavel por defenir quais serão as ações 
	 * que todas as classes filhas terão que fazer, ZIP, BZIP2 ...
	 * Sendo abstract não podendo ser instaciada, alguns metodos,
	 * não serão implemetados por serem muito simples ou por sere
	 * muito distintas dos metodos das classes filhas.
	 * @author: Jackson Garcia Pinheiro Junior
	*/
	abstract class CompressSuper {

		/**
		 * Cria um arquivo compactando todo o diretório passoado
		 * ou o arquivo.
		 * Metodo reponsavel por comprimir um diretório ou apenas
		 * um arquivo, caso os diretório de salvamento ou nome a ser
		 * ser definido do arquivo não for passado sera inserido valores
		 * default definidos no código.
		 * @param diretório onde se encontra os arquivos - $dir_raiz
		 * @param local onde será salvo o arquivo compactado - $dir_save
		 * @param nome do arquivo compactado - $file_name
		*/
		public function add_to_files ($dir_raiz, $dir_save=null, $file_name=null) {	
			if (is_file($dir_raiz)) {
				if ($file_name == null)
					$file_name = dirname ( realpath ( $dir_raiz) );
					
				if ($dir_save != null) $file_name = $dir_save.'\\'.basename($file_name);
				else $file_name = basename($file_name);	
				
				if ($this->open_format_create ($file_name)) {
					$this->adc_file ($dir_raiz);
					$this->close_format();
				} else
					throw new Exception('CREATE ARCHIVE ERROR');
				
			} else if (is_dir($dir_raiz)) {
				if ($file_name == null) $file_name = $dir_raiz;
				if ($dir_save != null) $file_name = $dir_save.'\\'.$file_name;

				if($this->open_format_create ($file_name) == true) {
					$this->comprim_dir($dir_raiz);
					$this->close_format();
				} else
					throw new \Exception('CREATE ARCHIVE ERROR');
			} else
				throw new \Exception('PATH ERROR');
		}
		
		/**
		 * Percorre o diretório recursivamente e insere no arquivo.
		 * @param diretório do arquivo - $cwd
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
		
		public function add_to_name ($dir_raiz) {
			$this->add_to_files ($dir_raiz);
		}
		
		public function extract_files ($dir_raiz, $dir_save=null) {	
			if ($dir_save == null) $dir_save = '.\\';
			if ($this->open_format ($dir_raiz)) {
				$this->extr_file ($dir_save);
				$this->close_format();
			} else
				throw new \Exception('OPEN ARCHIVE ERROR');
		}
		
		public function extract_here ($dir_raiz) {
			if ($this->open_format ($dir_raiz)) {
				$this->extr_file ($dir_save);
				$this->close_format();
			} else
				throw new \Exception('OPEN ARCHIVE ERROR');
		}
		
		public function extract_to_name ($dir_raiz) {
			if ($this->open_format ($dir_raiz)) {
				if (!file_exists (basename($dir_raiz))) {
					mkdir('.\\'.basename($dir_raiz), 0777);
					$this->extr_file (basename($dir_raiz));
					$this->close_format();
				} else 
					throw new \Exception('ARCHIVE EXISTS ERROR');
			} else
				throw new \Exception('OPEN ARCHIVE ERROR');
		}
		
		public function add_to_archive ($dir_raiz, $dir_file) {
			if ($this->open_format ($dir_raiz)) {
				$this->adc_file ($dir_file);
				$this->close_format();
			} else
				throw new \Exception('OPEN ARCHIVE ERROR');
		}
		
		public function extract_file_archive ($dir_raiz, $dir_file, $file_name) {
			if ($this->open_format ($dir_raiz)) {
				if (!file_exists (basename($dir_raiz))) {
					mkdir('.\\'.basename($dir_raiz), 0777);
					$this->extr_file (basename($dir_raiz), $file_name);
					$this->close_format();
				} else 
					throw new \Exception('ARCHIVE EXISTS ERROR');
			} else
				throw new \Exception('OPEN ARCHIVE ERROR');
		}
		
		public function delete_file ($dir_raiz, $file_name) {
			if ($this->open_format ($dir_raiz)) {
				$this->deleteName ($file_name);
				$this->close_format();
			} else
				throw new \Exception('OPEN ARCHIVE ERROR');
		}
		
		public function load_file ($dir_raiz, $file_name=null, $dir_index=null) {
			if ($this->open_format ($dir_raiz)) {
				if ($file_name != null) return $this->getFileName ($file_name);
				else if ($dir_index !=) return $this->getFileName ($dir_index);
				$this->close_format();
			} else
				throw new \Exception('OPEN ARCHIVE ERROR');
		}

	}
?>