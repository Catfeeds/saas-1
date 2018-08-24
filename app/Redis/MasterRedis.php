<?php
/**
 * redis底层公共类
 * User: 郭庆
 * Date: 2017/01/11
 * Time: 15:51
 * @author:郭庆
 */

namespace App\Redis;

use Illuminate\Support\Facades\Redis;

class MasterRedis
{

    /**
     * 判断key是否存在
     * @param $key string redis的key
     * @return bool
     * @author 郭庆
     */
    public function exists($key)
    {
        return Redis::exists($key);  //查询key是否存在
    }

    /**
     * 获取redis缓存里某一个list中的指定页的所有元素
     * @param $key string list的key
     * @param $nums int 每页显示条数
     * @param $nowPage int  当前页数
     * @return array
     * @author 郭庆
     */
    public function getPageLists($key, $nums, $nowPage)
    {
        //起始偏移量
        $offset = $nums * ($nowPage - 1);

        //获取条数
        $totals = $offset + $nums - 1;

        //获取缓存的列表索引并返回
        return $this->getBetweenList($key, $offset, $totals);

    }

    /**
     * 获取指定范围内的list数据
     * @param $key string 指定list Key
     * @param $start int 开始位置
     * @param $end int 结束位置
     * @return array
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');
     * $redis->lRange('key1', 0, -1); // array('A', 'B', 'C')
     * </pre>
     * @author 郭庆
     */
    public function getBetweenList($key, $start, $end, $time = '')
    {
        $data = Redis::lrange($key, $start, $end);
        if (!$data) return false;

        // 设置生命周期
        if (!empty($time)) {
            $this->setTime($key, config('setting.list_life_time'));
        }
        return $data;
    }

    /**
     * 获取hash的全部字段数据
     * @param $key string hash的key
     * @param bool|int $time 如果需要单独设置时间则传第二个参数
     * @return array [] 成功： array 全部字段的键值对 失败：bool false
     * @author 郭庆
     */
    public function getHash($key, $time = false)
    {
        $data = Redis::hGetAll($key);
        if (!$data) return false;

        //设置生命周期
        if (empty($time)) {
            $this->setTime($key, config('setting.hash_life_time'));
        } else {
            $this->setTime($key, $time);
        }
        return $data;
    }

