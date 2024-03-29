# php后台接口文件

### 效果展示：
请前往以下链接下载APK文件查看效果

https://github.com/Wgga/PluginCollect/tree/main/demo/plugins.apk

### 表情接口

#### 获取表情列表

| 请求方式 | URL                                                 |
| -------- | --------------------------------------------------- |
| GET      | http://127.0.0.1:8080/emoji.php?method=getemojilist |

- 返回参数说明

| 参数名 | 参数类型 | 参数说明 |
| ------ | ---------- | --------- |
| origin_uri | string | 表情图片名称 |
| display_name | string     | 表情名称 |
| emoji_url | string | 表情包图片路径 |

```json
{
    "origin_uri": "weixiao.png",
    "display_name": "[微笑]",
    "emoji_url": "app/emoji/0_[微笑]_weixiao.png"
}
```

### 城市接口

#### 获取城市列表

| 请求方式 | URL                            |
| -------- | ------------------------------- |
| POST      | http://127.0.0.1:8080/city.php  |

- 请求参数说明

| 参数名 | 参数类型 | 参数值 | 参数说明 |
| ------ | ---------- | --------- | --------- |
| method | string | getcitylist | 方法名 |
| code | number | 0 | 父级行政编码（获取省级数据时，该值传0）
| level | string | 0 | 行政级别；0省，1市，2区，3街道 |

- 返回参数说明

| 参数名       | 参数类型 | 参数说明                   |
| ------------ | -------- | -------------------------- |
| city_name    | string   | 城市名字                   |
| city_code    | int      | 行政编码                   |
| parent_code  | int      | 父级行政编码               |
| pinyin       | string   | 城市名称拼音               |
| first_letter | string   | 名称拼音首字母             |
| level        | int      | 类型；0省，1市，2区，3街道 |

```json
[
    {
        "city_name": "北京",
        "city_code": 110000,
        "parent_code": 0,
        "pinyin": "beijing",
        "first_letter": "B",
        "level": 0
    },
    {
        "city_name": "天津",
        "city_code": 120000,
        "parent_code": 0,
        "pinyin": "tianjin",
        "first_letter": "T",
        "level": 0
    },
    {
        "city_name": "河北省",
        "city_code": 130000,
        "parent_code": 0,
        "pinyin": "hebeisheng",
        "first_letter": "H",
        "level": 0
    }
]
```