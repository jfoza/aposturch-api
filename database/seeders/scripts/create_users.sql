-- SUPPORT
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _name        varchar = 'Technical Support User';
        _email       varchar = 'technical-support-user@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');

        _profile varchar := 'TECHNICAL_SUPPORT';
        _profile_uuid uuid;

        _module1 uuid;
        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'USERS';
        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO users.users (id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO users.admin_users (user_id)
        VALUES
            (
                _user_uuid
            );

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1),
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );
    END $$;
commit;

-- USER 1
START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _name        varchar = 'Giuseppe Foza';
        _email       varchar = 'jofender.foza@gmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');

        _profile varchar := 'ADMIN_MASTER';
        _profile_uuid uuid;

        _module1 uuid;
        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'USERS';
        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO users.users (id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO users.admin_users (user_id)
        VALUES
            (
                _user_uuid
            );

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1),
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _name        varchar = 'Usuário Admin Master 1';
        _email       varchar = 'admin-master1@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');

        _profile varchar := 'ADMIN_MASTER';
        _profile_uuid uuid;

        _module1 uuid;
        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'USERS';
        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO users.users (id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO users.admin_users (user_id)
        VALUES
            (
                _user_uuid
            );

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1),
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Admin Igreja 1';
        _email       varchar = 'admin-church1@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51912433363';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_CHURCH';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Admin Igreja 2';
        _email       varchar = 'admin-church2@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '61981476647';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_CHURCH';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-teste-1';
        _church_id uuid;

        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Admin Módulo Membresia';
        _email       varchar = 'membership-admin-module1@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '84975585929';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_MODULE';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module1 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Admin Módulo Loja Virtual 1';
        _email       varchar = 'store-admin-module1@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '84975085909';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_MODULE';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module1 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'STORE';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Admin Módulo Grupos';
        _email       varchar = 'groups-admin-module1@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '84905186929';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_MODULE';
        _profile_uuid uuid;

        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module1 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'GROUPS';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Auxiliar 1';
        _email       varchar = 'assistant1@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '99991749584';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ASSISTANT';
        _profile_uuid uuid;
        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module1 uuid;
        _module2 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1),
            (_user_uuid, _module2);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Auxiliar 2';
        _email       varchar = 'assistant2@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '49969946737';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ASSISTANT';
        _profile_uuid uuid;
        _church_unique_name varchar = 'igreja-teste-1';
        _church_id uuid;

        _module2 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module2);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Auxiliar 3';
        _email       varchar = 'assistant3@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51999999998';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ASSISTANT';
        _profile_uuid uuid;
        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module2 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module2);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _member_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Auxiliar Módulo Loja';
        _email       varchar = 'assistant-store-module@email.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51999967888';
        _zip_code    varchar = '96010165';
        _address     varchar = 'Rua Tiradentes';
        _district    varchar = 'Centro';
        _number      varchar = '80';
        _complement  varchar = 'casa';
        _city        varchar = 'Pelotas';
        _uf          varchar = 'RS';

        _profile varchar := 'ASSISTANT';
        _profile_uuid uuid;
        _church_unique_name varchar = 'igreja-biblica-viver-caxias';
        _church_id uuid;

        _module1 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;
        SELECT id INTO _church_id FROM membership.churches WHERE unique_name = _church_unique_name;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'STORE';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                true
            );

        INSERT INTO membership.members(id, user_id)
        VALUES
            (_member_uuid, _user_uuid);

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );

        INSERT INTO membership.churches_members (member_id, church_id)
        VALUES
            (
                _member_uuid,
                _church_id
            );
    END $$;
commit;


START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário Inativo';
        _email       varchar = 'inactive-user@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '51999999999';
        _zip_code    varchar = '99999999';
        _address     varchar = 'Rua Otto Daudt';
        _district    varchar = 'Feitoria';
        _number      varchar = '1770';
        _complement  varchar = 'casa';
        _city        varchar = 'São Leopoldo';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_MASTER';
        _profile_uuid uuid;

        _module1 uuid;
        _module2 uuid;
        _module3 uuid;
        _module4 uuid;
        _module5 uuid;
        _module6 uuid;
        _module7 uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;

        SELECT id INTO _module1 FROM module.modules WHERE module_unique_name = 'USERS';
        SELECT id INTO _module2 FROM module.modules WHERE module_unique_name = 'FINANCE';
        SELECT id INTO _module3 FROM module.modules WHERE module_unique_name = 'MEMBERSHIP';
        SELECT id INTO _module4 FROM module.modules WHERE module_unique_name = 'STORE';
        SELECT id INTO _module5 FROM module.modules WHERE module_unique_name = 'GROUPS';
        SELECT id INTO _module6 FROM module.modules WHERE module_unique_name = 'SCHEDULE';
        SELECT id INTO _module7 FROM module.modules WHERE module_unique_name = 'PATRIMONY';

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                false
            );

        INSERT INTO users.admin_users (user_id)
        VALUES
            (
                _user_uuid
            );

        INSERT INTO users.modules_users (user_id, module_id)
        VALUES
            (_user_uuid, _module1),
            (_user_uuid, _module2),
            (_user_uuid, _module3),
            (_user_uuid, _module4),
            (_user_uuid, _module5),
            (_user_uuid, _module6),
            (_user_uuid, _module7);

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );
    END $$;
commit;

START TRANSACTION;

DO $$

    DECLARE

        _user_uuid   uuid    = uuid_generate_v4();
        _person_uuid uuid    = uuid_generate_v4();
        _city_id     uuid;
        _name        varchar = 'Usuário sem Módulos';
        _email       varchar = 'user-without-modules@hotmail.com';
        _password    varchar = general.generate_bcrypt_hash('Teste123');
        _phone       varchar = '519632697';
        _zip_code    varchar = '99999999';
        _address     varchar = 'Rua Otto Daudt';
        _district    varchar = 'Feitoria';
        _number      varchar = '1770';
        _complement  varchar = 'casa';
        _city        varchar = 'São Leopoldo';
        _uf          varchar = 'RS';

        _profile varchar := 'ADMIN_MODULE';
        _profile_uuid uuid;

    BEGIN
        SELECT id INTO _profile_uuid FROM users.profiles WHERE unique_name = _profile;
        SELECT id INTO _city_id FROM city.cities WHERE description = _city;

        INSERT INTO person.persons(id, city_id, phone, zip_code, address, number_address, complement, district, uf)
        VALUES(
                  _person_uuid,
                  _city_id,
                  _phone,
                  _zip_code,
                  _address,
                  _number,
                  _complement,
                  _district,
                  _uf
              );

        INSERT INTO users.users (id, person_id, name, email, password, active)
        VALUES
            (   _user_uuid,
                _person_uuid,
                _name,
                _email,
                _password,
                false
            );

        INSERT INTO users.admin_users (user_id)
        VALUES
            (
                _user_uuid
            );

        INSERT INTO users.profiles_users (profile_id, user_id)
        VALUES
            (
                _profile_uuid,
                _user_uuid
            );
    END $$;
commit;
