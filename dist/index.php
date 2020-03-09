<?php
//----------------------------------------------//----------------------------------------------

include('class/RandomTextGenerator.php');
include('class/Stats.php');
include('class/CrawlerDetection.php');

if(isset($_GET["p"])){
	$page = $_GET["p"];
	if(!ctype_digit($page) && !in_array($page, ['stats','about'])){die();}
}
else{
	$page = 1;
}

$randomTextGenerator = new RandomTextGenerator();
$stats = new Stats();

if($page !== 'stats'){
	
	$h1 = "NOTENO - page: $page | ".$randomTextGenerator->getRandomLine();
	$txt = $randomTextGenerator->getRandomText();
	$stats->incrementData(strlen($h1.$txt));
}

//----------------------------------------------//----------------------------------------------
?>

<!DOCTYPE html>
<html>
<head>
	<title>NOTENO - <?=$page?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		body {
			background-color: DarkSeaGreen;
			font-family: monospace;
            padding: 30px;
		}
        table{
            border-collapse: collapse;
        }
		td,th{
			padding:2px 10px;
            text-align: right;
		}
        td{
            border: 1px solid green;
        }
        ul {
            display: flex;
            margin: 0;
            padding: 0;
        }
        li{
            list-style: none;
            padding-right: 30px;
        }
        .text-red{
            color:red;
        }
        .d-flex{
            display:flex;
        }
	</style>
</head>
<body>
<?php if($page == 'stats'): //---------------------------------------------- ?>
    
    <nav>
		<a href="/">home</a>
	</nav>

	<h1>statistics</h1>

    <?php $stats->render() ?>
    
    <?php elseif($page == 'about'): //---------------------------------------------- ?>   
    
    <nav>
		<a href="/">home</a>
	</nav>

	<h1>about</h1>
    <p>Search engines are extremely powerful machines. The algorithms behind these machines decide which information is considered relevant and comes into our field of vision. The basis of all search engines is the gathering of information. For this purpose, so-called bots or crawlers are used. These bots visit every page of the visible Internet at regular intervals and analyze and store the information that a website provides. The crawlers follow all links on a page and work their way through all subpages of a website.</p>
    <p>But what would happen if a single website had an infinite number of subpages that are all linked to each other? This is exactly what the project NOTENO does. The page itself consists of only a few kilobytes and less than 10 files, but offers an infinite number of pages that are all filled with total nonsense.</p>
    <p>
    <b>Noteno is an open source project. Get the code from github, copy it to your own webspace and start feeding...</b><br><br>

    <a href="https://github.com/rgb3000/noteno">https://github.com/rgb3000/noteno</a>
    </p>
    <?php else: //---------------------------------------------- ?>
        
        <nav>
            <ul>
                <li>
                    <a href="/page-<?=bcadd($page, 1);?>.html">next page</a> 
                </li>
                <li>
                    <a href="/stats.html">statistics</a>
                </li>
                <li><a href="/about.html">about</a></li>
            </ul>
        </nav>
    
        <article>
            <h1><?=$h1?></h1>
            <?= $txt?>
        </article>


<?php endif //---------------------------------------------- ?>
</body>
</html>