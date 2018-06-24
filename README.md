# project

## 项目介绍
新闻抓取系统简易版

## 使用说明

### 1.准备一台centos7.0服务器并安装elasticsearch
#### 安装JAVA环境
```
yum install java-1.8.0-openjdk
```

#### 安装elasticsearch
- 下载
```
wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-6.3.0.tar.gz
```
- 解压到/usr/local/
```
tar -zxvf elasticsearch-5.6.3.tar.gz -C /usr/local/
```
- 创建执行Elasticsearch用户
```
# 创建testuser账户
adduser testuser
# 修改密码
passwd testuse
# 给testuser用户elasticsearch目录的授权
chown -R testuser /usr/local/elasticsearch-5.6.3/
```
- 运行elasticsearch
```
# 以testUser用户运行
./bin/elasticsearch // -d 后台运行
```
- 测试elasticsearch
```
curl 'http://localhost:9200/?pretty' 
{
  "name" : "7Idqiie",
  "cluster_name" : "elasticsearch",
  "cluster_uuid" : "6FIgYHs7SYatju9knqkbVw",
  "version" : {
    "number" : "6.3.0",
    "build_flavor" : "default",
    "build_type" : "tar",
    "build_hash" : "424e937",
    "build_date" : "2018-06-11T23:38:03.357887Z",
    "build_snapshot" : false,
    "lucene_version" : "7.3.1",
    "minimum_wire_compatibility_version" : "5.6.0",
    "minimum_index_compatibility_version" : "5.0.0"
  },
  "tagline" : "You Know, for Search"
}

```
- 其他问题
借鉴地址 `https://segmentfault.com/a/1190000011899522`

### 2.下载项目
- git clone  url
- 执行composer install 
- 打开项目 测试

#### 参与贡献

