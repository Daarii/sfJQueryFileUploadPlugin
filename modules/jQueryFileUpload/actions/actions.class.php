<?php

/**
 * autozar actions.
 *
 * @package    sfJQueryFileUploadPlugin
 * @subpackage jQueryFileUpload module
 * @author     Enkuso
 * @version    1.0.0
 */
class jQueryFileUploadActions extends sfActions
{
    public function executeUploadAjax(sfWebRequest $request)
    {
        $this->forward404Unless($request->isXmlHttpRequest());

        $host = $request->isSecure()? 'https://':'http://';
        $host .= $request->getHost() . $request->getRelativeUrlRoot();

        $upload_handler = new qzUploadHandler(array(

            'script_url' => $this->generateUrl('jquery_file_upload_ajax',array(),true),
            'upload_dir' => sfConfig::get('sf_web_dir').'/sfJQueryFileUploadPlugin/uploads/files/',
            'upload_url' => $host.'/sfJQueryFileUploadPlugin/uploads/files/',
            'image_versions' => array(
                // define thumbnail settings here
                /*
                'large' => array(
                    'upload_dir' => sfConfig::get('sf_web_dir').'/sfJQueryFileUploadPlugin/uploads/large/',
                    'upload_url' => $host.'/sfJQueryFileUploadPlugin/large/400x300/',
                    'max_width' => 400,
                    'max_height' => 300,
                    'jpeg_quality' => 95
                ),
                */

                'thumbnail' => array(
                    'upload_dir' => sfConfig::get('sf_web_dir').'/sfJQueryFileUploadPlugin/uploads/thumbnails/',
                    'upload_url' => $host.'/sfJQueryFileUploadPlugin/uploads/thumbnails/',
                    'max_width' => 80,
                    'max_height' => 80
                )
            )
        ));
        switch ($request->getMethod()) {
            case 'OPTIONS':
                break;
            case 'HEAD':
            case 'GET':
                $upload_handler->get();
                break;
            case 'PUT':
            case 'POST':
                if ($request->isMethod(sfRequest::DELETE)) {
                    $upload_handler->delete();
                } else {
                    $upload_handler->post();
                }
                break;
            case 'DELETE':
                $upload_handler->delete();
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
        }
        return sfView::NONE;
    }
}