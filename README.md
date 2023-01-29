# Управление событиями в 1С-Битрикс
Работая с битриксом, многие сталкиваются с проблемой того, что не понятно к каким событиям, какие модули подвязаны.
Также очень часто бывает, что удаляешь модуль, а обработчики на событиях остаются.

Благодаря этому модулю вы можете найти не нужные обработчики событий и удалить их!

## Преимущества:
* модуль позволяет видеть все обработчики на событиях
* модуль позволяет видеть всю информацию по обработчикам (модуль, класс, метод и т.п.)
* модуль позволяет отвязывать обработчики от событий
* модуль работает по принципу установил и используешь
* модуль не грузит систему и работает только при обращении к нему
* модуль позволяет фильтровать модули, классы, события, методы
* модуль позволяет сортировать любую колонку

### Интерфейс
![Screenshot_31](https://user-images.githubusercontent.com/7825114/215313415-42d75368-77b3-47e2-a290-3b75767e2b54.jpg)

#### Поиск по модулю
![Screenshot_32](https://user-images.githubusercontent.com/7825114/215313460-11f73275-9327-4e8a-8ffb-a744d2117171.jpg)

#### Дополнительные картинки

![Image of 1](https://raw.githubusercontent.com/darkfriend/dev2fun.showevents/master/images/fbda0c8c13.png?raw=true)

![Image of 1](https://raw.githubusercontent.com/darkfriend/dev2fun.showevents/master/images/348a5e1b3e.png?raw=true)

![Image of 1](https://raw.githubusercontent.com/darkfriend/dev2fun.showevents/master/images/7e76a7efe7.png)

## Как пользоваться UI
* В шапке таблицы есть input-поля, это фильтры
* Вводите текст и нажимаете Enter
* Внизу таблицы есть пагинация
* При нажатии на иконку корзины происходит удаление события 

## Шаги первой установки

1. Узнать кодировку своего сайта (utf8 или win1251)
1. Скопировать папку dev2fun.showevents из папки нужной кодировки в папку bitrix/modules
1. Перейти в админку сайта на страницу "Решения Маркетплейс" (/bitrix/admin/partner_modules.php)
1. Установить модуль Управление событиями в 1С-Битрикс (dev2fun.showevents)
1. Прочитать сообщение
1. Зайти в папку migrations и поочередно запустить каждую миграцию
1. Использовать модуль на странице `/bitrix/admin/settings.php?lang=ru&mid=dev2fun.showevents&mid_menu=1`
1. Поблагодарить автора :)

## Шаги обновлений
Т.к. у не активных лицензии updater битрикса не доступен, то нужно проделать след. шаги.

1. Узнать кодировку своего сайта (utf8 или win1251)
1. Скопировать папку dev2fun.showevents из папки нужной кодировки в папку bitrix/modules
1. Зайти в папку migrations и запустить нужные миграции
1. Сбросить кэш
1. Поблагодарить автора :)
1. Использовать

## Donate

|   |  |
| ------------- | ------------- |
| Bank Card  | [Visa/Mastercard/Mir/Other](https://www.tinkoff.ru/cf/36wVfnMf7mo)  |
| Yandex.Money  | 410011413398643  |
| Webmoney WMR (rub)  | R218843696478  |
| Webmoney WMU (uah)  | U135571355496  |
| Webmoney WMZ (usd)  | Z418373807413  |
| Webmoney WME (eur)  | E331660539346  |
| Webmoney WMX (btc)  | X740165207511  |
| Webmoney WML (ltc)  | L718094223715  |
| Webmoney WMH (bch)  | H526457512792  |
| PayPal  | [@darkfriend](https://www.paypal.me/darkfriend)  |
| Payeer  | P93175651  |
| Bitcoin  | 15Veahdvoqg3AFx3FvvKL4KEfZb6xZiM6n  |
| Litecoin  | LRN5cssgwrGWMnQruumfV2V7wySoRu7A5t  |
| Ethereum  | 0xe287Ac7150a087e582ab223532928a89c7A7E7B2  |
| BitcoinCash  | bitcoincash:qrl8p6jxgpkeupmvyukg6mnkeafs9fl5dszft9fw9w  |