    /**
     * 获取hash的指定几个字段的数据
     * @param $key string hash的key
     * @param $fields array hash的指定几个字段 array('field1', 'field2')
     * @return array
     * @author 郭庆
     */
    public function getHashFileds($key, $fields, $status = true)
    {
        $i = 0;
        $values = Redis::hMGet($key, $fields);

        if (empty($status)) return $values;

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $values[$i++];
        }
        return $data;
    }

    /**
     * 将一条记录写入hash
     * @param $key string hash的key
     * @param $data array 存入hash的具体字段和值
     * @param $time int|bool 如果需要单独设置时间则传这个参数
     * @return bool
     * @author 郭庆
     */
    public function addHash($key, $data, $time = false)
    {
        if (empty($key) || empty($data)) return false;
        $result = true;
        if (!$this->exists($key)) {
            //写入hash
            if (isset($data['img_url']) &&(!empty($data['img_url']) || is_array($data['img_url']))) unset($data['img_url']);
            if (isset($data['building']) &&(!empty($data['building']) || is_array($data['building']))) unset($data['building']);
            $result = Redis::hMset($key, $data);
        }
        if (!$result) {
            \Log::error('写入hash出错' . $key);
            return false;
        } else {
            //设置生命周期
            if (empty($time)) {
                $this->setTime($key, config('setting.hash_life_time'));
            } else {
                $this->setTime($key, $time);
            }
            return true;
        }
    }

    /**
     * 修改一条hash记录
     * @param $key string hash的key
     * @param $data array 所要修改的键值对
     * @return bool
     * @author 郭庆
     */
    public function changeOneHash($key, $data)
    {
        //写入hash
        if (!Redis::hMset($key, $data)) return false;
        //设置生命周期
        if (empty($time)) {
            $this->setTime($key, config('setting.hash_life_time'));
        } else {
            $this->setTime($key, $time);
        }
        return true;
    }

    /**
     * 删除指定的 keys.
     * @param $key string|array 所要删除的key(可以为数组也可以为字符串)
     * @return int Number 删除了的条数
     * @example
     * <pre>
     * $redis->set('key1', 'val1');
     * $redis->set('key2', 'val2');
     * $redis->set('key3', 'val3');
     * $redis->set('key4', 'val4');
     * $redis->delete('key1', 'key2');          // return 2
     * $redis->delete(array('key3', 'key4'));   // return 2
     * </pre>
     * @author 郭庆
     */
    public function delKey($key)
    {
        if (empty($key)) return false;
        return Redis::del($key);
    }

    /**
     * 对list进行右推（推一个/多个）
     * @param $key string listkey
     * @param $lists array [guid1,guid2] / $lists string 一次推入一个list
     * @param $time bool 默认不设置生命周期
     * @return bool
     * @author 郭庆
     */
    public function rPushLists($key, $lists, $time = false)
    {
        if (empty($key) || empty($lists)) return false;

        //执行写list操作
        $res = Redis::rpush($key, $lists);
        if (empty($res)) return false;

        //设置生命周期
        if (!empty($time)) {
            $this->setTime($key, config('setting.list_life_time'));
        }
        return $res;
    }

    /**
     * 对list进行左推（可以推一个也可以多个）
     * @param $key string listkey
     * @param $lists array [guid1,guid2] / $lists string 一次推入一个list
     * @param $time bool 生命周期(默认不设置生命周期）
     * @return bool|int 失败返回false，成功插入条数
     * @author 郭庆
     */
    public function lPushLists($key, $lists, $time = false)
    {
        if (empty($key) || empty($lists)) return false;

        //执行写list操作
        $res = Redis::lpush($key, $lists);
        if (empty($res)) return false;

        //设置生命周期
        if (!empty($time)) {
            $this->setTime($key, config('setting.list_life_time'));
        }
        return $res;
    }

    /**
     * 将元素插入到指定list元素的前面或者后面
     *
     * @param $key string list key
     * @param $position "after"/"before"
     * @param $old mixed 指定的元素
     * @param $new mixed 所要插入的元素
     * @return int|boolean
     * @author 郭庆
     */
    public function lInsert($key, $position, $old, $new)
    {
        if (empty($key) || empty($position) || empty($old) || empty($new)) return false;

        return \Redis::LINSERT($key, $position, $old, $new);
    }

    /**
     * 设置hash缓存的生命周期
     * @param $key  string  需要设置的key
     * @param $time int|bool 如果需要单独设置时间则传这个参数
     * @return bool 设置成功true 否则false
     * @author 郭庆
     */
    public function setTime($key, $time = false)
    {
        if (empty($time)) return Redis::expire($key, config('setting.hash_life_time'));
        return Redis::expire($key, $time);
    }

    /**
     * 获取 现有list 的长度
     * @param $key string list的key
     * @return int 对应key的list长度
     * @author 郭庆
     */
    public function getLength($key)
    {
        return Redis::llen($key);
    }

    /**
     * 删除一条list记录
     * @param $key string list的key
     * @param $guid string 所要删除的list元素
     * @return bool|int 失败返回false，成功删除数目
     * @author 郭庆
     */
    public function delList($key, $guid)
    {
        if ($this->exists($key)) return Redis::lrem($key, 0, $guid);
        return true;
    }

    /**
     * 添加一个新的短存的string redis
     * @param $key string key
     * @param $value
     * @param $time int|bool 设置存活时间
     * @return bool
     * @author 郭庆
     */
    public function addString($key, $value, $time = false)
    {
        if (empty($key)) return false;
        if (!Redis::Set($key, $value)) return false;
        //设置生命周期
        if (empty($time)) {
            $this->setTime($key, config('setting.string_life_time'));
        } else {
            $this->setTime($key, $time);
        }
        return true;
    }

    /**
     * 将 string key 中储存的数字值增一
     * @param   string $key
     * @return  int    the new value
     * @author 郭庆
     */
    public function incre($key)
    {
        if (empty($key)) return false;
        return Redis::incr($key);
    }

    /**
     * 给hash中某一个字段加一个值
     * @param $key string hash的key
     * @param $filed string 所要自增的字段
     * @param $value int 所要自增的值
     * @return array
     * @author 郭庆
     */
    public function hIncrBy($key, $filed, $value)
    {
        return Redis::hIncrBy($key, $filed, $value);
    }

    /**
     * 得到一个string
     * @param   string $key
     * @param int|bool $time 失效时间
     * @return  string|bool: If key didn't exist, FALSE is returned. Otherwise, the value related to this key is returned.
     * @author 郭庆
     */
    public function getString($key, $time = false)
    {
        if (empty($key)) return false;
        $data = Redis::get($key);
        if (!$data && $data != 0) return false;

        if ($time == 'old') return $data;
        //设置生命周期
        if (empty($time)) {
            $this->setTime($key, config('setting.string_life_time'));
        } else {
            $this->setTime($key, $time);
        }
        return $data;
    }

    /**
     * 清空redis缓存
     * @param
     * @return array
     * @author 郭庆
     */
    public function destroy()
    {
        return Redis::flushAll();
    }

    /**
     * 获取到指定正则匹配的所有key
     * @param string $pattern
     * @return array
     * @author 郭庆
     */
    public function getKeys($pattern)
    {
        return Redis::keys($pattern);
    }

    /**
     * 插入集合
     * @param string $key
     * @param string $string
     * @return mixed
     * @author 张洵之
     */
    public function sadd($key, $string)
    {
        return Redis::sAdd($key, $string);
    }

    /**
     * 得到集合成员数量
     * @param string $key 键
     * @return int
     * @author 张洵之
     */
    public function scard($key)
    {
        return Redis::sCard($key);
    }

    /**
     * 获取集合数据
     * @param string $key 键
     * @param int $num 数量
     * @return bool
     * @author 罗振
     */
    public function srandmember($key, $num)
    {
        return Redis::sRandmember($key, $num);
    }

    /**
     * 删除集合置顶成员
     * @param string $key 键
     * @param $id
     * @return bool
     * @author 罗振
     */
    public function srem($key, $id)
    {
        return Redis::sRem($key, $id);
    }

    /**
     * 检验元素（value）是否存在集合中
     * @param string $key 集合键名
     * @param string $value 元素名
     * @return bool
     * author 张洵之
     */
    public function checkSadd($key, $value)
    {
        if (!is_string($key) || !is_string($value)) return false;

        return Redis::sIsMember($key, $value);
    }

    /**
     * 设置单字符redis 有超时时间
     * @param $key
     * @param $val
     * @param int $time
     * @author 张洵之
     */
    public static function setexRedis($key, $val, $time)
    {
        if (!empty($key) && !empty($val)) {
            return Redis::setex($key, $time, $val);
        } else {
            return false;
        }

    }

    /**
     * 存储单条hash数据
     * @param string $key hash键
     * @param string $hashKey hash内键
     * @param string $value hash内键对应的值
     * @return mixed
     * @author 张洵之
     */
    public static function hSet($key, $hashKey, $value)
    {
        return Redis::hSet($key, $hashKey, $value);
    }

    /**
     * 通过索引获取指定列表键值
     * @param $key
     * @param $index
     * @return bool|String
     * @author 王通
     */
    public static function getListInIndex($key, $index)
    {
        return Redis::lIndex($key, $index);
    }

}