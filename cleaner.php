<?php

set_time_limit(0);
ob_start();
header("Content-type:text/plain");

$root = "./";

$aPattern = array(
"^<\?php\s*\\\$md5\s*=\s*.*create_function\s*\(.*?\);\s*\\\$.*?\)\s*;\s*\?>\s*",
" echo \"<script type=\\\\\"text\/javascript\\\\\" src=\\\\\"http:\/\/.*\.js\\\\\"><\/script>\"; echo \"\";",
"<\?php\s*\@error_reporting\(0\);\s*if\s*\(\!isset\(([\$\w]+)\)\)\s*{[\$]+[^}]+}\s*\?>",
"<\?php\s*\/\*\w+_on\*\/.*\/\*\w+_off\*\/\s*\?>",
"<\?php\s*\/\*god_mode_on\*\/eval\(base64_decode\([\"'][^\"']{255,}[\"']\)\);\s*\/\*god_mode_off\*\/\s*\?>",
"<\?php\s*\?>",
"<IfModule\s*mod_rewrite\.c>\s*RewriteEngine\s*On\s*RewriteCond\s*%\{HTTP_REFERER\}\s*\^\.\*\([^\)]{255,}[google|yahoo|bing|ask|wikipedia|youtube][^\)]{255,}[^<]*<\/IfModule>",
"ErrorDocument\s*(?:400|401|403|404|500)+\s*http:\/\/.*\.\w+",
"^<script>(.*)<\/script>",
"^<\?php\s*\\\$md5\s*=\s*[\"|']\w+[\"|'];\s*\\\$wp_salt\s*=\s*[\w\(\),\"\'\;\$]+\s*\\\$wp_add_filter\s*=\s*create_function\(.*\);\s*\\\$wp_add_filter\(.*\);\s*\?>\s*",
"\s*eval\(base64_decode\([\"'][^\"']{255,}[\"']\)\);",
"if\(!function_exists\([^{]+\s*{\s*function[^}]+\s*}\s*[^\"']+\s*[\"'][^\"']+[\"'];\s*eval\s*\(.*\)\s*;\s*}\s*",
);

$find = '('.implode('|', $aPattern).')';

$except = array("rar", "zip", "mp3", "mp4", "mp3", "mov", "flv", "wmv", "swf", "png", "gif", "jpg", "bmp", "avi");
$only = array("php", "shtml", "html", "htm", "js", "css", "htaccess", "txt");
$infectedFiles = null;
$showOnlyInfectedFiles = true;
$cleanInfected = true;

$infectedFiles = startScan($root);

echo "\n\nFound Files\n";
echo "\n";
if(is_array($infectedFiles)){
$j=1;
foreach($infectedFiles AS $iFile){
	echo "\t{$j}. {$iFile}\n";
$j++; }
echo "\n";
}


/* functions */
function getAllFiles($dir){
global $except, $only;
	$filenames = null;
	if ($handle = opendir($dir)){
		while (false !== ($file = readdir($handle))) 
			if ($file != "." && $file != ".." && !is_dir($dir.$file)){
				$path_parts = pathinfo($file);
				if(isset($path_parts['extension']) && array_search(strtolower($path_parts['extension']), $except) === false)
					if(array_search(strtolower($path_parts['basename']), $only) !== false || array_search(strtolower($path_parts['extension']), $only) !== false || sizeof($only) < 1)
						$filenames[] = $file;
			}
		closedir($handle);
	}

	return $filenames;
}

function getAllDirectories($dir){
	$directories = null;
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle)))
			if ($file != "." && $file != ".." && is_dir($dir.$file))
				$directories[] = $dir.$file;
		closedir($handle);
	}

	return $directories;
}


function startScan($root, $tabs=""){
global $find, $infectedFiles, $showOnlyInfectedFiles, $cleanInfected;

	$time_start = microtime_float();

	$root = str_replace("//", "/", $root);
	echo "\n\n{$tabs}".$root;
	ob_implicit_flush();
	ob_flush();
	sleep(1);
			 
	$directories = getAllDirectories($root);
	if(is_array($directories)){
	
		// get all files
		if(($tmp = getAllFiles($root)) !== null){
			$files = $tmp;
			foreach($files AS $file){
				$numMatches = checkMalware($root.$file, $find);
				if(!empty($numMatches)){
					if($cleanInfected)
						cleanInfected($root.$file, $find);
						
					echo "\n\t{$tabs} * ".$infectedFiles[] = $root.$file;
					echo " - ".(microtime_float() - $time_start);
				}elseif(!$showOnlyInfectedFiles){
					$infectedFiles[] = $root.$file;
					echo "\n\t{$tabs} - ".$root.$file;
				}
				ob_implicit_flush();
				ob_flush();
				sleep(1);
			}
			echo "\n";
		}
		
		foreach($directories AS $dir){
			echo "\n\t{$tabs}".$dir;
			 ob_implicit_flush();
			 ob_flush();
			 sleep(1);
			 
			// get all files
			if(($tmp = getAllFiles($dir)) !== null){
				$files = $tmp;
				foreach($files AS $file){
					if($dir[strlen($dir)-1] === "/") $dir = substr($dir, 0, -1); 
					$numMatches = checkMalware($dir."/".$file, $find);
					if(!empty($numMatches)){
						if($cleanInfected)
							cleanInfected($dir."/".$file, $find);
							
						echo "\n\t\t{$tabs} * ".$infectedFiles[] = $dir."/".$file;
						echo " - ".(microtime_float() - $time_start);
					}elseif(!$showOnlyInfectedFiles) {
						$infectedFiles[] = $dir."/".$file;
						echo "\n\t\t{$tabs} - ".$infectedFiles[] = $dir."/".$file;
					}
				 ob_implicit_flush();
				 ob_flush();
				 sleep(1);
				}
			}
			
			// gel all directories
			if($root[strlen($root)-1] === "/") $tmp_root = substr($root, 0, -1); 
			if(($tmp = getAllDirectories($dir."/")) !== null && $dir !== $tmp_root){
				foreach($tmp AS $d){
					$tabs .= "\t";
					$a = startScan($d."/", $tabs);
					if(is_array($a))
						array_merge($infectedFiles, $a);
				}
				
			}
		}
	}else{
		// get all files
		if(($tmp = getAllFiles($root)) !== null){
			$files = $tmp;
			foreach($files AS $file){
				$numMatches = checkMalware($root.$file, $find);
				if(!empty($numMatches)){
					if($cleanInfected)
						cleanInfected($root.$file, $find);
						
					echo "\n\t{$tabs} * ".$infectedFiles[] = $root.$file;
					echo " - ".(microtime_float() - $time_start);
				}elseif(!$showOnlyInfectedFiles){
					$infectedFiles[] = $root.$file;
					echo "\n\t{$tabs} - ".$root.$file;
				}
			 ob_implicit_flush();
			 ob_flush();
			 sleep(1);
			}
			echo "\n";
		}
	}
	
 return $infectedFiles;
}

function checkMalware($filename, $find){
	$numMatches = null;
	$handle = fopen($filename, "r");
	if(filesize($filename) > 0){
		$contents = fread($handle, filesize($filename));
		$numMatches = preg_match_all('/'.$find.'/is', $contents, $matches);
	}
	fclose($handle);
	return $numMatches;
}

function cleanInfected($filename, $find){
	$handle = fopen($filename, "r");
	if(filesize($filename) > 0){
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		
		chmod($filename, 0755);
		$handle = fopen($filename, "w");
		$contents = preg_replace('/'.$find.'/is', '', $contents);
		fwrite($handle, $contents);
	}
	fclose($handle);
}

function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


ob_end_flush();