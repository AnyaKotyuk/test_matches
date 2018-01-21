# test_matches

To see table with matches just enter site_url (data out in index.php)
For using API enter site_url/api.php?method
You can use methods getData and setData. 
For setting data to database fron remote API add GET parameter date in format 2018-JAN.


=====================================

Описание: парсинг и вывод расписания бейсбольных матчей MLB.
Задача: Необходимо реализовать три основных метода:
1. Парсит данные и складывает в базу (документация -
https://developer.fantasydata.com/docs/services/54bb5fd214338d0950b86dfe/operations/54bf2b9814338d0f80af6cc5)
2. API метод, возвращающий необходимые данные из базы в JSON формате
3. Метод, который рендерит страницу с расписанием, в таком виде (этот метод должен
получать данные из API метода, описанного в пункте 2):
дата  |   аббревиатура_команды_хозяева   | аббревиатура_команды_гости  |   id_стадиона

