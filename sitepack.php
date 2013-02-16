<?php 
//https://github.com/R-Tur/sitepack

ini_set("display_errors", 1);
$f = @fopen('sitepack_extract.php','w+');
if(!$f) die("need permission for creating files");
fwrite($f,"<?php\n");
function rdir ($path2dir) {
    $d = dir ($path2dir); 
 
    while (false !== ($entry = $d->read())) { 
 
        if ($entry!='.' && $entry!='..' && $entry!='' ) {
            $all_path = $path2dir.$entry;
            $new_path = go ($all_path, is_file($all_path)); 
 
            if (!is_file($all_path)) {
                if (!rdir ($new_path)) {
                    return false;
                }
            }
        }
    } 
 
    return true;
}
 
function go ($path2file, $is_file = true) { 
  global $f;
 
    if ($is_file) { 
	  if($path2file!='./sitepack_extract.php'){
        fwrite($f,"\$fname[]='$path2file';\n");
        $base64 = base64_encode(file_get_contents($path2file));
        fwrite($f,"\$fbase64[]='$base64';\n");      
      }
    } else { 
 
        $path2file = $path2file.'/'; 
 
        fwrite($f,"\$dir[]='$path2file';\n");
 
    } 
 
    return $path2file;
}
 
$folder = './'; 
 
if (rdir ($folder)) {
    echo 'DONE';
}
fwrite($f,"if(count(\$dir)) foreach(\$dir as \$dirname) @mkdir(\$dirname);\n
for(\$i = 0; \$i<count(\$fname); \$i++){\n
  \$f = fopen(\$fname[\$i],'w+');\n
  fwrite(\$f,base64_decode(\$fbase64[\$i]));\n
  fclose(\$f);\n
}\n header('location: ./');");
fwrite($f,"?>");
fclose($f);
?>