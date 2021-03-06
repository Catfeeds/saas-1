<?php
/**
 * 公共方法
 * User: 罗振
 */
namespace App\Handler;

use App\Models\CustomerOperationRecord;
use App\Models\House;
use App\Models\HouseOperationRecord;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Ramsey\Uuid\Uuid;

/**
 * Class Common
 * 常用工具类
 * @package App\Tools
 */
class Common
{
    // 获取uuid
    public static function getUuid()
    {
        $uuid1 = Uuid::uuid1();
        return $uuid1->getHex();
    }

    // 验证手机号是否正确
    public static function isMobile($mobile)
    {
        return preg_match('#^1\d{10}$#', $mobile) ? true : false;
    }

    //登录用户
    public static function user()
    {
        return \Illuminate\Support\Facades\Auth::guard('api')->user();
    }

    // 登录用户
    public static function admin()
    {
        return \Illuminate\Support\Facades\Auth::guard('admin')->user();
    }

    // 获取七牛token
    public static function getToken($accessKey = null, $secretKey = null, $bucket = null)
    {
        if (empty($accessKey)) {
            $accessKey = config('setting.qiniu_access_key');
        }
        if (empty($secretKey)) {
            $secretKey = config('setting.qiniu_secret_key');
        }
        if (empty($bucket)) {
            $bucket = config('setting.qiniu_bucket');
        }
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        return $token;
    }

    // 七牛上传图片
    public static function QiniuUpload($filePath, $key)
    {
        //获得token
        $token = self::getToken();

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        $res = ['status' => true, 'url' => config('setting.qiniu_url') . $key];

        if (!$err == null) return ['status' => false, 'msg' => $err];

        return $res;
    }

    // 数组转对象
    public static function arrayToObject($e)
    {
        if (gettype($e) != 'array') return;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $e[$k] = (object)self::arrayToObject($v);
        }
        return (object)$e;
    }

    // 添加房源操作记录
    public static function houseOperationRecords
    (
        $user_guid,
        $house_guid,
        $type,
        $remarks,
        $img=null,
        $track_guid = null,
        $visit_guid = null,
        $old_img=null
    )
    {
        $houseOperationRecord = HouseOperationRecord::create([
            'guid' => self::getUuid(),
            'user_guid' => $user_guid,
            'house_guid' => $house_guid,
            'track_guid' => $track_guid,
            'type' => $type,
            'remarks' => $remarks,
            'old_img' => $old_img,
            'img' => $img,
            'visit_guid' => $visit_guid
        ]);
        if (empty($houseOperationRecord)) return false;
        return true;
    }

    // 添加客源操作记录
    public static function customerOperationRecords
    (
        $user_guid,
        $customer_guid,
        $type,
        $remarks,
        $track_guid = null
    )
    {
        $customerOperationRecords = CustomerOperationRecord::create([
            'guid' => self::getUuid(),
            'user_guid' => $user_guid,
            'customer_guid' => $customer_guid,
            'track_guid' => $track_guid,
            'type' => $type,
            'remarks' => $remarks
        ]);
        if (empty($customerOperationRecords)) return false;
        return true;
    }

    // 拼接房源标题
    public static function HouseTitle($guid)
    {
        $house = House::with('buildingBlock', 'buildingBlock.building')->where('guid', $guid)->first();
        $title = [];
        $title['building_name'] = $house->buildingBlock->building->name;
        $title['acreage'] = $house->acreage_cn;
        $title['price'] = $house->price . '元/㎡/月';
        $title['img'] = $house->indoor_img_cn;
        $title['house_guid'] = $guid;
        return $title;
    }

    // 房源,客源编号
    public static function identifier(
        $lastData
    )
    {
        if (empty($lastData->house_identifier) && empty($lastData->customer_identifier)) {
            return $identifier = 'WH-'.date('ymd',time()).'0001';
        } else {
            $lastIdentifier = $lastData->house_identifier??$lastData->customer_identifier;
        }

        if (empty($lastIdentifier)) {
            return $identifier = 'WH-'.date('ymd',time()).'0001';
        } elseif ($lastIdentifier) {
            // 截取日期
            $date = substr($lastIdentifier,3,6);
            // 截取编号
            $upIdentifier = substr($lastIdentifier,9,4);

            if (date('Y-m-d',strtotime('20'.$date) + (3600 * 24)) == date('Y-m-d',time())) {
                return $identifier = 'WH-'.date('ymd',time()).'0001';
            } else {
                $temp = $upIdentifier+1;

                if (strlen($temp) == 1) {
                    $temp = '000'.$temp;
                } elseif (strlen($temp) == 2) {
                    $temp = '00'.$temp;
                } elseif (strlen($temp) == 3) {
                    $temp = '0'.$temp;
                }

                return $identifier = 'WH-'.date('ymd',time()).$temp;
            }
        }
    }
}