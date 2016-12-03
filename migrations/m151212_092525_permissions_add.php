<?php

use yii\db\Schema;
use yii\db\Migration;

class m151212_092525_permissions_add extends Migration
{
    public function up()
    {

        $sql = "INSERT INTO {{%auth_item}} (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`)
VALUES
	('admin', 1, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ro\";a:2:{s:5:\"title\";s:13:\"Administrator\";s:11:\"description\";s:24:\"Administratorul siteului\";}s:2:\"ru\";a:2:{s:5:\"title\";s:26:\"Администратор\";s:11:\"description\";s:37:\"Администратор сайта\";}}}}', 1448189025, 1449234464),
	('admin-default-index', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:11:\"Admin panel\";s:11:\"description\";s:33:\"Main page of administrative panel\";}s:2:\"ro\";a:2:{s:5:\"title\";s:22:\"Partea administrativă\";s:11:\"description\";s:53:\"Pagina principală părții administrative a siteului\";}s:2:\"ru\";a:2:{s:5:\"title\";s:23:\"Админ панель\";s:11:\"description\";s:88:\"Главная страница административной панели сайта\";}}}}', 1448101731, 1449223877),
	('admin-languages-create', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:2:{s:2:\"en\";a:2:{s:5:\"title\";s:11:\"Add lanuage\";s:11:\"description\";s:24:\"Add new language to list\";}s:2:\"ru\";a:2:{s:5:\"title\";s:31:\"Добавление языка\";s:11:\"description\";s:71:\"Добавление нового языка к списку языка\";}}}}', 1448117161, 1449850668),
	('admin-languages-delete', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:14:\"Dlete language\";s:11:\"description\";s:24:\"Allow language delettion\";}s:2:\"ro\";a:2:{s:5:\"title\";s:13:\"Șterge limba\";s:11:\"description\";s:37:\"Acțiune permite ștergerea limbilor \";}s:2:\"ru\";a:2:{s:5:\"title\";s:23:\"Удалить язык\";s:11:\"description\";s:48:\"Разрешить удаление языков\";}}}}', 1448046783, 1448115886),
	('admin-languages-index', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:18:\"View language list\";s:11:\"description\";s:18:\"View all languages\";}s:2:\"ro\";a:2:{s:5:\"title\";s:14:\"Lista limbilor\";s:11:\"description\";s:28:\"Vizualizarea listei limbelor\";}s:2:\"ru\";a:2:{s:5:\"title\";s:29:\"Просмотр языков\";s:11:\"description\";s:42:\"Просмотр списка языков\";}}}}', 1448046546, 1448115970),
	('admin-languages-update', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:15:\"Update language\";s:11:\"description\";s:25:\"Update language from list\";}s:2:\"ro\";a:2:{s:5:\"title\";s:15:\"Editarea limbei\";s:11:\"description\";s:25:\"Editarea limbei din lista\";}s:2:\"ru\";a:2:{s:5:\"title\";s:39:\"Редактирование языка\";s:11:\"description\";s:57:\"Редактирование из списка языка\";}}}}', 1448046737, 1448116161),
	('admin-languages-view', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:13:\"View language\";s:11:\"description\";s:23:\"View language from list\";}s:2:\"ro\";a:2:{s:5:\"title\";s:19:\"Vizualizarea limbei\";s:11:\"description\";s:29:\"Vizualizarea limbei din lista\";}s:2:\"ru\";a:2:{s:5:\"title\";s:27:\"Просмотр языка\";s:11:\"description\";s:45:\"Просмотр языка из списка\";}}}}', 1448046655, 1448116275),
	('admin-menu-create', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ro\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ru\";a:2:{s:5:\"title\";s:25:\"Добавить меню\";s:11:\"description\";s:42:\"Добавление пункта меню\";}}}}', 1449216206, 1449218182),
	('admin-menu-delete', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ro\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ru\";a:2:{s:5:\"title\";s:23:\"Удалить меню\";s:11:\"description\";s:38:\"Удаление пункта меню\";}}}}', 1449216034, 1449216034),
	('admin-menu-index', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ro\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ru\";a:2:{s:5:\"title\";s:21:\"Список меню\";s:11:\"description\";s:39:\"Просмотр списка меню \";}}}}', 1449215882, 1449225487),
	('admin-menu-update', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ro\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ru\";a:2:{s:5:\"title\";s:25:\"Обновить меню\";s:11:\"description\";s:42:\"Обновление пункта меню\";}}}}', 1449216000, 1449216000),
	('admin-menu-view', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ro\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ru\";a:2:{s:5:\"title\";s:25:\"Просмотр меню\";s:11:\"description\";s:38:\"Просмотр пункта меню\";}}}}', 1449216237, 1449216237),
	('admin-rbac-assignment', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ro\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ru\";a:2:{s:5:\"title\";s:54:\"Назначение роль пользователю\";s:11:\"description\";s:55:\"Назначение роль пользователю.\";}}}}', 1448144731, 1448205450),
	('admin-rbac-index', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:22:\"Edit roles and actions\";s:11:\"description\";s:95:\"Edit roles and actions.\r\n(allows you to define what actions can perform certain roles of users)\";}s:2:\"ro\";a:2:{s:5:\"title\";s:34:\"Editarea rolurilor și acțiunilor\";s:11:\"description\";s:118:\"Editarea rolurilor și acțiunilor.\r\n(vă permite să definiți ce acțiuni pot efectua anumite roluri de utilizatori)\";}s:2:\"ru\";a:2:{s:5:\"title\";s:39:\"Редактирование ролей\";s:11:\"description\";s:218:\"Редактирование ролей и действий.\r\n(позволяет определять какие действия могут выполнять те или иные роли пользователей)\";}}}}', 1448193998, 1448193998),
	('admin-user-create', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:11:\"Create user\";s:11:\"description\";s:17:\"Create new user  \";}s:2:\"ro\";a:2:{s:5:\"title\";s:25:\"Adaugarea utilizatorului \";s:11:\"description\";s:31:\"Adaugarea noului utilizatorului\";}s:2:\"ru\";a:2:{s:5:\"title\";s:41:\"Добавить пользователя\";s:11:\"description\";s:54:\"Добавить нового пользователя\";}}}}', 1448116807, 1449218310),
	('admin-user-delete', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:11:\"Delete user\";s:11:\"description\";s:21:\"Delete user from list\";}s:2:\"ro\";a:2:{s:5:\"title\";s:25:\"Ștergerea utilizatorului\";s:11:\"description\";s:36:\"Ștergerea utilizatorului din listă\";}s:2:\"ru\";a:2:{s:5:\"title\";s:39:\"Удалить пользователя\";s:11:\"description\";s:57:\"Удалить пользователя из списка\";}}}}', 1448094010, 1448116438),
	('admin-user-index', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:10:\"View users\";s:11:\"description\";s:15:\"View users list\";}s:2:\"ro\";a:2:{s:5:\"title\";s:27:\"Vizualizarea utilizatorilor\";s:11:\"description\";s:34:\"Vizualizarea listei utilizatorilor\";}s:2:\"ru\";a:2:{s:5:\"title\";s:43:\"Просмотр пользователей\";s:11:\"description\";s:56:\"Просмотр списка пользователей\";}}}}', 1447881678, 1448116555),
	('admin-user-update', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:11:\"Update user\";s:11:\"description\";s:28:\"Update user\'s data from list\";}s:2:\"ro\";a:2:{s:5:\"title\";s:27:\"Actualizarea utilizatorului\";s:11:\"description\";s:46:\"Actualizarea datelor utilizatorului din listă\";}s:2:\"ru\";a:2:{s:5:\"title\";s:41:\"Обновить пользователя\";s:11:\"description\";s:72:\"Обновить данные пользователя из списка\";}}}}', 1448116960, 1448117019),
	('admin-user-view', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:9:\"View user\";s:11:\"description\";s:19:\"View user from list\";}s:2:\"ro\";a:2:{s:5:\"title\";s:27:\"Vizualizarea utilizatorului\";s:11:\"description\";s:38:\"Vizualizarea utilizatorului din listă\";}s:2:\"ru\";a:2:{s:5:\"title\";s:41:\"Просмотр пользователя\";s:11:\"description\";s:59:\"Просмотр пользователя из списка\";}}}}', 1448046970, 1448116638),
	('frontend-site-test', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ro\";a:2:{s:5:\"title\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ru\";a:2:{s:5:\"title\";s:33:\"тестовая страница\";s:11:\"description\";s:41:\"тестовая страница fronend\";}}}}', 1448555156, 1448555156),
	('frontend-user-index', 2, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:2:{s:2:\"en\";a:3:{s:5:\"title\";s:0:\"\";s:7:\"section\";s:0:\"\";s:11:\"description\";s:0:\"\";}s:2:\"ru\";a:3:{s:5:\"title\";s:13:\"Forntend user\";s:7:\"section\";s:5:\"Front\";s:11:\"description\";s:3:\"234\";}}}}', 1448111119, 1448111424),
	('moderator', 1, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:2:{s:2:\"en\";a:2:{s:5:\"title\";s:9:\"Moderator\";s:11:\"description\";s:14:\"Site moderator\";}s:2:\"ru\";a:2:{s:5:\"title\";s:20:\"Модератор !\";s:11:\"description\";s:29:\"Модератор сайта\";}}}}', 1447180224, 1449851348),
	('user', 1, NULL, NULL, 'a:1:{s:4:\"data\";a:1:{s:1:\"t\";a:3:{s:2:\"en\";a:2:{s:5:\"title\";s:4:\"user\";s:11:\"description\";s:36:\"Default role after user registration\";}s:2:\"ro\";a:2:{s:5:\"title\";s:10:\"utilizator\";s:11:\"description\";s:45:\"Rolul implicit după înregistrare utilizator\";}s:2:\"ru\";a:2:{s:5:\"title\";s:24:\"пользователь\";s:11:\"description\";s:104:\"Роль по умолчанию после регистрации нового пользователя\";}}}}', 1447188240, 1449221880);
";
        $sql.="INSERT INTO {{%auth_item_child}} (`parent`, `child`)
VALUES
	('admin', 'admin-default-index'),
	('moderator', 'admin-default-index'),
	('admin', 'admin-languages-create'),
	('admin', 'admin-languages-delete'),
	('admin', 'admin-languages-index'),
	('moderator', 'admin-languages-index'),
	('admin', 'admin-languages-update'),
	('admin', 'admin-languages-view'),
	('moderator', 'admin-languages-view'),
	('admin', 'admin-menu-create'),
	('admin', 'admin-menu-delete'),
	('admin', 'admin-menu-index'),
	('moderator', 'admin-menu-index'),
	('admin', 'admin-menu-update'),
	('admin', 'admin-menu-view'),
	('moderator', 'admin-menu-view'),
	('admin', 'admin-rbac-assignment'),
	('admin', 'admin-rbac-index'),
	('admin', 'admin-user-create'),
	('admin', 'admin-user-delete'),
	('admin', 'admin-user-index'),
	('moderator', 'admin-user-index'),
	('admin', 'admin-user-update'),
	('admin', 'admin-user-view'),
	('moderator', 'admin-user-view'),
	('admin', 'frontend-site-test'),
	('moderator', 'frontend-site-test'),
	('user', 'frontend-site-test'),
	('admin', 'frontend-user-index'),
	('user', 'frontend-user-index');
";
        $sql.="INSERT INTO {{%auth_assignment}} (`item_name`, `user_id`, `created_at`)
VALUES
	('admin', '1', 1449851367);
";
        $this->execute($sql);
    }

    public function down()
    {
        return false;
    }

}