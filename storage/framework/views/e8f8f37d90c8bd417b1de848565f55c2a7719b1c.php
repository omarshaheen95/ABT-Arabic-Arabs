

<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/froala_editor.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/froala_style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/code_view.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/image_manager.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/image.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/table.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/video.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('editor/css/plugins/colors.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">
    <style>
        .leftDirection {
            direction: ltr !important;
        }

        .rightDirection {
            direction: rtl !important;
        }
    </style>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('breadcrumb'); ?>
    <li class="breadcrumb-item b text-muted">
        <?php echo e($title); ?>

    </li>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(isset($story) ? route('manager.story.update', $story->id): route('manager.story.store')); ?>"
          method="post" class="form" id="form-data" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($story)): ?>
            <?php echo method_field('PATCH'); ?>
        <?php endif; ?>
        <div class="row">
            <!--begin::Image input-->
            <div class="col-12 d-flex flex-column align-items-center mb-5">
                <div><?php echo e(t('Image')); ?></div>
                <div class="image-input image-input-outline" data-kt-image-input="true"
                     style="background-image: url(/manager_assets/media/svg/avatars/blank.svg)">

                    <?php if(isset($story) && $story->image ): ?>
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(<?php echo e(asset($story->image)); ?>)"></div>

                    <?php else: ?>
                        <div class="image-input-wrapper w-125px h-125px"
                             style="background-image: url(/assets_v1/media/svg/avatars/blank.svg)"></div>
                    <?php endif; ?>

                    <!--begin::Edit button-->
                    <label
                            class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="change"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Change avatar">
                        <i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

                        <!--begin::Inputs-->
                        <input type="file" name="image" accept=".png, .jpg, .jpeg"/>
                        <input type="hidden" name="avatar_remove"/>
                        <!--end::Inputs-->
                    </label>
                    <!--end::Edit button-->

                    <!--begin::Cancel button-->
                    <span
                            class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="cancel"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Cancel avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Cancel button-->

                    <!--begin::Remove button-->
                    <span
                            class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                            data-kt-image-input-action="remove"
                            data-bs-toggle="tooltip"
                            data-bs-dismiss="click"
                            title="Remove avatar">
                <i class="ki-outline ki-cross fs-3"></i>
            </span>
                    <!--end::Remove button-->
                </div>
            </div>
            <!--end::Image input-->

            <div class="row">
                    <div class="col-6 mb-2">
                        <div class="form-group">
                            <label for="name" class="form-label"><?php echo e(t('Name')); ?></label>
                            <input type="text" id="" name="name" class="form-control"
                                   placeholder="<?php echo e(t('Name')); ?>"
                                   value="<?php echo e(isset($story) ? $story->name : old("name")); ?>"
                                   required>
                        </div>
                    </div>


                <div class="col-6 mb-2">
                    <div class="form-group">
                        <label for="grade" class="form-label"><?php echo e(t('Grade')); ?></label>
                        <select name="grade" class="form-select get_levels" data-control="select2"
                                data-placeholder="<?php echo e(t('Select Grade')); ?>" data-allow-clear="true">
                            <option></option>
                            <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade=>$name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php echo e(isset($story) && $story->grade == $grade ? 'selected':''); ?> value="<?php echo e($grade); ?>"><?php echo e($name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                    </div>
                </div>

                <div class="col-6">
                    <div class="d-flex">
                        <label class="mb-2"><?php echo e(t('Video')); ?> :</label>
                        <?php if(isset($story) && $story->video): ?>
                            <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                <a data-type="video" class="btn btn-icon btn-danger deleteFile" style="height: 20px; width: 20px">
                                    <i class="la la-close la-2"></i>
                                </a>
                                <a href="<?php echo e(asset($story->video)); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                    <i class="la la-eye la-2"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                    <input type="file" name="video" class="form-control">
                </div>

                <div class="col-6">
                    <div class="d-flex">
                        <label class="mb-2"><?php echo e(t('Alternative video')); ?> :</label>
                        <?php if(isset($story) && $story->alternative_video): ?>
                            <div class="ms-auto d-flex flex-row align-items-center gap-1 pb-1">
                                <a data-type="alternative_video" class="btn btn-icon btn-danger deleteFile" style="height: 20px; width: 20px">
                                    <i class="la la-close la-2"></i>
                                </a>
                                <a href="<?php echo e(asset($story->alternative_video)); ?>" target="_blank"  class="btn btn-icon btn-success ml-2" style="height: 20px; width: 20px">
                                    <i class="la la-eye la-2"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                    <input type="file" name="alternative_video" class="form-control">
                </div>

                <div class="col-6 mb-2 d-flex gap-2 mt-4">
                    <div class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name="active"
                               id="flexCheckDefault" <?php echo e(isset($story) && $story->active ? 'checked':''); ?>/>
                        <label class="form-check-label text-dark" for="flexCheckDefault">
                            <?php echo e(t('Active')); ?>

                        </label>
                    </div>
                </div>
                <div class="col-12 mb-2 mt-5">
                    <div class="form-group">
                        <label for="alternative_video" class="form-label"><?php echo e(t('Content')); ?>

                        </label>
                        <textarea class="form-control summernote edit" id='edit' style="margin-top: 30px;"
                                  name="content"><?php echo e(isset($story->content) ? $story->getOriginal('content') : old("content")); ?></textarea>
                    </div>
                </div>


            </div>
            <div class="row my-5">
                <div class="separator separator-content my-4"></div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-primary mr-2"><?php echo e(isset($story)?t('Update'):t('Save')); ?></button>
                </div>
            </div>
        </div>

    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('vendor/jsvalidation/js/jsvalidation.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <?php echo JsValidator::formRequest(\App\Http\Requests\Manager\StoryRequest::class, '#form-data'); ?>

    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/froala_editor.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/align.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/code_beautifier.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/code_view.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/draggable.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/image.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/image_manager.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/link.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/lists.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/paragraph_format.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/paragraph_style.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/table.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/video.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/url.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/entities.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/colors.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/font_size.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/font_family.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('editor/js/plugins/word_paste.min.js')); ?>"></script>
    <script>
        <?php if(isset($story)): ?>
        $(document).on('click', '.deleteFile', (function () {
            let type = $(this).data('type');
            let url = "<?php echo e(route('manager.story.remove-attachment',['id'=>$story->id,'type'=>':type'])); ?>".replace(':type',type);
            let parent = $(this).parent();

            showAlert("<?php echo e(t('Delete Attachment')); ?>","<?php echo e(t('Are you sure to for deleting process?')); ?>",'warning',
                true,true,function (callback) {
                    if (callback){
                        showLoadingModal()
                        $.ajax({
                            url: url,
                            type: 'post',
                            data: {'_token':"<?php echo e(csrf_token()); ?>"},
                            success: function(response){
                                hideLoadingModal()
                                parent.remove()
                                toastr.success(response.message);
                            },
                            error(error){
                                hideLoadingModal()
                                toastr.error(error.responseJSON.message);
                            }
                        });
                    }
                })
        }));
        <?php endif; ?>

        (function () {
            const editorInstance = new FroalaEditor('.edit', {
                key: "4NC5fB4D4H4D3F3B3D5D-13tsrH4iggC7jE-11llA-8G1rA-21C-16hE5B4E4H3F2H3B8A4C4E5==",
                fontFamilySelection: true,
                heightMin: 500,
                paragraphStyles: {
                    leftDirection: 'Left Direction',
                    rightDirection: 'Right Direction'
                },
                toolbarButtons: {
                    'moreText': {
                        'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
                    },
                    'moreParagraph': {
                        'buttons': ['alignLeft', 'alignCenter', 'paragraphStyle', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'formatOLSimple', 'lineHeight', 'outdent', 'indent', 'quote']
                    },
                    'moreRich': {
                        'buttons': ['insertLink', 'insertImage', 'insertVideo', 'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
                    },
                    'moreMisc': {
                        'buttons': ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help']
                    }
                },
                fontFamily: {
                    'Arial,Helvetica,sans-serif': 'Arial',
                    'Georgia,serif': 'Georgia',
                    'Impact,Charcoal,sans-serif': 'Impact',
                    'Tahoma,Geneva,sans-serif': 'Tahoma',
                    "'Times New Roman',Times,serif": 'Times New Roman',
                    'Verdana,Geneva,sans-serif': 'Verdana'
                },
                enter: FroalaEditor.ENTER_P,
                placeholderText: null,

                colorsStep: 6,
                colorsText: [
                    '#15E67F', '#E3DE8C', '#D8A076', '#D83762', '#76B6D8', 'REMOVE',
                    '#1C7A90', '#249CB8', '#4ABED9', '#FBD75B', '#FBE571', '#FFFFFF', '#000000'
                ],
                toolbarButtonsXS: [['undo', 'redo'], ['bold', 'italic', 'underline']],
                fontSize: ['8', '10', '12', '14', '18', '22', '24', '28', '32', '36', '40', '44', '48', '52', '56', '60', '96'],
                // Set the image upload parameter.
                imageUploadParam: 'imageFile',

                // Set the image upload URL.
                imageUploadURL: '<?php echo e(route('uploadImageLesson')); ?>',

                // Additional upload params.
                imageUploadParams: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                },

                // Set request type.
                imageUploadMethod: 'POST',

                // Set max image size to 5MB.
                imageMaxSize: 5 * 1024 * 1024,

                // Allow to upload PNG and JPG.
                imageAllowedTypes: ['jpeg', 'jpg', 'png'],

                events: {
                    initialized: function () {
                        const editor = this
                        this.el.closest('form').addEventListener('submit', function (e) {
                            console.log(editor.$oel.val());
                        })
                    },
                    'image.beforeUpload': function (images) {

                        console.log("// Return false if you want to stop the image upload.");
                    },
                    'image.uploaded': function (response) {

                        console.log("// Image was uploaded to the server.");
                    },
                    'image.inserted': function ($img, response) {

                        console.log("// Image was inserted in the editor.");
                    },
                    'image.replaced': function ($img, response) {

                        console.log("// Image was replaced in the editor.");
                    },
                    'image.error': function (error, response) {
                        // Bad link.
                        if (error.code == 1) {
                            console.log(error + " - " + error.code);
                        }

                        // No link in upload response.
                        else if (error.code == 2) {
                            console.log(error + " - " + error.code);
                        }

                        // Error during image upload.
                        else if (error.code == 3) {
                            console.log(error + " - " + error.code);
                        }

                        // Parsing response failed.
                        else if (error.code == 4) {
                            console.log(error + " - " + error.code);
                        }

                        // Image too text-large.
                        else if (error.code == 5) {
                            console.log(error + " - " + error.code);
                        }

                        // Invalid image type.
                        else if (error.code == 6) {
                            console.log(error + " - " + error.code);
                        }

                        // Image can be uploaded only to same domain in IE 8 and IE 9.
                        else if (error.code == 7) {
                            console.log(error + " - " + error.code);
                        }

                        // Response contains the original server response to the request if available.
                    }
                }
            })
        })()
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('manager.layout.container', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/omar/websites/arabic-arabs/resources/views/manager/story/edit.blade.php ENDPATH**/ ?>