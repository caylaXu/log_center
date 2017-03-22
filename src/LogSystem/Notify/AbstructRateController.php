<?php


namespace LogSystem\Notify;

/**
 * 频率控制
 * Class AbstructRateController
 * @author  Leo Yang <leoyang@motouch.cn>
 */
abstract class AbstructRateController
{

    /**
     * @var int 频率 秒
     */
    protected $rate = 60;

    /**
     * @var int 上次操作　time()
     */
    protected $lastAction;

    /**
     * @var int　距离上次操作累计限制频率次数
     */
    protected $ac = 0;

    /**
     * @var int 总共限制频率次数
     */
    protected $aca = 0;

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param int $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return int
     */
    public function getLastAction()
    {
        return $this->lastAction;
    }

    /**
     * @param int $lastAction
     */
    public function setLastAction($lastAction)
    {
        $this->lastAction = $lastAction;
    }


    protected function checkRate()
    {

        if ($this->lastAction == null) {
            $this->lastAction = time();
            return true;
        }

        $time = time();
        if ($time < $this->lastAction + $this->rate) {
            $this->aca ++;
            $this->ac ++;
            return false;
        }
        $this->lastAction = $time;
        $ac = $this->ac;
        $this->ac = 0;
        return $ac;
    }

}