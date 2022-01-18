<script src="../vendor/dcat-admin-extensions/panda-oreo/tinymce/js/tinymce/tinymce.min.js"></script>

<div class="{{$viewClass['form-group']}}">

    <label class="{{$viewClass['label']}} control-label">{!! $label !!}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <textarea class="form-control {{$class}}" name="{{$name}}"
                  placeholder="{{ $placeholder }}" {!! $attributes !!}>{{ $value }}</textarea>

        @include('admin::form.help-block')

    </div>
</div>

<script require="@tinymce" init="{!! $selector !!}">
    var opts = {!!admin_javascript_json($options) !!};
    var iuh = {!!admin_javascript_json($images_upload_handler) !!};
    var upurl1 = {!!admin_javascript_json($upurl) !!};

    opts.selector = '#' + id;

    if (!opts.init_instance_callback) {
        opts.init_instance_callback = function (editor) {
            editor.on('Change', function (e) {
                $this.val(String(e.target.getContent()).replace('<p><br data-mce-bogus="1"></p>', '').replace('<p><br></p>', ''));
            });
        }
    }
    opts.images_upload_handler = function (blobInfo, success, failure, progress) {
        var xhr, formData;
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', iuh);

        // xhr.upload.onprogress = function (e) {
        //     progress(e.loaded / e.total * 100);
        // }
        xhr.onload = function () {
            var json;
            if (xhr.status == 403) {
                failure('HTTP Error: ' + xhr.status, {remove: true});
                return;
            }
            if (xhr.status < 200 || xhr.status >= 300) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            json = JSON.parse(xhr.responseText);
            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
            success(json.location);
        };
        xhr.onerror = function () {
            failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
        }
        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.blob().name);

        xhr.send(formData);
    }
    opts.file_picker_callback = function (callback, value, meta) {
//文件分类
        var filetype = '.pdf, .txt, .zip, .rar, .7z, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .mp3, .mp4';
        //后端接收上传文件的地址
        var upurl = upurl1;
        //为不同插件指定文件类型及后端地址
        switch (meta.filetype) {
            case 'image':
                filetype = '.jpg, .jpeg, .png, .gif';
                upurl = iuh;
                break;
            case 'media':
                filetype = '.mp3, .mp4';
                upurl = upurl1;
                break;
            case 'file':
            default:
        }
        //模拟出一个input用于添加本地文件
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', filetype);
        input.click();
        input.onchange = function () {
            var file = this.files[0];
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', upurl1);
            xhr.onload = function () {
                var json;
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    // failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                callback(json.location);
            };
            formData = new FormData();
            formData.append('file', file, file.name);
            xhr.send(formData);
        }
    }
    tinymce.init(opts)
</script>
