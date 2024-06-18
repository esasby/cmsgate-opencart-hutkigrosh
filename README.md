## Модуль интеграции с CMS OpenCart  (v2.3, v3.0)

Данный модуль обеспечивает взаимодействие между интернет-магазином на базе CMS Opencart и сервисом платежей [ХуткiГрош](www.hutkigrosh.by)
* Модуль интеграции для версии [OpenCart 1.5.x](https://github.com/esasby/hutkigrosh-opencart1.5-module)
* Модуль интеграции для версии [OpenCart 2.1.x](https://github.com/esasby/hutkigrosh-opencart2.1-module)
* Модуль интеграции для версии [OpenCart 2.2.x](https://github.com/esasby/hutkigrosh-opencart2.2-module)

### Требования ###
1. PHP 5.6 и выше 
1. Библиотека Curl 

### Инструкция по установке:
1. Создайте резервную копию вашего магазина и базы данных
1. Установите модуль [cmsgate-opencart-hutkigrosh.ocmod.zip](https://bitbucket.org/esasby/cmsgate-opencart-hutkigrosh/raw/master/cmsgate-opencart-hutkigrosh.ocmod.zip) с помощью _Модули_ -> _Установка расширений_
1. Напротив модуля ХуткiГрош нажмите «Установить»

## Инструкция по настройке
1. Перейдите к настройке плагина через меню __Модули  -> Платежи__
1. Напротив модуля ХуткiГрош нажмите «Изменить».
1. Укажите обязательные параметры
    * Логин интернет-магазина – логин в системе ХуткiГрош.
    * Пароль интернет-магазина – пароль в системе ХуткiГрош.
    * Уникальный идентификатор услуги ЕРИП – ID ЕРИП услуги
    * Код услуги – код услуги в деревер ЕРИП. Используется при генерации QR-кода
    * Sandbox - перевод модуля в тестовый режим работы. В этом режиме счета выставляются в тестовую систему wwww.trial.hgrosh.by
    * Email оповещение - включить информирование клиента по email при успешном выставлении счета (выполняется шлюзом Хуткiгрош)
    * Sms оповещение - включить информирование клиента по смс при успешном выставлении счета (выполняется шлюзом Хуткiгрош)
    * Путь в дереве ЕРИП - путь для оплаты счета в дереве ЕРИП, который будет показан клиенту после оформления заказа (например, Платежи > Магазин > Заказы)
    * Срок действия счета - как долго счет, будет доступен в ЕРИП для оплаты    
    * Статус при выставлении счета  - какой статус выставить заказу при успешном выставлении счета в ЕРИП (идентификатор существующего статуса из Магазин > Настройки > Статусы)
    * Статус при успешной оплате счета - какой статус выставить заказу при успешной оплате выставленного счета (идентификатор существующего статуса)
    * Статус при отмене оплаты счета - какой статус выставить заказу при отмене оплаты счета (идентификатор существующего статуса)
    * Статус при ошибке оплаты счета - какой статус выставить заказу при ошибке выставленния счета (идентификатор существующего статуса)
    * Секция "Инструкция" - если включена, то на итоговом экране клиенту будет доступна пошаговая инструкция по оплате счета в ЕРИП
    * Секция QR-code - если включена, то на итоговом экране клиенту будет доступна оплата счета по QR-коду
    * Секция Alfaclick - если включена, то на итоговом экране клиенту отобразится кнопка для выставления счета в Alfaclick
    * Секция Webpay - если включена, то на итоговом экране клиенту отобразится кнопка для оплаты счета картой (переход на Webpay)
    * Текст успешного выставления счета - текст, отображаемый кленту после успешного выставления счета. Может содержать html. В тексте допустимо ссылаться на переменные @order_id, @order_number, @order_total, @order_currency, @order_fullname, @order_phone, @order_address
1. Сохраните изменения.

### Внимание!
* Для автоматического обновления статуса заказа (после оплаты клиентом выставленного в ЕРИП счета) необходимо сообщить в
  службу технической поддержки сервиса «Хуткi Грош» адрес обработчика:
    * для версии oc 2.1
    ```
    http://mydomen.my/index.php?route=payment/hutkigrosh/notify
    ```
    * Для версии oc >2.3
    ```
    http://mydomen.my/index.php?route=extension/payment/hutkigrosh/notify
    ```
    * Для версии oc >4.0
    ```
    http://mydomen.my/index.php?route=extension/cmsgate_opencart_hutkigrosh/payment/hutkigrosh.notify
    ```
* Модуль ведет лог файл по пути _
  site_root/upload/system/library/esas/cmsgate/hutkigrosh/vendor/esas/cmsgate-core/logs/cmsgate.log_
  Для обеспечения **безопасности** необходимо убедиться, что в настройках http-сервера включена директива _AllowOverride
  All_ для корневой папки.

### Тестовые данные

Для настрой оплаты в тестовом режиме

* воспользуйтесь данными для подключения к тестовой системе, полученными при регистрации в ХуткiГрош
* включите в настройках модуля режим "Песочницы"
* для эмуляции оплаты клиентом выставленного счета воспльзуйтесь личным
  кабинетом [тестовой системы](https://trial.hgrosh.by) (меню _Тест оплаты ЕРИП_)
  _Разработано и протестировано с OpenCart v.4.0.2.3_

### Инструкция по сборке

* при сборке cmsgate_opencart_hutkigrosh.ocmod.zip (для OpenCart > v4.0) необходимо:

- архивировать содержимое каталога \upload\
- удалить каталоги (необязательно)
    * admin\controller\extension\payment
    * admin\view\template\extension\payment
    * catalog\controller\extension\payment
    * catalog\model\extension\payment
    * catalog\view\theme\default\template\extension\payment

* при сборке cmsgate-opencart-hutkigrosh.ocmod.zip (для OpenCart > v2.3) необходимо удалить каталоги
    * upload\admin\controller\payment
    * upload\admin\view\template\payment
    * upload\catalog\controller\payment
    * upload\catalog\model\payment
    * upload\catalog\view\theme\payment
* при сборке cmsgate-opencart21-hutkigrosh.ocmod.zip (для OpenCart v2.1) необходимо удалить каталоги
    * upload\admin\controller\extension\payment
    * upload\admin\view\template\extension\payment
    * upload\catalog\controller\extension\payment
    * upload\catalog\model\extension\payment
    * upload\catalog\view\theme\default\template\extension\payment
    
    

