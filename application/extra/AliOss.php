<?php
declare(strict_types = 1);

namespace app\extra;

use OSS\OssClient;
use OSS\Core\OssException;

/**
 *阿里云oss文件上传
 */
class AliOss
{
    const endpoint        = 'oss-cn-beijing.aliyuncs.com';
    const accessKeyId     = 'LTAI4GEkHCPd2YfxdPftFhzv';
    const accessKeySecret = 'oci5yhpt5WTjFeq6J6x6bUKtPgp1OW';
    const bucket          = 'jinzilitop';

    /**
     * 根据Config配置，得到一个OssClient实例
     *
     * @return OssClient 一个OssClient实例
     */
    public static function getOssClient()
    {
        try {
            $ossClient = new OssClient(self::accessKeyId, self::accessKeySecret, self::endpoint, false);
        } catch (OssException $e) {
            printf(__FUNCTION__ . "creating OssClient instance: FAILED\n");
            printf($e->getMessage() . "\n");
            return null;
        }

        return $ossClient;
    }

    public static function getBucketName()
    {
        return self::bucket;
    }
    
}