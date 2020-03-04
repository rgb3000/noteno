<?php

use Jaybizzle\CrawlerDetect\CrawlerDetect;
Class Stats{

private $db;

function __construct() {
    if(!file_exists('stats.db')){
        $this->initDB();
    }
}


private function initDB(){
    
    $this->db = new PDO('sqlite:stats.db');
    $this->db->exec("CREATE TABLE IF NOT EXISTS stats ( 
                    user_agent varchar(255) PRIMARY KEY, 
                    pageviews INTEGER,
                    total_bytes INTEGER,
                    updated_at INTEGER)");
}

public function incrementData($bytes){

    $ua = $_SERVER['HTTP_USER_AGENT'];

    $CrawlerDetect = new CrawlerDetect;

    if($CrawlerDetect->isCrawler($ua)) {
        $agent = $CrawlerDetect->getMatches();
    }
    else{
        $agent = 'human';
    }

    $this->db = new PDO('sqlite:stats.db');
    $this->db->exec("INSERT INTO stats (user_agent, pageviews, total_bytes,updated_at) VALUES ('".$agent."', 1,".$bytes.",".time().")");
    $this->db->exec("UPDATE stats SET pageviews = pageviews + 1, total_bytes = total_bytes + ".$bytes.",updated_at = ".time()." WHERE user_agent = '".$agent."' ");
}


public function getStatsData(){

    $this->db = new PDO('sqlite:stats.db');
    
    return $this->db->query('SELECT * FROM stats ORDER BY total_bytes DESC');
}

}