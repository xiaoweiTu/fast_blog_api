<?php
/**
 *
 * User: xiaowei<13177839316@163.com>
 * Date: 2020/1/21
 * Time: 17:02
 */
namespace App\Services;

use App\Exception\WrongRequestException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Hyperf\Redis\Redis;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class UploadService {
    /**
     * @var Auth
     */
    protected $auth;
    protected $bucket;
    const QI_NIU_TOKEN = 'qi:niu:up:token';
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

    public function __construct() {
        $access       = config('qiniu.access_ey');
        $secret       = config('qiniu.secret_key');
        $this->bucket = config('qiniu.bucket');
        $this->auth   = new Auth($access, $secret);
    }

    /**
     * @param UploadedFile $image
     *
     * @return mixed
     * @throws \Exception
     */
    public function image(UploadedFile $image) {
        if (empty($image)) {
            throw new WrongRequestException("未获取到上传的文件!");
        }
        $extension = $image->getExtension();
        $filePath  = $this->getFileName($extension);
        if (!$image->isValid()) {
            throw new WrongRequestException('图片保存失败!');
        }
        $image->moveTo($filePath);
        if ($image->isMoved()) {
            $upToken = $this->redis->get(self::QI_NIU_TOKEN);
            if (empty($upToken)) {
                $upToken = $this->auth->uploadToken($this->bucket, null, 3600);
                $this->redis->setex(self::QI_NIU_TOKEN, 3600, $upToken);
            }
            $uploadMgr = new UploadManager();
            list($ret, $err) = $uploadMgr->putFile($upToken, null, $filePath);
        }
        // 异步删除本地文件
        go(function () use ($filePath){
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        });
        if ($err !== null) {
            throw new WrongRequestException("上传图片失败!");
        }
        return config('cdn') . '/' . $ret['hash'];
    }

    public function getFileName($extension) {
        $localPath = BASE_PATH . '/storage/images/';
        $fileName  = date('YmdHis') . round(0, 999999) . '.' . $extension;
        return $localPath . $fileName;
    }
}
