<?php
/**
 * @Author       : Jinghua Fan
 * @Date         : 2022-01-18 13:58:11
 * @LastEditors  : Jinghua Fan
 * @LastEditTime : 2022-01-22 13:40:45
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
        $disk = $this->disk();
        // 判断是否是删除文件请求
        if ($this->isDeleteRequest()) {
            // 删除文件并响应
            return $this->deleteFileAndResponse($disk);
        }

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
        $disk = $this->disk();
        // 判断是否是删除文件请求
        if ($this->isDeleteRequest()) {
            // 删除文件并响应
            return $this->deleteFileAndResponse($disk);
        }
        $file = $request->file;
        $newName = md5($file->getClientOriginalName() . time() . rand()) . '.' . $file->getClientOriginalExtension();
        $dir = 'tinymce/video';

        $result = $disk->putFileAs($dir, $file, $newName);

        $path = "{$dir}/$newName";
        return $result
        ? response()->json(['location' => $disk->url($path)])
        :
        $this->responseErrorMessage('文件上传失败');
    }
}
