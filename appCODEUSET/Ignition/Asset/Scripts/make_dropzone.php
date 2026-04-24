<?php
/*********************************************************************
    AUTHOR:      Mark E. Williamson
    FILE:        make_dropzone.php
    VERSION:     See BaseController.php
    DESCRIPTION: Displays a form using Ignition class
    COPYRIGHT:   2022
    FIRST REV:   16 Mar 2022
    LICENSE:     MIT

*********************************************************************/

    $baseDirectory = (isset($baseDirectory) ? $baseDirectory : $model->subDirectory);
?>

    <!-- begin photo uploads -->
    <div class="panel-body clearfix" style="border:2px solid #008413; padding: 10px;">
	        <!-- The fileinput-button span is used to style the file input field as button -->
        <center>
            <br>
            <button type="button" class="btn btn-default fileinput-button">
            <i class="fa fa-plus"></i> <?= lang($model->butttonText) ?></button>
            <br>
            <?= lang('base.click_drag') ?>
        </center>
        <!-- dropzone -->
        <div class="row">
            <div id="actions" class="col-xs-12">
                <div class="col-lg-7"></div>
                <div class="col-lg-5">
                    <!-- The global file processing state -->
                    <div class="fileupload-process">
                        <div id="total-progress" class="progress progress-striped active"
                             role="progressbar"
                             aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%;"
                                 data-dz-uploadprogress>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="previews" class="table table-condensed files no-margin">
                    <div id="template" class="file-row">
                        <!-- This is used as the file preview template -->
                        <div>
                            <span class="preview">
                                <img data-dz-thumbnail/>
                            </span>
                        </div>
                        <div>
                            <p class="name" data-dz-name>
                            </p>
                            <strong class="error text-danger" data-dz-errormessage>
                            </strong>
                        </div>
                        <div>
                            <p class="size" data-dz-size>
                            </p>
                            <div class="progress progress-striped active"
                                 role="progressbar" aria-valuemin="0"
                                 aria-valuemax="100" aria-valuenow="0">
                                <div class="progress-bar progress-bar-success"
                                     style="" data-dz-uploadprogress>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group">
                            <button data-dz-download class="btn btn-sm btn-primary">
                                <i class="fa fa-download"></i>
                                <span><?= lang('base.download')?></span>
                            </button>
                            <button data-dz-remove class="btn btn-danger btn-sm delete">
                                <i class="fa fa-trash-o"></i>
                                <span><?= lang('base.delete')?></span>
                            </button>
                            <button target="_blank" data-dz-cliptext class="btn btn-sm btn-primary">
                                <i class="fa fa-download"></i>
                                <span><?= lang('base.get_link')?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- stop dropzone -->
    </div>
    <!-- end file uploads -->

