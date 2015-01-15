<?php

    if (!empty($_FILES)){
        $tempFile = $_FILES['doptg_image']['tmp_name'];
        $targetPath = $_GET['path'].'uploads';
        $ext = substr($_FILES['doptg_image']['name'], strrpos($_FILES['doptg_image']['name'], '.') + 1);

        if (strtolower($ext) == 'png' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'jpg'){
            $len = 64;
            $base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
            $max=strlen($base)-1;
            $newName='';
            mt_srand((double)microtime()*1000000);
            
            while (strlen($newName)<$len+1){
                $newName.=$base{mt_rand(0,$max)};
            }

            $targetFile =  str_replace('//','/',$targetPath).'/'.$newName.'.'.$ext;
            
            // File and new size
            $filename = $targetPath.'/'.$newName.'.'.$ext;

            // Get new sizes
            list($width, $height) = getimagesize($tempFile);
            
            if ($width != 0 && $height != 0 && $width != '' && $height != ''){
                move_uploaded_file($tempFile, $targetFile);
                
                $newheight = 300;
                $newwidth = $width*$newheight/$height;

                if ($newwidth < 300){
                    $newwidth = 300;
                    $newheight = $height*$newwidth/$width;
                }

                // Load
                $thumb = ImageCreateTrueColor($newwidth, $newheight);
                
                if ($ext == 'png'){
                    imagealphablending($thumb, false);
                    imagesavealpha($thumb, true);  
                }
                
                if ($ext == 'png'){
                    $source = imagecreatefrompng($filename);
                    imagealphablending($source, true);
                }
                else{
                    $source = imagecreatefromjpeg($filename);
                }

                // Resize
                imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                // Output
                if ($ext == 'png'){
                    $source = imagepng($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext);
                }
                else{
                    $source = imagejpeg($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext, 100);
                }

                echo '<script language="javascript" type="text/javascript">window.top.window.doptgUploadImageSuccess("'.$newName.'.'.$ext.'");</script>';
            }
            else{
                echo '<script language="javascript" type="text/javascript">window.top.window.doptgUploadImageSuccess("-1");</script>';                   
            }
        }
        else{
            echo '<script language="javascript" type="text/javascript">window.top.window.doptgUploadImageSuccess("-1");</script>';            
        }
    }

?>