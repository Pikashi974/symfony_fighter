Routes:
| Name | Method | Parameters | Path |
|-----------------|--------|--------|--------------------------|
| app_register | POST | {'email' => string,'password' => string,'username' => string}  | /api/register |
| app_champion | POST | {'name' => string, 'pv' => integer > 0, 'power' => integer > 0}  | /api/admin/champions/add |
| app_fight | ANY | {'user1': string (mail), 'user2': string(mail)}  | /api/fight |
| app_index | ANY |   | / |
| api_login_check | POST | {'password' => string,'username' => string} | /api/login_check |
-----------------
Tests: 

[ChampionsControllerTest.php](https://github.com/Pikashi974/symfony_fighter/blob/main/tests/Controller/ChampionControllerTest.php)

[FightControllerTest.php](https://github.com/Pikashi974/symfony_fighter/blob/main/tests/Controller/FightControllerTest.php)
