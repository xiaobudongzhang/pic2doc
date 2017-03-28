# 运行
1. 在public下运行php -S {ip:port} -t ./
2. 在pic2doc_front下运行 npm run dev
3. 访问http://{ip:port}/v/home/project_sub?project_id=1


 
# 坐标
 
字段说明：
* map_page_sub_id id
* point_x 坐标x
* point_y 坐标y
* title  点的描述
* contentList 内容列表
* contentList.type_name  cp 产品 cs测试 kf开发 sj 设计 
* contentList.content  对应内容

1.获取列表

* 路径：data/point/getList
* 方式：get
* 参数：map_page_sub_id  
* 返回： 
`{
    "code": "00000",
    "data": [
        {
            "id": "59",
            "point_x": "861",
            "point_y": "271",
            "map_page_sub_id": "1",
            "title":'',
            "contentList": [
                {
                    "id": "11",
                    "map_page_sub_point_id": "59",
                    "type_name": "cp",
                    "content": " <h4>1.≤2张</h4>\r\n <h4>2.4s自动轮播</h4>\r\n <h4>3.点击图片区域新窗口打开页面</h4>\r\n <h4>4.点击底部圆点可定位至对应焦点图，点击后重新计时</h4>\r\n <h4>5.后台配置</h4>",
                    "type_index": "1",
                    "create_time": "0",
                    "update_time": "0",
                    "ancient_index": 0
                }
            ]
        }
    ],
    "msg": ""
}`

2.删除
* 路径：data/point/delete
* 方式：post
* 参数：id 
* 返回： 
`{
  "code": "00000",
  "data": [],
  "msg": ""
}`


3.更新点的描述
* 路径：data/point/updateTitle
* 方式：post
* 参数：id title
* 返回： 
`{
  "code": "00000",
  "data": {
    "id": "63",
    "title": "cp"
  },
  "msg": ""
}`


# 内容

字段说明：
* map_page_sub_point_id id
* content 内容
* type_name      cp 产品 cs测试 kf开发 sj 设计 
* send_dd   是否讲将将消息发送钉钉 （0  否  1   是）   
1.修改
* 路径：data/content/update
* 方式：post
* 参数：map_page_sub_point_id ,type_name ,  content,send_dd
* 返回： 
`{
  "code": "00000",
  "data": [],
  "msg": ""
}`




http://10.12.21.119:8899/v/home/project_sub