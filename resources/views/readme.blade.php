## Alaska 最新净值接口开发指南

推荐使用阿里云腾讯云等云函数

### 目的

解决支付宝等平台不能计算基金股票等年化收益

### 接口规范

* HTTP方式 GET
* 参数自定义
* 返回结果

|   名称   |  类型  | 必填 | 默认值 |   备注    |
| :------: | :----: | :--: | :----: | :-------: |
|  success   | bool | yes  |  null  |  返回结果   |
| data.last_value | float | yes  |  null  |   昨日净值    |
| data.current_value | float | yes  |  null  |   最新净值    |

```json
{
    success: true,
    data:{
        last_value: "1.952",
        current_value: "1.852",
    }
}
```

