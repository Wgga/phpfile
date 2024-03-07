# -*-coding:utf-8-*-
import requests
from bs4 import BeautifulSoup
import time

headers = {
    'Cookie': 'wzws_sessionid=gTMxMTZiNoI3ZWQyZDCAMTcxLjE1LjEwNy4xMTGgZRApaw==; SF_cookie_1=37059734',
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36 Edg/117.0.2045.36',
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7'
}

# 根据地址获取页面内容，并返回BeautifulSoup
def get_html(url):
    # 若页面打开失败，则无限重试，没有后退可言
    while True:
        try:
            print(url)
            # 超时时间为1秒
            response = requests.get(url, timeout=1, headers=headers)
            response.encoding = "UTF-8"
            if response.status_code == 200:
                return BeautifulSoup(response.text, "lxml")
            else:
                continue
        except Exception:
            continue
        finally:
            # 等待10秒
            time.sleep(1)

# 获取地址前缀（用于相对地址）
def get_prefix(url):
    return url[0:url.rindex("/") + 1]


# 递归抓取下一页面
def spider_next(url, lev, province_code):
    if lev == 1:
        spider_class = "city"
    elif lev == 2:
        spider_class = "county"
    elif lev == 3:
        spider_class = "town"
    else:
        spider_class = "village"

    for item in get_html(url).select("tr." + spider_class + "tr"):
        if spider_class == "village": continue
        item_td = item.select("td")
        item_td_code = item_td[0].select_one("a")
        item_td_name = item_td[1].select_one("a")
        if item_td_code is None:
            item_href = None
            item_code = item_td[0].text
            item_name = item_td[1].text
            if lev == 4:
                item_name = item_td[2].text
        else:
            item_href = item_td_code.get("href")
            item_code = item_td_code.text
            item_name = item_td_name.text
		# 输出：(名称, 区划代码, 父级代码, 拼音, 首字母, 级别)
        content2 = "('" + item_name + "', " + item_code + ", " + province_code + ", '', '', " + str(lev) + "),"
        print(content2)
        f.write(content2 + "\n")
        if item_href is not None:
            spider_next(get_prefix(url) + item_href, lev + 1, item_code)


# 入口
if __name__ == '__main__':

    # 抓取省份页面
    province_url = "http://www.stats.gov.cn/sj/tjbz/tjyqhdmhcxhfdm/2023/index.html"
    province_list = get_html(province_url).select('tr.provincetr a')
    # 数据写入到当前文件夹下 area-number-2023.txt 中
    f = open("area-number-2023.txt", "w", encoding="utf-8")
    try:
        for province in province_list:
            href = province.get("href")
            province_code = href[0: 2] + "0000"
            province_name = province.text
            # 输出：(名称, 区划代码, 父级代码, 拼音, 首字母, 级别)
			# ('北京', 110000, 0, 'beijing', 'B', 0),
            content = "('" + province_name + "', " + province_code + ", 0, '', '', 0),"
            print(content)
            if int(province_code) < 520000:
                continue
            f.write(content + "\n")
            spider_next(get_prefix(province_url) + href, 1, province_code)
    finally:
        f.close()