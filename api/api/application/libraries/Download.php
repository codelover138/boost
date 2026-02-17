<?php

class Download
{
    public function get_file($file)
    {
        $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        $download_path = FCPATH . 'assets/' . $file_ext . '/' . $file;

        if(file_exists($download_path))
        {
            $quoted = sprintf('"%s"', addcslashes(basename($download_path), '"\\'));
            $size = filesize($download_path);

            if (!headers_sent()) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/' . $file_ext);
                header('Content-Disposition: attachment; filename=' . $quoted);
                header('Content-Transfer-Encoding: binary');
                header('Connection: Keep-Alive');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . $size);
                readfile($download_path); # push the file out
            }
        }
        else
        {
            echo 'The file you are looking for does not exist.';
        }
    }
}