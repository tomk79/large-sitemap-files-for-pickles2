<?php
require_once(__DIR__.'/vendor/autoload.php');

class main{
    private $fs;
    private $req;
    private $conf;
    private $path_sitemapCsv;
    private $page_numer = 0;

    public function __construct(){
        $this->fs = new \tomk79\filesystem();
        $this->req = new \tomk79\request();

        $this->path_sitemapCsv = __DIR__.'/dist/';
        if( !is_dir($this->path_sitemapCsv) ){
            $this->fs->mkdir($this->path_sitemapCsv);
        }

        $this->conf = new stdClass;
        $this->conf->bros = 5;
        $this->conf->depth = 6;
        $this->conf->max = 5000;

        if( $this->req->get_param('max') ){
            $this->conf->max = intval( $this->req->get_param('max') );
        }
        if( $this->req->get_param('bros') ){
            $this->conf->bros = intval( $this->req->get_param('bros') );
        }
        if( $this->req->get_param('depth') ){
            $this->conf->depth = intval( $this->req->get_param('depth') );
        }

        $this->csv_filename = 'sitemap_max'.intval($this->conf->max).'_d'.intval($this->conf->depth).'_b'.intval($this->conf->bros).'.csv';
    }

    public function kick(){
        $this->init();
        $this->execute();
    }

    private function init(){
        $this->fs->rm( $this->path_sitemapCsv.$this->csv_filename );
        $this->page_numer = 0;
        $csv = array();
        array_push($csv, array(
            'path'=>'* path',
            'content'=>'* content',
            'id'=>'* id',
            'title'=>'* title',
            "title_breadcrumb" => "* title_breadcrumb",
            "title_h1" => "* title_h1",
            "title_label" => "* title_label",
            "title_full" => "* title_full",
            "logical_path" => "* logical_path",
            "list_flg" => "* list_flg",
            "layout" => "* layout",
            "orderby" => "* orderby",
            "keywords" => "* keywords",
            "description" => "* description",
            "category_top_flg" => "* category_top_flg",
        ));
        array_push($csv, array(
            'path'=>'/',
            'content'=>'',
            'id'=>'',
            'title'=>'HOME',
            "title_breadcrumb" => "",
            "title_h1" => "",
            "title_label" => "",
            "title_full" => "",
            "logical_path" => "",
            "list_flg" => "1",
            "layout" => "top",
            "orderby" => "",
            "keywords" => "",
            "description" => "",
            "category_top_flg" => "1",
        ));
        error_log( $this->fs->mk_csv($csv), 3, $this->path_sitemapCsv.$this->csv_filename );
    }

    private function execute($breadcrumb = '', $depth = 0){

        for( $iBros = 0; $iBros<$this->conf->bros; $iBros++ ){
            if($this->page_numer > $this->conf->max){
                break;
            }
            $csv = array();
            $pageId = 'page'.$this->page_numer;
            array_push($csv, array(
                'path'=>'/page/page_'.$this->page_numer.'.html',
                'content'=>'',
                'id'=>$pageId,
                'title'=>'Page '.$this->page_numer.'',
                "title_breadcrumb" => "",
                "title_h1" => "",
                "title_label" => "",
                "title_full" => "",
                "logical_path" => $breadcrumb,
                "list_flg" => "1",
                "layout" => "",
                "orderby" => "",
                "keywords" => "",
                "description" => "",
                "category_top_flg" => (strlen($breadcrumb)?'':'1'),
            ));
            var_dump('/page/page_'.$this->page_numer.'.html');
            error_log( $this->fs->mk_csv($csv), 3, $this->path_sitemapCsv.$this->csv_filename );
            $this->page_numer ++;

            if($depth < $this->conf->depth){
                $this->execute((strlen($breadcrumb)?$breadcrumb.'>':'').$pageId, $depth+1);
            }
        }

    }
}


$main = new main();
$main->kick();
exit();
