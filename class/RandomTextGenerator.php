<?php

Class RandomTextGenerator {

private $syllables = [
    'al' =>	[
        'rate' =>0.93,
        'startWord' => true,
        'notFollow' => []
        ],
    'an' =>	[
        'rate' =>2.17,
        'startWord' => true,
        'notFollow' => []
        ],
    'ar' =>	[
        'rate' =>1.06,
        'startWord' => true,
        'notFollow' => []
        ],
    'as' =>	[
        'rate' =>1.09,
        'startWord' => true,
        'notFollow' => ['ng','ve']
        ],
    'at' =>	[
        'rate' =>1.17,
        'startWord' => true,
        'notFollow' => ['wa','th','nd']
        ],
    'ea' =>	[
        'rate' =>0.84,
        'startWord' => true,
        'notFollow' => []
        ],
    'ed' =>	[
        'rate' =>1.29,
        'startWord' => true,
        'notFollow' => ['th','nt']
        ],
    'en' =>	[
        'rate' =>1.37,
        'startWord' => true,
        'notFollow' => ['ng','nd']
        ],
    'er' =>	[
        'rate' =>2.11,
        'startWord' => true,
        'notFollow' => []
        ],
    'es' =>	[
        'rate' =>1.00,
        'startWord' => true,
        'notFollow' => ['nd','st']
        ],
    'ha' =>	[
        'rate' =>1.17,
        'startWord' => true,
        'notFollow' => []
        ],
    'he' =>	[
        'rate' =>3.65,
        'startWord' => true,
        'notFollow' => ['he']
        ],
    'hi' =>	[
        'rate' =>1.07,
        'startWord' => true,
        'notFollow' => ['in']
        ],
    'in' =>	[
        'rate' =>2.10,
        'startWord' => true,
        'notFollow' => ['th']
        ],
    'is' =>	[
        'rate' =>0.99,
        'startWord' => true,
        'notFollow' => ['th']
        ],
    'it' =>	[
        'rate' =>1.24,
        'startWord' => true,
        'notFollow' => ['nt']
        ],
    'le' =>	[
        'rate' =>0.95,
        'startWord' => true,
        'notFollow' => []
        ],
    'me' =>	[
        'rate' =>0.83,
        'startWord' => true,
        'notFollow' => []
        ],
    'nd' =>	[
        'rate' =>1.62,
        'startWord' => false,
        'notFollow' => ['th','ne','he','nt','ng']
        ],
    'ne' =>	[
        'rate' =>0.75,
        'startWord' => true,
        'notFollow' => []
        ],
    'ng' =>	[
        'rate' =>0.99,
        'startWord' => false,
        'notFollow' => ['th','nt']
        ],
    'nt' =>	[
        'rate' =>0.77,
        'startWord' => false,
        'notFollow' => ['ng']
        ],
    'on' =>	[
        'rate' =>1.36,
        'startWord' => true,
        'notFollow' => []
        ],
    'or' =>	[
        'rate' =>1.09,
        'startWord' => true,
        'notFollow' => ['or']
        ],
    'ou' =>	[
        'rate' =>1.41,
        'startWord' => true,
        'notFollow' => ['ve']
        ],
    're' =>	[
        'rate' =>1.64,
        'startWord' => true,
        'notFollow' => []
        ],
    'se' =>	[
        'rate' =>0.85,
        'startWord' => true,
        'notFollow' => []
        ],
    'st' =>	[
        'rate' =>0.96,
        'startWord' => true,
        'notFollow' => ['th','nt','th','ve','ti','to','st','hi','he']
        ],
    'te' =>	[
        'rate' =>1.00,
        'startWord' => true,
        'notFollow' => []
        ],
    'th' =>	[
        'rate' =>3.99,
        'startWord' => true,
        'notFollow' => ['hi','he','nd','ve','te','st','to','th','se','le','ng']
        ],
    'ti' =>	[
        'rate' =>0.92,
        'startWord' => true,
        'notFollow' => []
        ],
    'to' =>	[
        'rate' =>1.24,
        'startWord' => true,
        'notFollow' => []
        ],
    've' =>	[
        'rate' =>1.11,
        'startWord' => true,
        'notFollow' => ['ve']
        ],
    'wa' =>	[
        'rate' =>0.84,
        'startWord' => true,
        'notFollow' => []
        ],
];

private $syllablesProbability = [];


function __construct() {

    foreach ($this->syllables as $key => $value) {
        for($i=0;$i<$value['rate']*100;$i++){
            $this->syllablesProbability[] = $key;
        }
    }

    
}

public function getRandomSyllable(){
    return $this->syllablesProbability[mt_rand(0,count($this->syllablesProbability)-1)];
}


public function getRandomWord(){
    $w = '';
    $wordLength = mt_rand(1,6);
    while($w == ''){
        $s = $this->getRandomSyllable();
        if($this->syllables[$s]['startWord']){
            if($wordLength == 1){
                if((substr_count($s,'a')+substr_count($s,'e')+substr_count($s,'i')+substr_count($s,'o')+substr_count($s,'u')>0)){
                    $w = $s;
                }
            }
            else{
                $w = $s;
            } 
        }
    }

    $c = 1;
    while($c < $wordLength){
        $s = $this->getRandomSyllable();
        

        if(!in_array($s, $this->syllables[ substr($w,-2)]['notFollow']  ) ){			
            $w .= $s;
            $c++;
        }
    }
    

    return $w;

}

public function getRandomSentence(){
    $words = [];
    for ($i=0; $i < mt_rand(5,20) ; $i++) { 
        $w = $this->getRandomWord();
        if(mt_rand(0,30)==0){
            $w = '<a href="/page-'.$this->getBigRandomNumber().'.html">'.$w.'</a>';
        }
        $words[] = $w;
    }

    return ucfirst(implode(' ', $words) .'. ');
}


public function getRandomParagraph(){
    $p = '';
    for ($i=0; $i < mt_rand(5,60) ; $i++) { 
        $p .= $this->getRandomSentence();
    }

    return "<p>".$p."</p>";
}

public function getRandomText(){
    $t = '';
    for ($i=0; $i < mt_rand(5,10) ; $i++) { 
        $t .= $this->getRandomParagraph();
    }

    return $t;
}

public function getRandomLine(){
    $words = [];
    for ($i=0; $i < mt_rand(2,5) ; $i++) { 
        $words[] = $this->getRandomWord();
    }

    return ucfirst(implode(' ', $words));
}

function getBigRandomNumber(){
    $n = '';
    for($i=0;$i<mt_rand(1,300);$i++){
        $n .= mt_rand(0,9);
    }
    return $n;
}


}