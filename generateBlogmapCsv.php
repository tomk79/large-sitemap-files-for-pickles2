<?php
require_once(__DIR__.'/vendor/autoload.php');

class main{
    private $fs;
    private $req;
    private $conf;
    private $path_blogmapCsv;
    private $page_numer = 0;

    public function __construct(){
        $this->fs = new \tomk79\filesystem();
        $this->req = new \tomk79\request();

        $this->path_blogmapCsv = __DIR__.'/dist/';
        if( !is_dir($this->path_blogmapCsv) ){
            $this->fs->mkdir($this->path_blogmapCsv);
        }

        $this->conf = new stdClass;
        $this->conf->max = 5000;

        if( $this->req->get_param('max') ){
            $this->conf->max = intval( $this->req->get_param('max') );
        }
        if( !is_int($this->conf->max) || $this->conf->max <= 0 ){
            $this->conf->max = 5000;
        }

        $this->csv_filename = 'blogmap_max'.intval($this->conf->max).'.csv';
    }

    public function kick(){
        $this->init();
        $this->execute();
    }

    private function init(){
        $this->fs->rm( $this->path_blogmapCsv.$this->csv_filename );
        $this->page_numer = 0;
        $csv = array();
        array_push($csv, array(
            'title'=>'* title',
            'path'=>'* path',
            "release_date" => "* release_date",
            "update_date" => "* update_date",
            "article_summary" => "* article_summary",
            "article_keywords" => "* article_keywords",
        ));
        error_log( $this->fs->mk_csv($csv), 3, $this->path_blogmapCsv.$this->csv_filename );
    }

    private function execute(){
        $date_base = strtotime('2023-01-01');

        while(1){
            if($this->page_numer > $this->conf->max){
                break;
            }
            $csv = array();
            $str_date = date('Y-m-d', $date_base + ($this->page_numer*60*60*24));
            array_push($csv, array(
                'title'=>'Page '.$this->page_numer.'/'.intval($this->conf->max).'',
                'path'=>'/page/page_'.$this->page_numer.'_'.intval($this->conf->max).'.html',
                "release_date" => $str_date,
                "update_date" => $str_date,
                "article_summary" => "",
                "article_keywords" => "",
            ));
            var_dump('/page/page_'.$this->page_numer.'.html');
            error_log( $this->fs->mk_csv($csv), 3, $this->path_blogmapCsv.$this->csv_filename );
            $this->page_numer ++;
        }

    }


}


$main = new main();
$main->kick();
exit();
