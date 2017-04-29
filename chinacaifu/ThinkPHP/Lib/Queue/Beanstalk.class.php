<?php
namespace Queue;
use Pheanstalk\Pheanstalk;

/**
 * @todo 出错踢回队列重新处理；一条数据反复处理；定时处理；
 * Class Beanstalk
 * @package Queue
 */
class Beanstalk {

    const STATUS_INIT = 1; //插入队列
    const STATUS_SUCCESS = 2; //处理成功
    const STATUS_FAIL = 3; //处理失败

    private $beanstalk,
            $config;


    function __construct()
    {
        $config = C('beanstalk');
        if(empty($config)){
           throw_exception('请检查Beanstalk的配置是否存在');
        }
        $this->beanstalk = new Pheanstalk($config['host'],$config['port'],$config['connectTimeout']);
        unset($config['host']);
        unset($config['port']);
        unset($config['connectTimeout']);
        $this->config = $config;
    }

    /**
     * 数据放入队列
     * @param string $queue_name 队列名
     * @param string $queue_key 队列KEY
     * @param array $data 数据
     * @param string $priority
     * @param string $delay
     * @param string $ttr
     * @return int
     */
    public function set($queue_name,$queue_key,$data,$priority='',$delay='',$ttr='')
    {
        $queue_data = null;
        if(is_array($data)){
            $queue_data = serialize($data);
        }

        //队列日志记录成功，数据才能入队列
        $queue_id = $this->add_recode($queue_name,$queue_key,$queue_data);
        if(!$queue_id){
            throw_exception($queue_name.":".$queue_key.'数据'.$queue_data.'入队失败或者同样数据已经入队');
        }
        $config = $this->check_config($priority,$delay,$ttr);
        //返回队列中的ID
        $job_id =  $this->beanstalk->putInTube($queue_name,$queue_data,$config['priority'],$config['delay'],$config['ttr']);
        M('queue_record')->where(['id' => $queue_id])->save(['job_id' => $job_id]);
        return $job_id;
    }

    /**
     * 获取当前队列最靠前的一条数据
     * 这个操作是阻塞的，一直到有数据才返回。
     * @param string $queue_name
     * @return bool|object|\Pheanstalk\Job
     */
    public function get($queue_name)
    {
        $job = $this->beanstalk->reserveFromTube($queue_name);
        return array(
            'id'=>$job->getId(),
            'data'=>unserialize($job->getData()),
            'job'=>$job,
        );
    }

    /**
     * 处理队列数据
     * 更新队列记录表的log数据
     * 根据status的值删除或者保留队列中当前这条数据
     * status = self::STATUS_SUCCESS 处理成功后删除
     * status = self::STATUS_FAIL 处理失败后保留
     * 被保留的队列数据可以重新踢回队列，继续处理。
     * @todo 失败数据重复处理一定次数后才打入回收站，次数可配置
     * @param string $queue_name
     * @param string $queue_key
     * @param object $job
     * @param string $status
     * @param string $error_msg
     * @return bool
     */
    public function apply($queue_name,$queue_key,$job,$status='',$error_msg='')
    {
        if(empty($status)){
            $status = self::STATUS_FAIL;
            if(empty($error_msg)){
                $status = self::STATUS_SUCCESS;
            }
        }
        $update_recode = $this->update_recode($queue_name,$queue_key,$status,$error_msg);
        if($update_recode){
            if($status == self::STATUS_SUCCESS){
                $this->beanstalk->delete($job);
            }
            if($status == self::STATUS_FAIL){
                $this->beanstalk->bury($job);
            }
            return true;
        }
        return false;
    }

    public function stats($queue_name)
    {
        return $this->beanstalk->statsTube($queue_name);
    }

    /**
     * @param $job
     * @return $this
     */
    public function delete($job)
    {
        return $this->beanstalk->delete($job);
    }

    /**
     * 检查参数
     * @param $priority
     * @param $delay
     * @param $ttr
     * @return mixed
     */
    private function check_config($priority,$delay,$ttr)
    {
        $config = $this->config;
        if(!empty($priority) && is_numeric($priority)){
            $config['priority'] = $priority;
        }
        if(!empty($delay) && is_numeric($delay)){
            $config['delay'] = $delay;
        }
        if(!empty($ttr) && is_numeric($ttr)){
            $config['ttr'] = $ttr;
        }
        return $config;

    }

    /**
     * 数据库插入队列记录
     * @param $queue_name
     * @param $queue_key
     * @param $data
     * @return mixed
     */
    private function add_recode($queue_name,$queue_key,$data)
    {
        $db = M('queue_record');
        try {
            $db->startTrans();
            //queue和tkey有UNIQUE约束，保证同一条数据只能写入队列一次。
            $queue_data = array(
                'queue' => $queue_name,
                'tkey' => $queue_key,
                'data' => $data,
                'status' => self::STATUS_INIT,
                'ctime' => time(),
            );
            $id = $db->add($queue_data);
            if(!$id || !$db->commit()){
                throw_exception('插入失败');
            }
            return $id;
        } catch (\Exception $exc) {
            $db->rollback();
            return false;
        }

    }

    /**
     * 更新数据库队列记录状态和错误信息
     * @param $queue_name
     * @param $queue_key
     * @param $status
     * @param $error_msg
     * @return bool
     */
    public function update_recode($queue_name,$queue_key,$status,$error_msg)
    {
        $where = array(
            'queue' => $queue_name,
            'tkey' => $queue_key,
        );

        $queue_data = array(
            'mtime' => time(),
            'error_msg' => $error_msg,
        );

        if($status == self::STATUS_SUCCESS || $status == self::STATUS_FAIL){
            $queue_data['status'] = $status;
        }
        return M('queue_record')->where($where)->save($queue_data);
    }

} 