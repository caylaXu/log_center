#流程#

    client 端产生日志
        ↓↓↓↓
    udp发送到消息队列
        ↓↓↓↓
    消息队列分发到worker
        ↓↓↓↓
    worker 对日志进行处理 判断日志级别，告警
        ↓↓↓↓
    日志入库


消息队列使用golang＋zmq实现 当队列长度为1000W时 消耗2.4G内存

因为使用golang原生当chain  理想情况下入列出列速度 均可达到 1000W/s

实际速度取决于 UDP TCP 传输速度

#部署#

##安装go运行环境##

###debian/ubuntu###
    sudo apt-get install golang

###redhat/centos###
    sudo yum install go
    


##安装go依赖##

    cd queue
    go get
    
##安装php-zmq##

    自己百度去
    
#运行#

##运行队列##
    go run queue.go
    
    or
    
    go build queue.go
    ./queue
    
##运行worker##

    cd ../src
    php Worker.php
    
    
#客户端使用#

    向指定的端口发送UDP包
    具体格式参考如下
    
    
        $log = array(
            'SystemId' => 1,
            'Time'     => date('Y-m-d H:i:s'),
            'Level'    => 5,
            'Type'     => 'Runtime',
            'Message'  => $int,
            'Context'  => json_encode('一些附加信息, 需用json编码', JSON_UNESCAPED_UNICODE)
        );
    
        $logs = json_encode($log);
        $len = strlen($logs);
        socket_sendto($sock, $logs, $len, 0, LOG_SYSTEM_HOST, LOG_SYSTEM_PORT);
 
Request    
    Method: GET
    URL: 待定
    Params: {
        page: 1,                           // 页数
        limit: 10,                         // 记录条数
        type:1,                            // Runtime=1, Api=2, SQL=3...
        level: 1,                          // Notice=1, Warning=2...
        time_start: "2015-11-11 11:11:11", // 开始时间
        time_end: "2015-11-11 22:22:22",   // 结束时间
        keywords                           // 关键字搜索
    }
        
Response:
    {
        status: 0, // 状态 成功=1, 失败=2
        msg: "",   // 如果 status=1 , 则返回错误信息
        
        page_total: 5, // 总页数
        page: 1,       // 当前页
        total:  200,   // 总记录数
        
        records: [        
            {
                Id: 1, // 主键
                Time: 2015-11-11 11:11:11, // 日期
                Level: 200, // 数字
                Type: "Runtime", // 字符串
                Message: "接口调用失败", // 日志信息
                Context: {...} // 附加的json信息 需反序列化显示
            },
            {
                Id: 1, // 主键
                Time: 2015-11-11 11:11:11, // 日期
                Level: 200, // 数字
                Type: "Runtime", // 字符串
                Message: "接口调用失败", // 日志信息
                Context: {...} // 附加的json信息 需反序列化显示
            },
        ]
    }