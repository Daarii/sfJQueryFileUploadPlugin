<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Enkuso
 * Date: 8/30/12
 * Time: 1:47 PM
 * To change this template use File | Settings | File Templates.
 */

class sfWidgetFormJQueryFileUpload extends sfWidgetFormInput
{
    /**
     * Constructor.
     *
     * Available options:
     *
     *  * type: The widget type
     *
     * @param array $options     An array of options
     * @param array $attributes  An array of default HTML attributes
     *
     * @see sfWidgetForm
     */
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        
        $this->addOption('url');

        $this->setOption('type', 'hidden');
        $this->setOption('url', sfContext::getInstance()->getRouting()->generate('jquery_file_upload_ajax',array(),true));
        
        $this->setOption('needs_multipart', true);
    }

    /**
     * Renders the widget.
     *
     * @param  string $name        The element name
     * @param  string $value       The value displayed in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $id = $this->generateId($name);
        $url = $this->getOption('url');
        /*
        $obj_id = $this->getOption('objid');
        $load = "// Load existing files:
        $('#fileupload').each(function () {
            var that = this;
            $.getJSON('$url?id=$obj_id&filenames='+$('#$id').val(), function (result) {
                if (result && result.length) {
                    $(that).fileupload('option', 'done')
                        .call(that, null, {result: result});
                }
            });
        });";
        */
        $load = "// Load existing files:
        $('#fileupload').each(function () {
            var that = this;
            $.getJSON('$url?filenames='+$('#$id').val(), function (result) {
                if (result && result.length) {
                    $(that).fileupload('option', 'done')
                        .call(that, null, {result: result});
                }
            });
        });";
        $html = <<<EOF
           <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar">
        <div class="span2">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <i class="icon-plus icon-white"></i>
                <input type="file" name="files[]" multiple>
            </span>
            <button type="button" class="btn btn-danger delete">
                <i class="icon-trash icon-white"></i>
            </button>
            <label class="checkbox"><input type="checkbox" class="toggle">Бүх зураг сонгох</label>
        </div>
        <!-- The global progress information -->
        <div class="span5 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="bar" style="width:0%;"></div>
            </div>
            <!-- The extended global progress information -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>
    <!-- The loading indicator is shown during file processing -->
    <div class="fileupload-loading"></div>
    <br>
    <!-- The table listing the files available for upload/download -->
    <!--<table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>-->
    <div role="presentation" class="table table-striped"><div class="files row"></div></div>

    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <div class="template-download span1 fade">
            {% if (file.error) { %}
            <div class="error"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
            {% } else { %}
            <div class="preview">{% if (file.thumbnail_url) { %}
                <img src="{%=file.thumbnail_url%}" onmouseover="$('.delete',$(this).parent().parent()).css('opacity','0.9');" onmouseout="$('.delete',$(this).parent().parent()).css('opacity','0.1');">
                {% } %}</div>
            {% } %}
            <div class="delete"  onmouseover="$(this).css('opacity','0.9');" onmouseout="$(this).css('opacity','0.1');">
                <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                    <i class="icon-trash icon-white"></i>
                </button>
                <input type="checkbox" name="delete" value="1">
            </div>
        </div>
        {% } %}
    </script>
    <!-- The template to display files available for upload -->
    <script id="template-upload" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
        <div class="template-upload span1 fade">
            <div class="preview"><span class="fade"></span></div>
            {% if (file.error) { %}
            <div class="error"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</div>
            {% } else if (o.files.valid && !i) { %}
            <div>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </div>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span>{%=locale.fileupload.start%}</span>
                </button>
                {% } %}</td>
            {% } else { %}
            {% } %}
            <div class="cancel">{% if (!i) { %}
                <button class="btn btn-warning">
                    <i class="icon-ban-circle icon-white"></i>
                </button>
                {% } %}</div>
        </div>
        {% } %}
    </script>

    <script type="text/javascript">
    $(function () {
        'use strict';

        // Set id to form
        $('#$id').closest("form").attr('id','fileupload');
        
        // Initialize the jQuery File Upload widget:
        $('#fileupload').fileupload();

        // Enable iframe cross-domain access via redirect option:
        $('#fileupload').fileupload(
            'option',
            'redirect',
            window.location.href.replace(
                /\/[^\/]*$/,
                '/cors/result.html?%s'
            )
        );

        // Demo settings:
        $('#fileupload').fileupload('option', {
            autoUpload: true,
            previewMaxWidth: 60,
            maxNumberOfFiles: 10,
            url: '$url',
            sequentialUploads: true,
            maxFileSize: 5000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            process: [
                {
                    action: 'load',
                    fileTypes: /^image\/(gif|jpeg|png)$/,
                    maxFileSize: 20000000 // 20MB
                },
                {
                    action: 'resize',
                    maxWidth: 1440,
                    maxHeight: 900
                },
                {
                    action: 'save'
                }
            ]
        });

        $('#fileupload')
            .bind('fileuploaddestroy', function (e, data) {
                var results = new RegExp('[\?&]' + 'file' + '=([^&#]*)').exec(data.url)
                if(results !== null){
                    var filenames = $('#$id').val();
                    filenames = filenames.replace(' '+results[1],'');
                    $('#$id').val(filenames);
                }
            })
            .bind('fileuploadcompleted', function (e, data) {
                console.debug(data);
                $.each(data.result, function(index, value){
                    var curstr = $('#$id').val();
                    if(curstr.indexOf(value.name) < 0)
                        $('#$id').val(curstr+' '+value.name);
                });
            });

        $load

    });
    </script>
EOF;

        return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes)).$html;
    }
    
  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * The array keys are files and values are the media names (separated by a ,):
   *
   *   array('/path/to/file.css' => 'all', '/another/file.css' => 'screen,print')
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array(
        public_path('/sfJQueryFileUploadPlugin/css/jquery.fileupload-ui.css') => 'all',
    );
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    $path = public_path('/sfJQueryFileUploadPlugin/js');
    return array(
        $path . '/jquery-ui-widget.min.js',
        $path . '/tmpl.min.js',
        $path . '/load-image.min.js',
        $path . '/canvas-to-blob.min.js',
        $path . '/bootstrap-image-gallery.min.js',
        $path . '/jquery.iframe-transport.js',
        $path . '/jquery.fileupload.js',
        $path . '/jquery.fileupload-fp.js',
        $path . '/jquery.fileupload-ui.js',
        $path . '/locale.js',
    );
  }

}