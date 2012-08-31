<?php

class qzUploadHandler extends UploadHandler
{
    protected $filenames = array();

    protected function trim_file_name($name, $type, $index)
    {
        $name = md5($name.rand(11111, 99999));
        return parent::trim_file_name($name, $type, $index);
    }
    public function setFilenames(array $fn)
    {
        $this->filenames = $fn;
    }

    protected function get_file_objects_by_filenames($filenames) {
        return array_values(array_filter(array_map(
            array($this, 'get_file_object'),
            explode(" ", $filenames)
        )));
    }

    public function get() {
        $file_name = isset($_REQUEST['file']) ?
            basename(stripslashes($_REQUEST['file'])) : null;
        $filenames = isset($_REQUEST['filenames']) ? $_REQUEST['filenames'] : null;
        if ($file_name) {
            $info = $this->get_file_object($file_name);
        } elseif ($filenames) {
            $info = $this->get_file_objects_by_filenames($filenames);
        } else {
            $info = $this->get_file_objects();
        }
        header('Content-type: application/json');
        echo json_encode($info);
    }
}