<script>
    function getIcon(fullname) {
        var fileFormat = fullname.match(/\.([A-z0-9]{1,5})$/);
        if (fileFormat) {
            fileFormat = fileFormat[1];
        }
        else {
            fileFormat = '';
        }

        var fileIcon = 'default';

        switch (fileFormat) {
            case 'pdf':
                fileIcon = 'file-pdf';
                break;
            case 'mp3':
            case 'wav':
            case 'ogg':
                fileIcon = 'file-audio';
                break;
            case 'doc':
            case 'docx':
            case 'odt':
                fileIcon = 'file-document';
                break;
            case 'xls':
            case 'xlsx':
            case 'ods':
                fileIcon = 'file-spreadsheet';
                break;
            case 'ppt':
            case 'pptx':
            case 'odp':
                fileIcon = 'file-presentation';
                break;
        }
        return fileIcon;
    }

    // ----- get the template HTML and remove it from the document
    var previewNode = document.querySelector('#template');
    previewNode.id = '';
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    var fileDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
        <?php // the next line line causes the uploaded files to display on page load 
              // This is because when call upload without $_FILES, jumps to GetFiles
        ?>
        url: "<?= BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/getfiles/'. $baseDirectory . '/'  . $model->data[$model->IDField])?>",
        params: {
        },
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        uploadMultiple: false,
        previewTemplate: previewTemplate,
        autoQueue: true, // Make sure the files aren't queued until manually added
        previewsContainer: '#previews', // Define the container to display the previews
        clickable: '.fileinput-button', // Define the element that should be used as click trigger to select files.
        init: function () {
            thisDropzone = this;
            $.getJSON("<?= BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/getfiles/'. $baseDirectory . '/'  . $model->data[$model->IDField])?>",
                // ------ setup loop to print file info
                function (data) {
                    $.each(data, function (index, val) {
                        var mockFile = {fullname: val.fullname, size: val.size, name: val.name};

                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                        createDownloadButton(mockFile, "<?= BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/getfile/' . $baseDirectory) ?>" + '/' + val.fullname);
                        createClipTextButton(mockFile, "<?= BaseURL('/assets/' . $baseDirectory) ?>" + '/' + val.fullname);

                        if (val.fullname.match(/\.(jpg|jpeg|png|gif)$/)) {
                            thisDropzone.options.thumbnail.call(thisDropzone, mockFile,
                                "<?= BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/getfile/' . $baseDirectory) ?>" + '/' + val.fullname);
                        }
                        else {
                            thisDropzone.options.thumbnail.call(thisDropzone, mockFile,
                                "<?= BaseURL('favicon-32x32.png') ?>");
                        }

                        thisDropzone.emit('complete', mockFile);
                        thisDropzone.emit('success', mockFile);
                    });
                });
        },
    });

    // ----- add download and copy cliptext buttons
    fileDropzone.on('addedfile', function (file) {
        fileDropzone.emit('thumbnail', file, "<?= BaseURL('favicon-32x32.png') ?>");
        createDownloadButton(file, '<?= BaseURL("/asset" . $GLOBALS["KEYCODE"] . "/getfile/" . $baseDirectory) ?>' + '/' + '<?= $model->data[$model->IDField] ?>' + '-' + file.name);
        createClipTextButton(file, '<?= BaseURL("/assets/" . $baseDirectory) ?>' + '/' + '<?= $model->data[$model->IDField] ?>' + '-' + file.name);
    });

    // ----- update the total progress bar
    fileDropzone.on('totaluploadprogress', function (progress) {
        document.querySelector('#total-progress .progress-bar').style.width = progress + '%';
    });

    // ----- show the total progress bar when upload starts
    fileDropzone.on('sending', function (file) {
        document.querySelector('#total-progress').style.opacity = '1';
    });

    // ----- hide the total progress bar when nothing's uploading anymore
    fileDropzone.on('queuecomplete', function (progress) {
        document.querySelector('#total-progress').style.opacity = '0';
    });

    fileDropzone.on('removedfile', function (file) {
        $.post({
            url: "<?= BaseURL('/asset' . $GLOBALS['KEYCODE'] . '/deletefile/'. $baseDirectory)?>" + '/' + file.fullname,
            data: {
                name: file.fullname,
            }
        });
    });

    function createDownloadButton(file, fileUrl) {
        var downloadButtonList = file.previewElement.querySelectorAll('[data-dz-download]');
        for (var $i = 0; $i < downloadButtonList.length; $i++) {
            downloadButtonList[$i].addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                location.href = fileUrl;
                return false;
            });
        }
    }

    function createClipTextButton(file, fileUrl) {
        var clipTextButtonList = file.previewElement.querySelectorAll('[data-dz-cliptext]');
        for (var $i = 0; $i < clipTextButtonList.length; $i++) {
            clipTextButtonList[$i].addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                location.href = fileUrl;

                //var copyText = document.getElementById(data-dz-name);
                //copyText.select();
                //copyText.setSelectionRange(0, 99999); /* For mobile devices */
                //e.navigator.clipboard.writeText("test");
                //alert("Copied the text: " + copyText.value);

                return false;
            });
        }
    }

</script>
