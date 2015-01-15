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
        $data = explode(';;', $_GET['data']);
        $tempFile = $_FILES['Filedata']['tmp_name'];
        $targetPath = $data[0].$data[1];

        $ext = substr($_FILES['Filedata']['name'], strrpos($_FILES['Filedata']['name'], '.') + 1);

        if (strtolower($ext) == 'png' || strtolower($ext) == 'jpeg' || strtolower($ext) == 'jpg' || strtolower($ext) == 'gif'){
            $targetFile =  str_replace('//','/',$targetPath).'/'.$data[2].'.'.$ext;
            list($width, $height) = getimagesize($tempFile);
                        
            if ($width != 0 && $height != 0 && $width != '' && $height != ''){
                move_uploaded_file($tempFile, $targetFile);
                echo $data[1].$data[2].'.'.$ext;
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