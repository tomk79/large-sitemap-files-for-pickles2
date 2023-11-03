<?php
chdir(__DIR__);
require_once(__DIR__.'/vendor/autoload.php');

class main{
	private $px;
	private $fs;
	private $req;
	private $path_sitemap_dir;
	private $csv_filename;
	private $csv_filefullname;
	private $xlsx_filefullname;

	public function __construct(){
		ini_set( 'memory_limit', -1 );

		$this->px = new \picklesFramework2\px('./px-files/');

		$this->fs = new \tomk79\filesystem();
		$this->req = new \tomk79\request();

		$this->path_sitemap_dir = __DIR__.'/dist/';
		if( !is_dir($this->path_sitemap_dir) ){
			$this->fs->mkdir($this->path_sitemap_dir);
		}

		$this->csv_filename = $this->req->get_param('csv');
		$this->csv_filefullname = $this->csv_filename.'.csv';
		$this->xlsx_filefullname = $this->csv_filename.'.xlsx';
	}

	public function kick(){
		$this->init();
		$this->execute();
	}

	private function init(){
		$this->fs->rm( $this->path_sitemap_dir.$this->xlsx_filefullname );
	}

	private function execute(){
		echo 'Starting convert CSV to Xlsx: '.$this->csv_filefullname."\n";
		echo 'started at: '.date('c')."\n";
		$started_at = time();

		if( !is_file($this->path_sitemap_dir.$this->csv_filefullname) ){
			echo 'Error: CSV is NOT DEFINED.'."\n";
			echo "\n";
			return;
		}

		set_time_limit(0);

		$pickles_sitemap_excel = new \tomk79\pickles2\sitemap_excel\pickles_sitemap_excel($this->px);
		$pickles_sitemap_excel->csv2xlsx($this->path_sitemap_dir.$this->csv_filefullname, $this->path_sitemap_dir.$this->xlsx_filefullname, array(
			'target' => (preg_match('/^blogmap/i', $this->csv_filename) ? 'blogmap' : 'sitemap'),
		));

		set_time_limit(30);

		echo '--- done. (at '.date('c').', in '.(time() - $started_at).'sec)'."\n";
		echo "\n";
		return;
	}
}


$main = new main();
$main->kick();
exit();
