eventdove_wp_plugin
===================

会鸽API wordpress plugin 

会鸽API访问 Wordpress 插件

会鸽集成活动简单步骤
1. 创建会鸽帐号
2. 进入open.eventdove.com 申请开发者帐号，申请完成后需要审批
3. 审核完成后，会生成访问需要的token 
4. http://eventdove.com/api/apilist/1  访问该接口，用以获取活动列表


接口方式：http
数据返回方式：JSON串

简单参数说明，详细见会鸽各接口文档：

bannerUrl        活动banner  真实地址是：http://eventdove.com/bannerUrl
brief                活动简介 
eventAddress   活动地址
eventId           活动标识
eventTime       活动时间
logoUrl            活动logo
organizername  组织者名字
pubStatus           活动状态  1 即将开始 2 正在进行 3 已经结束 
subdomainName  二级域名    所有访问地址是: ${subdomainName}+eventdove.com
