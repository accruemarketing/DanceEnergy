<?php
/*
Uploadify v3.0.0
Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

    if (!empty($_FILES)){
        $tempFile = $_FILES['Filedata']['tmp_name'];
        $targetPath = $_GET['path'].'uploads';

        $ext = substr($_FILES['Filedata']['name'], strrpos($_FILES['Filedata']['name'], '.') + 1);
        
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

                echo $newName.'.'.$ext;
            }
            else{
                echo '-1';                
            }
        }
        else{
            echo '-1';
        }
    }

?>