<?php
/**
 * @Author       : fanjinghua
 * @LastEditors  : fanjinghua
 * @LastEditTime : 2022/1/18 13:58
 * @Description  : 佛祖保佑,永无BUG
 */

namespace PandaOreo\Tinymce\Http\Controllers;


use Dcat\Admin\Traits\HasUploadedFile;
use Illuminate\Http\Request;

class FileUploadController
{
    use HasUploadedFile;

    public function handle(Request $request)
    {
        $disk = $this->disk('admin');

        // 获取上传的文件
        $file = $request->file;
        $dir = 'tinymce/images';
        $newName = md5($file->getClientOriginalName() . time() . rand()) . '.' . $file->getClientOriginalExtension();

        $result = $disk->putFileAs($dir, $file, $newName);

        $path = "{$dir}/$newName";
        return $result
            ? response()->json(['location' => $disk->url($path)])
            :
            $this->responseErrorMessage('文件上传失败');
    }

    public function uploadVideo(Request $request)
    {
        $file = $request->file;
        $newName = md5($file->getClientOriginalName() . time() . rand()) . '.' . $file->getClientOriginalExtension();
        $dir = 'tinymce/video';
        $disk = $this->disk('admin');
        $result = $disk->putFileAs($dir, $file, $newName);

        $path = "{$dir}/$newName";
        return $result
            ? response()->json(['location' => $disk->url($path)])
            :
            $this->responseErrorMessage('文件上传失败');
    }
}
